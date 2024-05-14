-- Insert barbers
INSERT INTO barbers (name, contact) VALUES
    ('John Smith', 'john.smith@example.com'),
    ('Jane Doe', 'jane.doe@example.com'),
    ('Bob Johnson', 'bob.johnson@example.com');

-- Insert users
INSERT INTO users (name, email, phone, password) VALUES
    ('Jakub Novák', 'jakub@example.com', '1234567890', '$2y$10$eI7j/U8IgycruZl/EaRzSe5Dmsk0Rpq6XZjcOj7j/6TFN/8we9tpS'),
    ('Tereza Kovaříková', 'tereza@example.com', '0987654321', '$2y$10$wHwGhX3IqT0Q3m/j5aTkheC6JqFVGbljZ6d5iYsdL1i08QZAF5Wqi'),
    ('Petr Svoboda', 'petr@example.com', '1029384756', '$2y$10$nv95F5gQj.H7D3A1ktIuFu4LDjFwOv.JQK5QzyerRPhQ6A6J4ljZG'),
    ('Barbora Dvořáková', 'barbora@example.com', '5647382910', '$2y$10$SXJq1OuB/m1wO5T9cKQFh.LFSvKJvhPvhsRhkP7Zd1g60Zbf6UOFi'),
    ('Martin Procházka', 'martin@example.com', '6473829101', '$2y$10$5Tb1p6uozTQ1A9fP3i9/SOedg0b0UJ91TtJwIWZl0dfH6J7H1tdCe');


-- Insert schedules
INSERT INTO schedules (barber_id, date, time, is_available) VALUES
    (1, '2024-05-15', '09:00:00', 1),
    (1, '2024-05-15', '10:00:00', 1),
    (2, '2024-05-15', '11:00:00', 1),
    (2, '2024-05-15', '12:00:00', 1),
    (3, '2024-05-15', '13:00:00', 1),
    (3, '2024-05-15', '14:00:00', 1),
    (1, '2024-05-16', '09:00:00', 1),
    (1, '2024-05-16', '10:00:00', 1),
    (2, '2024-05-16', '11:00:00', 1),
    (2, '2024-05-16', '12:00:00', 1),
    (3, '2024-05-16', '13:00:00', 1),
    (3, '2024-05-16', '14:00:00', 1);
