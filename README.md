## CrowdFix – Crowdsourced Road Issue Reporting System

CrowdFix is a PHP/MySQL web application that lets **citizens report road issues**, **vote on their urgency**, and helps **municipal authorities** visualize and prioritize repairs using **crowd intelligence, smart priority scoring, and heatmap analytics**.

### Features
- **Citizen portal**
  - Register/login (session-based auth)
  - Report issues with type, description, photo upload
  - Pick exact location on an interactive map (Leaflet + OpenStreetMap)
  - Track reported issues and see status (Pending, In Progress, Resolved, Closed)
  - Vote on issues (Low / Medium / High) to influence priority
- **Admin portal**
  - View all reported issues with priority score and status
  - Change status / override priority
  - Analytics dashboard (Chart.js) for:
    - Issues by category
    - Status breakdown
    - High-priority queue
  - Heatmap view showing clusters of high-priority issues
  - Basic duplicate detection (nearby same-type issues)

### Smart Priority Logic
Each issue’s **priority score** is recalculated based on:

\[
\text{PriorityScore} = (\text{VoteScore} \times 2) + \text{DaysSinceReported} + \text{IssueTypeWeight}
\]

- **VoteScore**: Weighted from votes (High=3, Medium=2, Low=1)
- **DaysSinceReported**: Older unresolved issues slowly rise in priority
- **IssueTypeWeight**: Higher weights for critical types (e.g., accident > water leakage > pothole > streetlight)

This makes CrowdFix more than a simple CRUD app; it continuously reprioritizes work based on live crowd feedback and time.

### Tech Stack
- **Backend**: Core PHP (no framework, simple MVC-style organization)
- **Database**: MySQL (PDO, prepared statements)
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **JS**: Vanilla JS, AJAX patterns ready
- **Charts**: Chart.js
- **Maps**: Leaflet + OpenStreetMap tiles

### Project Structure
- `config/` – DB connection (`db.php`), helpers (auth/session/CSRF)
- `models/` – `User`, `Issue`, `Vote` (priority + duplicate + stats logic)
- `controllers/` – `AuthController`, `IssueController`, `AdminController`
- `views/` – Auth pages, citizen dashboard, issue reporting, admin dashboard
- `assets/` – Custom CSS and JS (charts + maps)
- `sql/` – `schema.sql` (tables) and `sample_data.sql` (demo admin/user/issues)
- `docs/` – `DOCUMENTATION.md` for academic/project-report style write-up

### Setup (XAMPP / WAMP)
1. **Copy project** into web root, e.g. `C:/xampp/htdocs/CrowdFix`.
2. Import database:
   - Run `sql/schema.sql`, then `sql/sample_data.sql` in phpMyAdmin (or MySQL CLI).
3. Update DB credentials in `config/db.php` if needed (host, DB name, user, password).
4. Ensure `uploads/` is writable for image uploads.
5. Visit `http://localhost/CrowdFix/index.php`.

### Default Admin Login
- **Email**: `admin@crowdfix.test`
- **Password**: `password` (or use the credentials/role you configure in the `users` table)

### For College / Final-Year Submission
- Includes: authentication, CRUD operations, voting, analytics, heatmaps, duplicate detection, and documentation.
- See `docs/DOCUMENTATION.md` for:
  - Abstract, problem statement, existing/proposed system
  - System architecture, ER diagram (textual), DFD description
  - Database design, algorithm explanation
  - Testing suggestions and conclusion


