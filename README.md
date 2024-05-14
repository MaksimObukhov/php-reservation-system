# Barbershop Service Booking Page

## Overview

This project is a service booking page for a barbershop. It allows users to book appointments for haircuts without having to register. Users provide their name, email, and phone number, and then select a date and time that matches the barber's availability schedule. Upon booking, they receive a confirmation email.

## Features

- **User Booking:**
  - Book an appointment without registration
  - Fill in name, email, and phone number
  - Select a date and time based on barber availability
  - Receive email confirmation for the booking

- **Admin Interface:**
  - Manage bookings (view, edit, cancel)
  - Add new available dates to the schedule
  - Admin login interface

## Database Schema

The application uses a database with the following schema:

- **User (Client) Table:**
  - `id` (Primary Key)
  - `name`
  - `email`
  - `phone`

- **Booking Table:**
  - `id` (Primary Key)
  - `user_id` (Foreign Key to User table)
  - `barber_id` (Foreign Key to Barber table)
  - `date`
  - `time`

- **Schedule Table:**
  - `id` (Primary Key)
  - `barber_id` (Foreign Key to Barber table)
  - `date`
  - `time`

- **Barber Table:**
  - `id` (Primary Key)
  - `name`
  - `contact_details`

## Project Structure

- `index.php`: Main page for booking appointments
- `admin.php`: Admin interface for managing bookings
- `database.php`: Database connection and schema setup
- `email.php`: Script for sending confirmation emails
- `styles.css`: CSS for styling the pages
- `scripts.js`: JavaScript for front-end interactivity

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/barbershop-booking.git
   cd barbershop-booking
   ```

2. Set up the database:
    - Create a MySQL database and import the provided `schema.sql` file to set up the tables.
    - Update the `database.php` file with your database credentials.

3. Configure email settings:
    - Update the `email.php` file with your email server details.

4. Run the application on your local server or deploy it to a web server.

## Usage

- Open `index.php` in your web browser to access the booking page.
- Admins can access `admin.php` for managing bookings.

## License

This project is licensed under the MIT License. See the `LICENSE` file for more details.