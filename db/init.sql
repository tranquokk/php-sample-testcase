-- SQLite schema for sample testcase project.
-- Create locally with: sqlite3 db/sample.sqlite < db/init.sql
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    email TEXT
);

INSERT INTO users (username, password, email) VALUES
    ('admin', 'admin123', 'admin@example.com'),
    ('tester', 'tester123', 'tester@example.com');
