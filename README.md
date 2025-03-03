# Hotel Management System

A comprehensive hotel management system built with Laravel, designed to streamline hotel operations including room management, bookings, guest information, and payments.

## Features

- **Dashboard**: Get an overview of key metrics including room availability, upcoming check-ins/check-outs, and revenue.
- **Room Management**: Manage different room types, room status, and room features.
- **Booking System**: Create, view, and manage bookings with check-in/check-out functionality.
- **Guest Management**: Store and manage guest information and booking history.
- **Payment Processing**: Record and track payments for bookings.
- **Reporting**: Generate reports on occupancy rates, revenue, and other key metrics.
- **Responsive Design**: Works on desktop, tablet, and mobile devices.

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL or compatible database
- Node.js and NPM (for frontend assets)

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/hotel-management-system.git
   cd hotel-management-system
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Copy the example environment file and configure your database:
   ```
   cp .env.example .env
   ```

4. Generate an application key:
   ```
   php artisan key:generate
   ```

5. Run the database migrations and seed the database with sample data:
   ```
   php artisan migrate --seed
   ```

6. Start the development server:
   ```
   php artisan serve
   ```

7. Visit `http://localhost:8000` in your browser.

## Database Structure

The system uses the following main database tables:

- **guests**: Stores guest information
- **room_types**: Defines different types of rooms and their features
- **rooms**: Individual rooms with their status and type
- **bookings**: Reservation information linking guests to rooms
- **payments**: Payment records for bookings

## Usage

### Room Management

- Add new room types with different features and pricing
- Add individual rooms and assign them to room types
- Update room status (available, occupied, maintenance, etc.)

### Booking Management

- Create new bookings for guests
- Check guests in and out
- View booking history and details
- Cancel or modify bookings

### Guest Management

- Register new guests
- View guest history and preferences
- Search for guests by name, email, or other criteria

### Payment Processing

- Record payments for bookings
- Generate receipts
- Track payment status

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# hms" 
