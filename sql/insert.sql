-- Insert barbers
INSERT INTO barbers (name, contact) VALUES
    ('Petr "Strih" Novák', 'petr.strih@example.com'),
    ('Martin "Kadeř" Kovář', 'martin.kader@example.com'),
    ('Jakub "Úprava" Svoboda', 'jakub.uprava@example.com');

-- Insert users
INSERT INTO users (name, email, phone, password) VALUES
    ('Jakub Novák', 'jakub@example.com', '1234567890', '$2y$10$eI7j/U8IgycruZl/EaRzSe5Dmsk0Rpq6XZjcOj7j/6TFN/8we9tpS'),
    ('Tereza Kovaříková', 'tereza@example.com', '0987654321', '$2y$10$wHwGhX3IqT0Q3m/j5aTkheC6JqFVGbljZ6d5iYsdL1i08QZAF5Wqi'),
    ('Petr Svoboda', 'petr@example.com', '1029384756', '$2y$10$nv95F5gQj.H7D3A1ktIuFu4LDjFwOv.JQK5QzyerRPhQ6A6J4ljZG'),
    ('Barbora Dvořáková', 'barbora@example.com', '5647382910', '$2y$10$SXJq1OuB/m1wO5T9cKQFh.LFSvKJvhPvhsRhkP7Zd1g60Zbf6UOFi'),
    ('Martin Procházka', 'martin@example.com', '6473829101', '$2y$10$5Tb1p6uozTQ1A9fP3i9/SOedg0b0UJ91TtJwIWZl0dfH6J7H1tdCe');

-- Insert admins
INSERT INTO admins (name, email, password) VALUES
    ('Maksim Obukhov', 'obum00@vse.cz', '$2y$10$mquavnGF4S.jg5NE2pv5b.oMFNqQakMGejgxIURj7UPWel9njlp0S');

-- Insert schedules
INSERT INTO schedules (barber_id, date, time, is_available) VALUES
    (1, '2024-06-03', '09:00:00', TRUE),
    (2, '2024-06-03', '10:00:00', TRUE),
    (3, '2024-06-03', '11:00:00', TRUE),
    (1, '2024-06-04', '09:00:00', TRUE),
    (1, '2024-06-04', '10:00:00', TRUE),
    (2, '2024-06-04', '11:00:00', TRUE),
    (2, '2024-06-04', '12:00:00', TRUE),
    (3, '2024-06-04', '13:00:00', TRUE),
    (3, '2024-06-04', '14:00:00', TRUE),
    (1, '2024-06-05', '09:00:00', TRUE),
    (1, '2024-06-05', '10:00:00', TRUE),
    (2, '2024-06-05', '11:00:00', TRUE),
    (2, '2024-06-05', '12:00:00', TRUE),
    (3, '2024-06-05', '13:00:00', TRUE),
    (3, '2024-06-05', '14:00:00', TRUE),
    (1, '2024-06-06', '09:00:00', TRUE),
    (1, '2024-06-06', '10:00:00', TRUE),
    (2, '2024-06-06', '11:00:00', TRUE),
    (2, '2024-06-06', '12:00:00', TRUE),
    (3, '2024-06-06', '13:00:00', TRUE),
    (3, '2024-06-06', '14:00:00', TRUE);
