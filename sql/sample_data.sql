USE crowdfix;

INSERT INTO users (name, email, password, role, created_at) VALUES
('Admin User', 'admin@crowdfix.test', '$2y$10$1a0qV4FUnO9RbUR7VszrkOKluzMdGII4XESyqCCpt5TR1qjNenE2C', 'admin', NOW()),
('Jane Citizen', 'jane@crowdfix.test', '$2y$10$1a0qV4FUnO9RbUR7VszrkOKluzMdGII4XESyqCCpt5TR1qjNenE2C', 'citizen', NOW());
-- Password for both: password

INSERT INTO issues (title, description, latitude, longitude, issue_type, priority_score, status, created_at, reported_by) VALUES
('Large pothole near school', 'Dangerous pothole affecting school buses.', 28.610, 77.210, 'pothole', 8, 'Pending', NOW() - INTERVAL 3 DAY, 2),
('Broken streetlight at main road', 'Dark stretch causing accidents.', 28.620, 77.215, 'broken streetlight', 6, 'In Progress', NOW() - INTERVAL 2 DAY, 2),
('Water leakage damaging road', 'Continuous leakage eroding the asphalt.', 28.605, 77.200, 'water leakage', 10, 'Pending', NOW() - INTERVAL 1 DAY, 2);

INSERT INTO votes (issue_id, user_id, vote_level) VALUES
(1, 2, 'high'),
(2, 2, 'medium');

