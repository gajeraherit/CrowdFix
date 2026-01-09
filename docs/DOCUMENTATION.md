# CrowdFix – Crowdsourced Road Issue Reporting System

## Abstract
CrowdFix lets citizens report road hazards, vote on urgency, and help authorities prioritize fixes. A PHP MVC stack with MySQL stores issues, votes, and analytics for admins, including heatmaps and smart priority scoring.

## Problem Statement
Road issues often go unreported or unprioritized. Authorities lack real-time, geo-tagged data and citizen feedback, causing slow responses and resource misallocation.

## Proposed System
- Citizens submit geo-tagged issues with photos.
- Crowd voting (low/medium/high) drives a dynamic priority score.
- Admins view heatmaps, category stats, and manage status/priority.
- Duplicate detection suggests merges for nearby similar reports.

## Architecture
- **Frontend:** Bootstrap 5, Leaflet map picker, Chart.js analytics.
- **Backend:** Core PHP (MVC folders), PDO prepared statements, session auth.
- **Database:** MySQL with `users`, `issues`, `votes`.
- **Priority Logic:** `Priority = (VoteScore*2) + DaysSinceReported + IssueTypeWeight`.

## ER Diagram (textual)
- User (1..n) — reports —> Issue
- User (1..n) — votes —> Vote —> Issue (1..n)
- Issue has fields: title, description, coords, image, issue_type, priority_score, status, reported_by.

## DFD (Level-0 textual)
- Citizen: register/login → report issue → upload image → vote on issues.
- System: validates data → stores in DB → recalculates priority.
- Admin: views dashboard → updates status/priority → monitors heatmap/analytics.

## Database Design
- See `sql/schema.sql` and `sql/sample_data.sql`.
- Index: unique (issue_id, user_id) on votes prevents duplicate voting.

## Algorithms
- **Priority:** `($voteScore*2) + $daysSince + $issueTypeWeight` in `Issue::recalcPriority`.
- **Duplicate detection:** Haversine distance filter within radius by issue type (`Issue::findDuplicates`).
- **Heatmap:** Leaflet circles weighted by `priority_score`.

## Security Measures
- PDO prepared statements; password hashing (bcrypt).
- CSRF tokens on forms; session-based authentication and role checks.
- Basic image validation (MIME check) and uploads isolated to `/uploads`.

## Setup & Run
1) Import DB  
   - Run `sql/schema.sql` then `sql/sample_data.sql` in MySQL.  
2) Configure DB creds  
   - Update `config/db.php` constants if needed.  
3) Deploy  
   - Place project in web root (e.g., `C:/xampp/htdocs/CrowdFix`).  
   - Ensure `uploads` is writable.  
4) Access  
   - Visit `http://localhost/CrowdFix/index.php`.  
   - Admin login: `admin@crowdfix.test` / `password`.

## Module Walkthrough
- **Authentication:** `AuthController`, `User` model, views `login.php`, `register.php`.
- **Issue Reporting:** `IssueController::create`, map picker in `report_issue.php`.
- **Voting:** `IssueController::vote`, unique per user/issue enforced in DB.
- **Priority Calculation:** `Issue::recalcPriority`, weights by type and age.
- **Heatmap & Analytics:** `AdminController::dashboardData`, Chart.js and Leaflet in `admin_dashboard.php`.
- **Status Management:** Admin-only status update forms.
- **Duplicate Detection:** Inline warning when reporting (shows nearby titles).

## Testing Suggestions
- Report new issue, confirm coordinates captured.
- Vote as same user twice → should update vote, not duplicate.
- Change status as admin and see dashboard update.
- Inspect heatmap and charts after adding sample data.
- Try invalid CSRF token to confirm rejection.

## Screenshots (to capture)
- Login/Register, Issue list with votes, Report form with map, Admin dashboard charts/heatmap.

## Conclusion
CrowdFix channels citizen input into actionable, data-driven maintenance. Extensible modules support future additions like SMS alerts, SLA timers, and automated ticketing.

