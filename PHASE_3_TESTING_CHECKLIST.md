# PHASE 3 - TESTING & VERIFICATION CHECKLIST
## Complete Campaign Management System

### 🎯 Testing Overview
This document provides step-by-step testing procedures for all Phase 3 features.

---

## ✅ PRE-TESTING SETUP

### 1. Server Status
- [ ] Server running on http://127.0.0.1:8888
- [ ] Database connected and migrations executed
- [ ] Admin user exists (+22500000000 / password)
- [ ] Storage link created (`php artisan storage:link`)

### 2. Test Accounts
**Admin Account:**
- Phone: `+22500000000`
- Password: `password`
- Status: Admin

**Regular User Account:**
- Register a new user at `/register`
- Or use existing test account

---

## 📋 PHASE 3.1 & 3.2 - CAMPAIGN CRUD

### Test 1: Create Campaign
- [ ] Login as admin
- [ ] Navigate to `/admin/campaigns`
- [ ] Click "Nouvelle Campagne"
- [ ] Fill in all required fields:
  - [ ] Title: "Test Campaign 1"
  - [ ] Description: "This is a test campaign"
  - [ ] CPA Link: "https://example.com/affiliate"
  - [ ] Reward: "100"
  - [ ] Start Date: Today
  - [ ] End Date: 30 days from now
  - [ ] Upload an image (JPG/PNG)
  - [ ] Geographic restrictions: "CI,SN,BF"
  - [ ] Validation rules: "Complete signup and verify email"
- [ ] Click "Créer la Campagne"
- [ ] Verify success message appears
- [ ] Verify campaign created with status "draft"
- [ ] Verify image uploaded successfully

### Test 2: List Campaigns
- [ ] Navigate to `/admin/campaigns`
- [ ] Verify campaign list displays
- [ ] Check table columns show correctly
- [ ] Verify pagination works (if > 15 campaigns)

### Test 3: Search Campaigns
- [ ] Enter search term in search box
- [ ] Click "Filtrer"
- [ ] Verify results match search term
- [ ] Clear search and verify all campaigns return

### Test 4: Filter by Status
- [ ] Select "Brouillon" from status dropdown
- [ ] Click "Filtrer"
- [ ] Verify only draft campaigns shown
- [ ] Test other status filters

### Test 5: View Campaign Details
- [ ] Click eye icon on a campaign
- [ ] Verify all campaign details display
- [ ] Check statistics section shows
- [ ] Verify image displays correctly
- [ ] Check action buttons present

### Test 6: Edit Campaign
- [ ] On campaign detail page, click "Modifier"
- [ ] Change campaign title
- [ ] Change reward amount
- [ ] Upload new image
- [ ] Click "Mettre à Jour"
- [ ] Verify success message
- [ ] Verify changes saved

### Test 7: Duplicate Campaign
- [ ] On campaign detail page, click "Dupliquer"
- [ ] Verify new campaign created with "(Copie)" suffix
- [ ] Verify new campaign has "draft" status
- [ ] Check all data copied except status

### Test 8: Delete Campaign
- [ ] Navigate to campaign list
- [ ] Click trash icon on a test campaign
- [ ] Confirm deletion in dialog
- [ ] Verify campaign removed from list
- [ ] Verify soft delete (check database if needed)

---

## 📋 PHASE 3.3 - APPROVAL WORKFLOW

### Test 9: Submit for Approval
- [ ] Create or edit a campaign
- [ ] Ensure status is "draft"
- [ ] Click "Soumettre" button
- [ ] Verify status changes to "pending_approval"
- [ ] Verify success message appears

### Test 10: View Pending Campaigns
- [ ] Navigate to `/admin/campaigns/approvals/pending`
- [ ] Verify pending campaigns list
- [ ] Check campaign details display
- [ ] Verify action buttons present

