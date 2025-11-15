# PHASE 3 - API ROUTES REFERENCE
## Complete Campaign Management System Routes

---

## 🔓 PUBLIC ROUTES (No Authentication Required)

### Campaign Browsing
```
GET  /campaigns
```
**Purpose:** List all published campaigns  
**Query Parameters:**
- `search` - Search in title/description
- `min_reward` - Minimum pieces reward
- `max_reward` - Maximum pieces reward
- `sort` - Sorting: `latest`, `reward_high`, `reward_low`, `ending_soon`
- `page` - Pagination

**Returns:** Campaign grid view with filters

---

```
GET  /campaigns/{campaign}
```
**Purpose:** View single campaign details  
**Parameters:**
- `{campaign}` - Campaign ID

**Returns:** Campaign detail page with participation option

---

## 🔐 AUTHENTICATED USER ROUTES

### Campaign Participation
```
POST /campaigns/{campaign}/participate
```
**Purpose:** User participates in campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- User must be authenticated
- Campaign must be published
- Campaign must be active (within date range)
- User cannot participate twice
- Geographic restrictions checked

**Returns:** Redirect to CPA affiliate link

---

```
GET  /my-participations
```
**Purpose:** View user's participation history  
**Returns:** List of all user participations with stats

---

## 🛡️ ADMIN ROUTES (Requires `auth` + `admin` middleware)

### Campaign Management (CRUD)

```
GET  /admin/campaigns
```
**Purpose:** List all campaigns (admin view)  
**Query Parameters:**
- `search` - Search campaigns
- `status` - Filter by status

**Returns:** Admin campaign list with search/filter

---

```
GET  /admin/campaigns/create
```
**Purpose:** Show campaign creation form  
**Returns:** Campaign creation form

---

```
POST /admin/campaigns
```
**Purpose:** Store new campaign  
**Validation:**
- `title` - required, max:255
- `description` - required
- `cpa_link` - required, url
- `pieces_reward` - required, numeric, min:1
- `start_date` - required, date
- `end_date` - required, date, after:start_date
- `image` - nullable, image, max:2048kb
- `validation_rules` - nullable
- `geographic_restrictions` - nullable

**Returns:** Redirect to campaign detail

---

```
GET  /admin/campaigns/{campaign}
```
**Purpose:** View campaign details (admin)  
**Parameters:**
- `{campaign}` - Campaign ID

**Returns:** Campaign detail with statistics and actions

---

```
GET  /admin/campaigns/{campaign}/edit
```
**Purpose:** Show campaign edit form  
**Parameters:**
- `{campaign}` - Campaign ID

**Returns:** Campaign edit form

---

```
PUT  /admin/campaigns/{campaign}
```
**Purpose:** Update campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:** Same as POST

**Returns:** Redirect to campaign detail

---

```
DELETE /admin/campaigns/{campaign}
```
**Purpose:** Delete campaign (soft delete)  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Cannot delete if has participations

**Returns:** Redirect to campaigns list

---

```
POST /admin/campaigns/{campaign}/submit-approval
```
**Purpose:** Submit campaign for approval  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Status must be 'draft'

**Returns:** Redirect back with success message

---

```
POST /admin/campaigns/{campaign}/duplicate
```
**Purpose:** Duplicate existing campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Returns:** Redirect to edit page of new campaign

---

### Campaign Approvals

```
GET  /admin/campaigns/approvals/pending
```
**Purpose:** View all pending campaigns  
**Returns:** List of campaigns with status 'pending_approval'

---

```
POST /admin/campaigns/{campaign}/approve
```
**Purpose:** Approve and publish campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Status must be 'pending_approval'

**Actions:**
- Sets status to 'published'
- Records approved_by and approved_at
- Creates audit log

**Returns:** Redirect to approvals page

---

```
POST /admin/campaigns/{campaign}/reject
```
**Purpose:** Reject campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Status must be 'pending_approval'
- `rejection_reason` - required, min:10

**Actions:**
- Sets status to 'draft'
- Creates audit log with reason

**Returns:** Redirect to approvals page

---

```
POST /admin/campaigns/{campaign}/request-modifications
```
**Purpose:** Request modifications to campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Status must be 'pending_approval'
- `modification_request` - required, min:10

**Actions:**
- Sets status to 'draft'
- Creates audit log with request

**Returns:** Redirect to approvals page

---

```
POST /admin/campaigns/{campaign}/pause
```
**Purpose:** Pause published campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Status must be 'published'

**Actions:**
- Sets status to 'paused'
- Creates audit log

**Returns:** Redirect back

---

```
POST /admin/campaigns/{campaign}/resume
```
**Purpose:** Resume paused campaign  
**Parameters:**
- `{campaign}` - Campaign ID

**Validation:**
- Status must be 'paused'

**Actions:**
- Sets status to 'published'
- Creates audit log

**Returns:** Redirect back

---

### Campaign Analytics

```
GET  /admin/campaigns/{campaign}/analytics
```
**Purpose:** View campaign analytics dashboard  
**Parameters:**
- `{campaign}` - Campaign ID

**Returns:** Analytics page with charts and statistics

**Data Provided:**
- Total participants
- Pending/Completed/Rejected counts
- Conversion rate
- Total pieces distributed
- Average completion time
- Daily participation chart (30 days)
- Hourly distribution chart
- Geographic distribution chart
- Status breakdown chart
- Top 10 recent completions

