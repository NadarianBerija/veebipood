# Vihmart - Art Gallery & Shop

Vihmart is a professional, custom-built web application designed to showcase and sell artwork. The platform follows a clean Model-View-Controller (MVC) architecture, providing a robust foundation for an art gallery with integrated e-commerce capabilities.

## 🌟 Key Features

### 🏛️ Public Facing Site
- **Dynamic Multi-Language Interface:** Supports Estonian (EE), English (EN), and Russian (RU). Content is served based on URL prefixes (e.g., `/en/shop`), with data retrieved from localized database tables.
- **Categorized Art Gallery:** Artworks are organized into categories like Paintings, Illustrations, Posters, Design, and Photos.
- **Interactive Shop:** 
  - Real-time product listing with pagination (12 items per page).
  - Filtering by category.
  - Detailed product views with multiple images.
- **Session-Based Shopping Cart:** Users can add/remove items to a persistent cart without needing an account.
- **Secure Order System:** Integrated checkout process with anti-spam (honeypot) protection and email notifications via PHPMailer.
- **Hero Slider:** A visually striking homepage slider with customizable content.

### 🔐 Administrative Suite
- **Role-Based Access Control:** Secure login system with distinct 'admin' and 'moderator' roles.
- **Session Security:** 
  - Activity timeout (auto-logout after 30 minutes of inactivity).
  - CSRF protection for all sensitive actions.
  - HttpOnly and SameSite cookies for enhanced security.
- **Content Management System (CMS):**
  - **Art Management:** Full CRUD operations for artworks, including multi-language title/description editing and image uploads.
  - **User Management:** Admin-only access to manage staff accounts and permissions.
  - **Hero Slider Control:** Upload and manage home page slides.
  - **Soft Delete:** Arts and users can be "soft-deleted" (marked as inactive) to preserve data integrity and order history.

## 🏗️ Technical Architecture

### 📁 Directory Structure
```text
Vihmart/
├── admin/                  # Admin Panel Module
│   ├── controllerAdmin/    # Admin business logic
│   ├── modelAdmin/         # Admin data handling
│   ├── public/             # Admin-specific assets
│   ├── routeAdmin/         # Admin routing (routingAdmin.php)
│   └── viewAdmin/          # Admin UI templates
├── controller/             # Public site Controller (Controller.php)
├── inc/                    # Core System Utilities
│   ├── Database.php        # PDO-based DB Wrapper
│   └── Lang.php            # Localization Engine
├── lang/                   # Static language files (ee/en/ru)
├── model/                  # Public site Models (Arts, Category, HeroSlider, Order)
├── public/                 # Global assets (CSS, JS, Images, Icons)
├── route/                  # Public site Routing (routing.php)
├── tests/                  # PHPUnit Test Suite
├── view/                   # Public site View templates
└── vendor/                 # Composer dependencies
```

### 🗄️ Database Schema
The database uses a normalized relational structure optimized for localization:
- **`arts`**: Core art data (id, price, category_id, user_id, status).
- **`art_lang`**: Localized titles and descriptions for artworks.
- **`categories`**: Core category data and icons.
- **`cat_lang`**: Localized names for categories.
- **`art_images`**: Stores paths to multiple images per artwork with positioning logic.
- **`languages`**: Supported locale definitions.
- **`users`**: Administrative accounts with hashed passwords and role statuses.
- **`hero_slides`**: Homepage slider image management.

### 🛠️ Core Technologies
- **PHP 8.2+**: Leveraging modern PHP features and type safety.
- **MySQL (MariaDB)**: Relational data storage.
- **PDO**: Prepared statements for all queries to prevent SQL injection.
- **Bootstrap 5**: Responsive UI framework.
- **Swiper.js**: Modern mobile-friendly touch sliders.
- **SortableJS**: JavaScript library for reorderable drag-and-drop lists.
- **PHPMailer**: Reliable email handling for order processing.
- **PHPUnit 11**: Rigorous testing for both unit and integration scenarios.

## 🚀 Installation & Deployment

### Prerequisites
- **XAMPP** (Recommended environment)
- Apache/Nginx with PHP 8.2+
- MySQL 10.4+
- Composer

### Step-by-Step Setup
1. **Clone & Setup:**
   - Clone the repository.
   - **IMPORTANT:** Rename the project folder to `Vihmart`.
2. **Install Dependencies:**
   ```bash
   composer install
   ```
3. **Database Configuration:**
   - Create a database named `vihmart`.
   - Import `vihmart.sql`.
4. **Environment Setup:**
   - Create a `.env` file (refer to `.env.example`):
     ```env
     MAIL_HOST=mail_host
     MAIL_USERNAME=yourmail@gmail.com
     MAIL_PASSWORD=your_password
     MAIL_PORT=587
     MAIL_FROM=yourmail@gmail.com
     MAIL_FROM_NAME=mail_name
     MAIL_TO=yourmail@gmail.com

     DB_HOST=localhost
     DB_NAME=database_name
     DB_USER=your_user
     DB_PASS=your_password
     ```
5. **Permissions:**
   - Ensure the `public/images/` directory is writable by the web server for uploads.
6. **Apache Configuration:**
   - Ensure `AllowOverride All` is set to allow `.htaccess` to handle routing.

### Accessing the Application
- **Main Application:** `http://localhost/Vihmart/`
- **Admin Panel:** `http://localhost/Vihmart/admin/`

## 🧪 Quality Assurance

Run the automated test suites for backend (PHPUnit) and end-to-end (Playwright).

### PHPUnit (unit & integration tests)

- Install PHP dependencies:
```bash
composer install
```

- Run all tests in the `tests` folder:
```bash
vendor/bin/phpunit tests
```

- Run a single test file:
```bash
vendor/bin/phpunit tests/AdminControllerTest.php
```

### Playwright (end-to-end tests)

- Install Node dependencies and Playwright browsers:
```bash
npm install
npx playwright install
```

- Run headless E2E tests (tests live in `tests-e2e`):
```bash
npm run test:e2e
```

- Run headed (visible) tests:
```bash
npm run test:e2e:headed
```

- Open the HTML report after a run:
```bash
npm run test:e2e:report
```

## 📝 License
This project is built for the Vihmart Art Gallery. All rights reserved.