### Test 11: Approve Campaign
- [ ] On pending campaigns page
- [ ] Click "Approuver et Publier" on a campaign
- [ ] Confirm approval
- [ ] Verify status changes to "published"
- [ ] Verify success message
- [ ] Check campaign removed from pending list
- [ ] Verify `approved_by` and `approved_at` set in database

### Test 12: Reject Campaign
- [ ] Submit a campaign for approval
- [ ] Go to pending campaigns
- [ ] Click "Rejeter" button
- [ ] Enter rejection reason: "Images not clear"
- [ ] Click "Confirmer le Rejet"
- [ ] Verify status changes to "draft"
- [ ] Verify success message
- [ ] Check audit log created

### Test 13: Request Modifications
- [ ] Submit a campaign for approval
- [ ] Go to pending campaigns
- [ ] Click "Demander des Modifications"
- [ ] Enter modification request: "Please add more details"
- [ ] Click "Envoyer la Demande"
- [ ] Verify status changes to "draft"
- [ ] Verify success message
- [ ] Check audit log created

### Test 14: Pause Campaign
- [ ] Approve a campaign (status = published)
- [ ] Go to campaign detail page
- [ ] Click "Pause" button
- [ ] Verify status changes to "paused"
- [ ] Verify campaign not visible to users
- [ ] Check audit log

### Test 15: Resume Campaign
- [ ] On paused campaign detail page
- [ ] Click "Relancer" button
- [ ] Verify status changes to "published"
- [ ] Verify campaign visible to users again
- [ ] Check audit log

---

## 📋 PHASE 3.4 - USER SIDE

### Test 16: Browse Campaigns (Public)
- [ ] Logout or open incognito window
- [ ] Navigate to `/campaigns`
- [ ] Verify campaign grid displays
- [ ] Check only published campaigns shown
- [ ] Verify campaign cards show:
  - [ ] Image or placeholder
  - [ ] Title
  - [ ] Description excerpt
  - [ ] Reward amount
  - [ ] End date
  - [ ] "Voir Détails" button

### Test 17: Search Campaigns (User)
- [ ] On campaigns page
- [ ] Enter search term: "Test"
- [ ] Click "Filtrer"
- [ ] Verify matching campaigns display
- [ ] Clear and test with different term

### Test 18: Filter by Reward
- [ ] Enter min reward: "50"
- [ ] Enter max reward: "200"
- [ ] Click "Filtrer"
- [ ] Verify only campaigns in range shown

### Test 19: Sort Campaigns
- [ ] Click "Plus Récentes" - verify latest first
- [ ] Click "Récompense +" - verify highest reward first
- [ ] Click "Récompense -" - verify lowest reward first
- [ ] Click "Fin Proche" - verify ending soon first

### Test 20: View Campaign Detail (User)
- [ ] Click on a campaign card
- [ ] Verify full campaign details display
- [ ] Check participation button shows
- [ ] Verify statistics display
- [ ] Check campaign timeline

### Test 21: Participate in Campaign
- [ ] Login as regular user
- [ ] Navigate to a published campaign
- [ ] Click "Participer Maintenant"
- [ ] Verify redirected to CPA link
- [ ] Check participation recorded in database
- [ ] Verify status is "pending"

### Test 22: Prevent Duplicate Participation
- [ ] Return to same campaign
- [ ] Verify participation status shown
- [ ] Verify "Participer" button replaced with status
- [ ] Try to participate again
- [ ] Verify error message about already participating

### Test 23: View Participation History
- [ ] Navigate to `/my-participations`
- [ ] Verify participation list displays
- [ ] Check stats dashboard shows:
  - [ ] Total participations
  - [ ] Pending count
  - [ ] Completed count
  - [ ] Total pieces earned
- [ ] Verify table shows all participations
- [ ] Check status badges display correctly

### Test 24: Geographic Restrictions
- [ ] Create campaign with country restrictions: "CI"
- [ ] Approve and publish campaign
- [ ] Login as user with different country
- [ ] Try to participate
- [ ] Verify error message about geographic restriction

---

## 📋 PHASE 3.5 - ANALYTICS

