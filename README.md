# Real Estate MVC PHP Application

## Overview
This is a Real Estate Web Application built using native PHP with an MVC (Model-View-Controller) architecture. It allows users to browse properties, agents to manage their listings, and administrators to oversee the platform.

## Features
* **Authentication & Authorization**: Secure login and registration with role-based access control (User, Agent, Admin).
* **Property Catalog**: Browse properties with pagination, filtering, and detailed property views.
* **Role-Based Dashboards**: Dedicated dashboards for Agents and Admins to manage real estate operations.
* **Secure Database Access**: Prepared statements and robust data validation/sanitization.

## Architecture
This project follows a strict MVC pattern:
* **Controllers** (`src/controllers/`): Handle incoming requests, route to the appropriate model, and pass data to views.
* **Models** (`src/models/`): Contain business logic and database interactions.
* **Views** (`src/views/`): Present the data using HTML and minimal PHP.
* **Config** (`src/config/`): Database and application configuration.
* **Public** (`public/`): The document root, containing the entry point `index.php` and static assets.

## Prerequisites
* PHP 8.x
* MySQL / MariaDB

## Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd Fil-rouge-b2
   ```

2. **Database Setup:**
   * Create a new MySQL database.
   * Import the provided SQL dump: `real_estate.sql` into your database.

3. **Environment Configuration:**
   * Configure your database credentials. Check for an `.env` file or update `src/config/database.php` directly to match your local setup (DB host, user, password, and database name).

4. **Run the Application locally:**
   * Use PHP's built-in server to serve the `public/` directory:
     ```bash
     php -S localhost:8000 -t public
     ```
   * Open your browser and navigate to `http://localhost:8000`.

## Testing & QA
A smoke test script is provided to verify the setup and ensure basic routing and PHP linting passes:
```bash
php scripts/qa-smoke.php
```

## Team & Contribution
Refer to `TEAM_TASK_REPARTITION.md` for details on team roles and responsibilities.
Refer to `CONVENTION.md` for naming, code style, and git commit guidelines.
