# Laravel Task Management Application

## Requirements

Before running the project, ensure you have the following installed:

* PHP
* Composer
* Node.js & NPM
* MySQL (or compatible database)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Create Environment File

Copy the example environment file:

```bash
cp .env.example .env
```

### 3. Configure Database

Update the database credentials in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4. Install PHP Dependencies

```bash
composer install
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database

```bash
php artisan db:seed
```

This command will:

* Create a default user account.
* Generate 50 dummy tasks.

> Note: Every time the seeder is executed, 50 additional dummy tasks will be created.

### Default User Credentials

| Field    | Value                                         |
| -------- | --------------------------------------------- |
| Email    | [admin@example.com](mailto:admin@example.com) |
| Password | StrongPassword!@#                             |

### 8. Install Node Dependencies

```bash
npm install
```

or

```bash
npm i
```

### 9. Build Frontend Assets

```bash
npm run build
```

For development, you may also use:

```bash
npm run dev
```

### 10. Run Tests

```bash
php artisan test tests/
```

### 11. Start the Application

```bash
php artisan serve
```

The application will be available at:

```
http://127.0.0.1:8000
```

## Additional Commands

### Refresh Database and Seed Data

```bash
php artisan migrate:fresh --seed
```

This command recreates all tables and seeds the database from scratch.

