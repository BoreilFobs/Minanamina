const CACHE_NAME = 'minanamina-v1';
const STATIC_CACHE = 'minanamina-static-v1';
const DYNAMIC_CACHE = 'minanamina-dynamic-v1';

// Static assets to cache
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
];

// Install event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== STATIC_CACHE && name !== DYNAMIC_CACHE)
                    .map((name) => caches.delete(name))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - Network first, fallback to cache
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip admin routes for caching
    if (url.pathname.startsWith('/admin')) {
        return;
    }

    // API requests - Network only
    if (url.pathname.startsWith('/api')) {
        event.respondWith(
            fetch(request)
                .catch(() => new Response(JSON.stringify({ error: 'Offline' }), {
                    headers: { 'Content-Type': 'application/json' }
                }))
        );
        return;
    }

    // HTML pages - Network first
    if (request.headers.get('Accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const clone = response.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, clone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(request)
                        .then((response) => response || caches.match('/offline'));
                })
        );
        return;
    }

    // Static assets - Cache first
    event.respondWith(
        caches.match(request)
            .then((cached) => {
                if (cached) {
                    return cached;
                }
                return fetch(request).then((response) => {
                    const clone = response.clone();
                    caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, clone);
                    });
                    return response;
                });
            })
    );
});

// Background sync for form submissions
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-participations') {
        event.waitUntil(syncParticipations());
    }
});

async function syncParticipations() {
    // Handle offline participation submissions when back online
    console.log('Syncing participations...');
}

// Push notifications
self.addEventListener('push', (event) => {
    const data = event.data?.json() || {};
    const options = {
        body: data.body || 'Nouvelle notification',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/'
        },
        actions: [
            { action: 'open', title: 'Ouvrir' },
            { action: 'close', title: 'Fermer' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Minanamina', options)
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    if (event.action === 'open' || !event.action) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});
