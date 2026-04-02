# Dev Task Repartition (3 Members)

## Current Project Status
- Routing is already started in `public/index.php`.
- Database connection is started in `src/config/database.php`.
- Most controllers, models, and views are placeholders right now.

## Recommended Split for 3 Members

### 1) Member A: Auth + User Access (Core Security Owner)
**Own files:**
- `src/controllers/loginController.php`
- `src/controllers/registerController.php`
- `src/models/loginModel.php`
- `src/models/registerModel.php`
- `src/views/login.php`
- `src/views/register.php`

**Tasks:**
1. Implement register and login flow (POST validation, password hashing, session management).
2. Add role-based redirect after login (agent/admin/user).
3. Handle logout and unauthorized access guard.

**Deliverable:**
- Fully working auth routes in `public/index.php`.

### 2) Member B: Property Catalog (Main Business Feature Owner)
**Own files:**
- `src/controllers/propertyListController.php`
- `src/controllers/propertyDetailController.php`
- `src/models/propertyListModel.php`
- `src/models/propertyDetailModel.php`
- `src/views/propertyList.php`
- `src/views/propertyDetail.php`

**Tasks:**
1. List properties from DB with pagination/filter.
2. Build property detail page with images and full info.
3. Add safe query handling and error fallback pages.

**Deliverable:**
- Production-ready browsing flow from list to detail.

### 3) Member C: Dashboards + Integration + QA (Stability Owner)
**Own files:**
- `src/controllers/adminDashboardController.php`
- `src/controllers/agentDashboardController.php`
- `src/models/adminDashboardModel.php`
- `src/models/agentDashboardModel.php`
- `src/views/adminDashboard.php`
- `src/views/agentDashboard.php`
- `public/index.php`
- `src/config/database.php`

**Tasks:**
1. Implement admin and agent dashboard data.
2. Maintain routing integration and shared error handling.
3. Own final QA pass and bug triage across all modules.

**Deliverable:**
- Stable app integration and dashboard access by role.

## Team Process to Avoid Conflicts

### Branches
- `feature/auth-register-login`
- `feature/property-list-detail`
- `feature/dashboards-integration`

### Merge Order Each Cycle
1. First merge model changes.
2. Then controller changes.
3. Then views and route updates.

### Daily Sync (15 min)
1. What was finished.
2. What is blocked.
3. What interfaces changed (method names, expected data keys).

### Definition of Done for Each Task
- Route reachable.
- Validation in place.
- DB query uses prepared statements.
- View renders with real data.
- No PHP warnings/notices on page load.

## Optional Next Step
- Convert this into a 7-day sprint board with daily task cards per member.