### Test 25: View Analytics Dashboard
- [ ] Login as admin
- [ ] Go to any campaign detail page
- [ ] Click "Analytiques" button
- [ ] Verify analytics dashboard loads
- [ ] Check all metric cards display:
  - [ ] Total participants
  - [ ] Completed participations
  - [ ] Conversion rate
  - [ ] Total pieces distributed
  - [ ] Pending participations
  - [ ] Rejected participations
  - [ ] Average completion time

### Test 26: Daily Participations Chart
- [ ] Verify chart displays on analytics page
- [ ] Check last 30 days shown
- [ ] Verify two lines (Total & Completed)
- [ ] Hover over data points - verify tooltips
- [ ] Check chart is responsive

### Test 27: Hourly Distribution Chart
- [ ] Verify bar chart displays
- [ ] Check 24 hours (0h-23h) shown
- [ ] Verify bars represent participation count
- [ ] Test chart interactivity

### Test 28: Geographic Distribution Chart
- [ ] Create participations from different countries
- [ ] Verify doughnut chart displays
- [ ] Check top 10 countries shown
- [ ] Verify legend displays correctly
- [ ] Test chart click interactions

### Test 29: Status Breakdown Chart
- [ ] Verify doughnut chart displays
- [ ] Check three segments:
  - [ ] Completed (green)
  - [ ] Pending (yellow)
  - [ ] Rejected (red)
- [ ] Verify percentages add up to 100%

### Test 30: Top Performers List
- [ ] Create some completed participations
- [ ] Verify last 10 completions listed
- [ ] Check user names display
- [ ] Verify pieces earned shown
- [ ] Check completion dates

### Test 31: Export CSV
- [ ] Click "Exporter CSV" button
- [ ] Verify file downloads
- [ ] Open CSV in Excel/spreadsheet
- [ ] Check columns:
  - [ ] ID
  - [ ] User name
  - [ ] Phone
  - [ ] Country
  - [ ] Status
  - [ ] Pieces earned
  - [ ] Participation date
  - [ ] Completion date
  - [ ] Completion time (minutes)
- [ ] Verify data accuracy

---

## 📋 INTEGRATION TESTS

### Test 32: Complete Workflow (Admin)
- [ ] Login as admin
- [ ] Create new campaign
- [ ] Upload image
- [ ] Submit for approval
- [ ] Approve campaign
- [ ] View published campaign on user side
- [ ] Pause campaign
- [ ] Resume campaign
- [ ] View analytics
- [ ] Export data
- [ ] Edit campaign
- [ ] Duplicate campaign

### Test 33: Complete Workflow (User)
- [ ] Register new account
- [ ] Browse campaigns
- [ ] Search for campaigns
- [ ] Filter by reward
- [ ] View campaign detail
- [ ] Participate in campaign
- [ ] View participation history
- [ ] Check participation status

### Test 34: Audit Logging
- [ ] Perform approval action
- [ ] Check `admin_audit_logs` table
- [ ] Verify log entry created
- [ ] Check fields:
  - [ ] admin_id
  - [ ] action
  - [ ] entity_type = "Campaign"
  - [ ] entity_id
  - [ ] details
  - [ ] ip_address
  - [ ] user_agent

---

## 📋 RESPONSIVE DESIGN TESTS

### Test 35: Mobile View (< 768px)
- [ ] Resize browser to mobile width
- [ ] Check campaign cards stack vertically
- [ ] Verify images responsive
- [ ] Test mobile bottom navigation
- [ ] Check tables scroll horizontally
- [ ] Verify forms are usable
- [ ] Test modals on mobile

### Test 36: Tablet View (768px - 992px)
- [ ] Resize to tablet width
- [ ] Check 2-column grid for campaigns
- [ ] Verify navigation works
- [ ] Test all interactive elements

### Test 37: Desktop View (> 992px)
- [ ] View on full desktop width
- [ ] Check 3-column grid for campaigns
- [ ] Verify sidebar layouts
- [ ] Test all features

