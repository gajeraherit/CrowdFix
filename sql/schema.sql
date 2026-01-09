-- Database: crowdfix
CREATE DATABASE IF NOT EXISTS crowdfix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crowdfix;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('citizen','admin') NOT NULL DEFAULT 'citizen',
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS issues (
    issue_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255),
    latitude FLOAT NOT NULL,
    longitude FLOAT NOT NULL,
    issue_type VARCHAR(50) NOT NULL,
    priority_score INT NOT NULL DEFAULT 0,
    status VARCHAR(50) NOT NULL DEFAULT 'Pending',
    created_at DATETIME NOT NULL,
    reported_by INT,
    FOREIGN KEY (reported_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS votes (
    vote_id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    user_id INT NOT NULL,
    vote_level ENUM('low','medium','high') NOT NULL,
    FOREIGN KEY (issue_id) REFERENCES issues(issue_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY uniq_vote (issue_id, user_id)
);

