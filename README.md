# User Management System API (Apex)

User Management System API using Laravel. This API will be responsible for handling user profiles within an application, including operations such as creating, updating, viewing, and deleting users.

## Table of Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Routes](#routes)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Introduction

The User Management System API is built with Laravel and provides endpoints for managing user profiles within an application. It includes features for user registration, login, profile management, password update, and user deletion.

## Requirements

- PHP >= 7.x
- Composer
- Laravel Framework
- Laravel Passport
- Other dependencies...

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/abiodunmale/User-Management-System-API-Apex-.git
   ```

2. Install dependencies:
   ```bash
   cd User-Management-System-API-Apex-
   composer install
   ```

3. Configure environment variables:
   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your environment-specific configurations, including database settings and Passport keys.

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run database migrations and seeds:
   ```bash
   php artisan migrate --seed
   ```

6. Install Laravel Passport:
   ```bash
   php artisan passport:install
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

1. Register or log in to access protected routes.
2. Use the provided API endpoints for various functionalities.

## Routes

Here are the API routes available in your project:

- **POST** /api/v1/login - User login
- **POST** /api/v1/register - User registration
- **POST** /api/v1/logout - User logout (protected)
- **GET** /api/v1/profile - Get user profile (protected)
- **PUT** /api/v1/profile - Update user profile (protected)
- **PUT** /api/v1/update-password - Update user password (protected)
- **DELETE** /api/v1/user/{id} - Delete user (admin only)
- **POST** /api/v1/user - Create user (admin only)

## Testing

Explain how to run the test suite for your project.

1. Run PHPUnit tests:
   ```bash
   php artisan test
   ```

## Contributing

Explain how others can contribute to your project. Include guidelines for submitting bug reports, feature requests, or pull requests.

## License

Specify the license under which your project is distributed. For example:

This project is licensed under the [MIT License](LICENSE).

---

Feel free to adjust the instructions and details according to your project's specific requirements and configurations.