---

## 📋 SECURITY TESTS

### Test 38: Authentication Required
- [ ] Logout
- [ ] Try to access `/admin/campaigns` - verify redirect to login
- [ ] Try to POST `/campaigns/{id}/participate` - verify 401/redirect
- [ ] Try to access `/my-participations` - verify redirect

### Test 39: Admin Authorization
- [ ] Login as regular user
- [ ] Try to access `/admin/campaigns` - verify 403 error
- [ ] Try to access `/admin/campaigns/approvals/pending` - verify 403
- [ ] Try to access analytics - verify 403

### Test 40: Data Validation
- [ ] Try to create campaign without title - verify error
- [ ] Try to create campaign with end date before start date - verify error
- [ ] Try to upload file > 2MB - verify error
- [ ] Try to upload non-image file - verify error
- [ ] Try negative reward amount - verify error

---

## 📋 PERFORMANCE TESTS

### Test 41: Page Load Times
- [ ] Campaign list loads in < 2 seconds
- [ ] Campaign detail loads in < 1 second
- [ ] Analytics page loads in < 3 seconds
- [ ] Search results load in < 2 seconds

### Test 42: Large Data Sets
- [ ] Create 50+ campaigns
- [ ] Verify pagination works smoothly
- [ ] Test search performance
- [ ] Check analytics with many participations

---

## 📋 EDGE CASES

### Test 43: Empty States
- [ ] View campaigns when none exist
- [ ] View pending campaigns when none exist
- [ ] View participations when none exist
- [ ] View analytics with no data
- [ ] Verify helpful empty state messages

### Test 44: Date Edge Cases
- [ ] Campaign ending today
- [ ] Campaign starting tomorrow
- [ ] Expired campaign
- [ ] Campaign with same start and end date

### Test 45: Character Limits
- [ ] Very long campaign title (255+ chars)
- [ ] Very long description (1000+ chars)
- [ ] Special characters in title
- [ ] Emoji in description

---

## ✅ FINAL VERIFICATION

### Checklist Summary:
- [ ] All Phase 3.1 & 3.2 tests passed (8 tests)
- [ ] All Phase 3.3 tests passed (7 tests)
- [ ] All Phase 3.4 tests passed (9 tests)
- [ ] All Phase 3.5 tests passed (7 tests)
- [ ] All integration tests passed (3 tests)
- [ ] All responsive tests passed (3 tests)
- [ ] All security tests passed (3 tests)
- [ ] All performance tests passed (2 tests)
- [ ] All edge case tests passed (3 tests)

**Total Tests: 45**

---

## 🐛 BUG REPORTING

If you find any issues during testing:

1. **Document the bug:**
   - What were you trying to do?
   - What did you expect to happen?
   - What actually happened?
   - Steps to reproduce

2. **Check:**
   - Browser console for JavaScript errors
   - Laravel log: `storage/logs/laravel.log`
   - Network tab for failed requests

3. **Common Fixes:**
   - Clear browser cache
   - Run `php artisan config:clear`
   - Run `php artisan route:clear`
   - Run `php artisan view:clear`
   - Restart server

---

## 🎉 COMPLETION CRITERIA

Phase 3 is considered complete when:
- ✅ All 45 tests pass
- ✅ No critical bugs found
- ✅ All features work as expected
- ✅ Responsive on all devices
- ✅ Security measures verified
- ✅ Performance acceptable
- ✅ French UI throughout
- ✅ Ready for production deployment

---

## 📝 NOTES

- Test with different browsers (Chrome, Firefox, Safari)
- Test with different screen sizes
- Create realistic test data
- Use different user accounts
- Document any issues found
- Retest after fixes applied

---

**Testing Date:** _______________

**Tester:** _______________

**Status:** ⬜ Not Started | ⬜ In Progress | ⬜ Complete

**Issues Found:** _______________

**Ready for Production:** ⬜ Yes | ⬜ No
