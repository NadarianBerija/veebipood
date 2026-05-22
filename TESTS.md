# Vihmart Project Testing

This document describes the automated testing system for the **Vihmart** web application — an online art gallery and shop. It outlines the test structure, how to run tests, their coverage areas, and environment setup instructions.

Vihmart is a full-stack PHP (MVC) application that includes a public section (gallery, shop, cart, orders) and an administrative panel (managing arts, users, slider). Testing is divided into two levels:

- **Unit & Integration tests (PHPUnit)** — verification of backend logic, models, controllers, routing, and helper classes.
- **End-to-End tests (Playwright)** — browser scenarios that check the application's operation from a user's perspective (navigation, cart functionality, admin panel).

## Tools Used

| Tool | Purpose | Configuration |
|---|---|---|
| PHPUnit 11 | Unit and integration tests for backend logic | `phpunit.xml` |
| Playwright | End-to-End browser tests (Chromium) | `playwright.config.ts` |

Tests are located in the following folders:

```text
Vihmart/
├── tests/              # PHPUnit tests (backend)
└── tests-e2e/          # Playwright tests (end-to-end)
```

## Running Tests

The project uses standard tools for running tests.

| Level | Command |
|---|---|
| **Backend (PHPUnit)** | `vendor/bin/phpunit tests` |
| **Backend (Single File)** | `vendor/bin/phpunit tests/ArtsTest.php` |
| **E2E (Playwright)** | `npm run test:e2e` |
| **E2E (Headed mode)** | `npm run test:e2e:headed` |
| **E2E (Report)** | `npm run test:e2e:report` |

## Environment Setup

### 1. Dependencies
Before running tests, you need to install dependencies:

```bash
composer install
npm install
```

For Playwright, you also need to install browsers:

```bash
npx playwright install
```

### 2. Database
Tests (especially integration and E2E) require a database. Before running, you need to:
1. Create a database named `vihmart`.
2. Import the `vihmart.sql` SQL dump.
3. Configure the `.env` file in the project root (see `README.md`).

Example `.env` for tests:
```env
DB_HOST=localhost
DB_NAME=vihmart
DB_USER=root
DB_PASS=
```

## PHPUnit Tests (Backend)

Backend tests cover core models, controllers, and system components. The project uses a combination of unit tests and integration tests with database interaction.

### Main Coverage Areas

| Test File | What is tested |
|---|---|
| `ArtsTest.php` | Logic for retrieving art lists, category filtering, pagination. |
| `CategoryTest.php` | Retrieving categories and their localized names. |
| `HeroSliderTest.php` | Logic for homepage slider operation. |
| `OrderTest.php` | Order processing, saving to DB, order data validation. |
| `UsersTest.php` | User management, creation, role verification (admin/moderator). |
| `LoginTest.php` | Authentication, password hashing, sessions. |
| `LangTest.php` | Localization system operation (EE, EN, RU). |
| `AdminControllerTest.php` | Logic for administrative actions (CRUD arts, users). |
| `RouteIntegrationTest.php` | Verification of correct routing and HTTP responses for pages. |

### PHPUnit Specifics in the Project
- **Bootstrap:** The `tests/bootstrap.php` file initializes the environment and autoloader before tests.
- **BaseTestCase:** A common base class for tests, providing helper methods.
- **Stubs:** The `tests/TestStubs.php` file contains stubs for mocking objects (e.g., sessions or mail client).

## End-to-End Tests (Playwright)

E2E tests verify the application as a whole by launching a real browser and simulating user actions.

### Playwright Configuration
The `playwright.config.ts` file is configured to use `baseURL: 'http://localhost/Vihmart/'`. If your local URL differs, you should update it in the config.

### Covered Scenarios

1. **Public Section (`home.spec.ts`)**:
   - Homepage loading.
   - Language switching and content change verification.
   - Navigation through categories in the gallery.
   - Going to the shop and viewing product details.
   - Cart functionality: adding and removing items.

2. **Admin Panel (`admin.spec.ts`)**:
   - Logging into the administration panel.
   - Verification of admin panel protection from unauthorized users.
   - Navigation through sections (Arts, Users, Slides).
   - Verification of add and edit forms.

## Recommended Run Order

For a full project check before publishing or committing:

1. **Preparation:**
   ```bash
   composer install
   npm install
   # Ensure the vihmart DB is imported and .env is configured
   ```

2. **Backend Tests:**
   ```bash
   vendor/bin/phpunit tests
   ```

3. **E2E Tests:**
   ```bash
   npm run test:e2e
   ```

## Reports and Diagnostics

- **PHPUnit:** Results are output to the console. Test cache is stored in `.phpunit.cache`.
- **Playwright:** 
  - After a test run, the `playwright-report` folder is created.
  - The report can be opened with the command `npm run test:e2e:report`.
  - In case of test failures, screenshots and traces are saved in the `test-results` folder.

## Conclusion

The Vihmart testing system ensures:
- Business logic stability through **PHPUnit**.
- Functionality of critical user paths through **Playwright**.
- Security of the administration panel and correctness of localization.

Regularly running tests helps prevent regressions when adding new features or refactoring code.
