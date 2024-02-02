# Task managment

Short description or purpose of your Laravel project.

## Table of Contents
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Database](#database)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Acknowledgements](#acknowledgements)

## Getting Started

### Prerequisites
- PHP (>= 8.0.30)
- Composer

- MySQL or other preferred database
- ...

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/aryanraj78/task-management.git
    ```

2. Navigate to the project directory:

    ```bash
    cd your-laravel-project
    ```

3. Install Composer dependencies:

    ```bash
    composer install
    ```

4. Copy the `.env.example` file to `.env` and configure the database and other settings:

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database credentials and other necessary configurations.

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Run database migrations and seed the database (if applicable):

    ```bash
    php artisan migrate --seed
    ```

7. Serve your application:

    ```bash
    php artisan serve
    ```

    Your Laravel application should now be running at `http://localhost:8000`.

    Admin cred
    email: admin@admin.com
    password: 12345678