---

```
GET  /admin/campaigns/{campaign}/analytics/export
```
**Purpose:** Export campaign analytics to CSV  
**Parameters:**
- `{campaign}` - Campaign ID

**Query Parameters:**
- `format` - Export format (default: 'csv')

**Returns:** CSV file download

**CSV Columns:**
- ID
- User Name
- Phone
- Country
- Status
- Pieces Earned
- Participation Date
- Completion Date
- Completion Time (minutes)

---

## 📊 ROUTE SUMMARY

| Method | Route | Auth | Admin | Purpose |
|--------|-------|------|-------|---------|
| GET | /campaigns | ❌ | ❌ | Public campaign list |
| GET | /campaigns/{id} | ❌ | ❌ | Public campaign detail |
| POST | /campaigns/{id}/participate | ✅ | ❌ | User participation |
| GET | /my-participations | ✅ | ❌ | User history |
| GET | /admin/campaigns | ✅ | ✅ | Admin campaign list |
| GET | /admin/campaigns/create | ✅ | ✅ | Create form |
| POST | /admin/campaigns | ✅ | ✅ | Store campaign |
| GET | /admin/campaigns/{id} | ✅ | ✅ | Admin detail |
| GET | /admin/campaigns/{id}/edit | ✅ | ✅ | Edit form |
| PUT | /admin/campaigns/{id} | ✅ | ✅ | Update campaign |
| DELETE | /admin/campaigns/{id} | ✅ | ✅ | Delete campaign |
| POST | /admin/campaigns/{id}/submit-approval | ✅ | ✅ | Submit for approval |
| POST | /admin/campaigns/{id}/duplicate | ✅ | ✅ | Duplicate campaign |
| GET | /admin/campaigns/approvals/pending | ✅ | ✅ | Pending list |
| POST | /admin/campaigns/{id}/approve | ✅ | ✅ | Approve campaign |
| POST | /admin/campaigns/{id}/reject | ✅ | ✅ | Reject campaign |
| POST | /admin/campaigns/{id}/request-modifications | ✅ | ✅ | Request changes |
| POST | /admin/campaigns/{id}/pause | ✅ | ✅ | Pause campaign |
| POST | /admin/campaigns/{id}/resume | ✅ | ✅ | Resume campaign |
| GET | /admin/campaigns/{id}/analytics | ✅ | ✅ | Analytics dashboard |
| GET | /admin/campaigns/{id}/analytics/export | ✅ | ✅ | Export CSV |

**Total Routes: 21**

---

## 🔑 AUTHENTICATION

### Headers Required (for authenticated routes):
```
Cookie: laravel_session={session_token}
X-CSRF-TOKEN: {csrf_token}
```

### Admin Check:
User model must have `is_admin = 1`

---

## 📝 RESPONSE FORMATS

### Success Responses:
```php
// Redirect with flash message
redirect()->route('...')->with('success', 'Message');

// View with data
return view('view.name', compact('data'));

// File download
return response()->stream($callback, 200, $headers);
```

### Error Responses:
```php
// Validation errors (422)
redirect()->back()->withErrors($validator)->withInput();

// Authorization error (403)
abort(403, 'Accès refusé');

// Not found (404)
abort(404, 'Campaign not found');

// General error
redirect()->back()->with('error', 'Message');
```

---

## 🎯 QUICK REFERENCE

### Create Campaign Flow:
1. `GET /admin/campaigns/create` - Show form
2. `POST /admin/campaigns` - Store campaign
3. Campaign created with status 'draft'

### Approval Flow:
1. `POST /admin/campaigns/{id}/submit-approval` - Submit
2. `GET /admin/campaigns/approvals/pending` - View pending
3. `POST /admin/campaigns/{id}/approve` - Approve
4. Campaign published

### User Participation Flow:
1. `GET /campaigns` - Browse campaigns
2. `GET /campaigns/{id}` - View detail
3. `POST /campaigns/{id}/participate` - Participate
4. Redirect to CPA link
5. `GET /my-participations` - Check status

### Analytics Flow:
1. `GET /admin/campaigns/{id}/analytics` - View dashboard
2. `GET /admin/campaigns/{id}/analytics/export` - Download CSV

---

## 🛠️ TESTING WITH CURL

### Create Campaign:
```bash
curl -X POST http://127.0.0.1:8888/admin/campaigns \
  -H "Content-Type: multipart/form-data" \
  -F "title=Test Campaign" \
  -F "description=Test Description" \
  -F "cpa_link=https://example.com" \
  -F "pieces_reward=100" \
  -F "start_date=2025-11-14" \
  -F "end_date=2025-12-14" \
  -b cookies.txt
```

### Participate:
```bash
curl -X POST http://127.0.0.1:8888/campaigns/1/participate \
  -b cookies.txt \
  -H "X-CSRF-TOKEN: {token}"
```

### Export Analytics:
```bash
curl http://127.0.0.1:8888/admin/campaigns/1/analytics/export \
  -b cookies.txt \
  -o campaign_analytics.csv
```

---

## 📚 RELATED DOCUMENTATION

- See `PHASE_3_COMPLETE.md` for full implementation details
- See `PHASE_3_TESTING_CHECKLIST.md` for testing procedures
- See `TODO_IMPLEMENTATION.md` for project roadmap
