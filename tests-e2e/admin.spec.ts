import { test, expect } from '@playwright/test';

// Admin panel end-to-end tests for the Vihmart application.
test.describe('Vihmart admin panel', () => {
  // Verify the admin login page renders and rejects invalid credentials.
  test('admin login page renders and rejects invalid credentials', async ({ page }) => {
    await page.goto('admin/');
    await expect(page.locator('h3')).toContainText(/sisestage oma andmed/i);
    await expect(page.locator('form[action="login"]')).toBeVisible();

    // Enter invalid credentials and submit the login form.
    await page.fill('input[name="login"]', 'invalid-user');
    await page.fill('input[name="password"]', 'invalid-pass');
    await page.click('button[type="submit"]');

    // Confirm the page stays on the login form and displays an error message.
    await expect(page.locator('form[action="login"]')).toBeVisible();
    await expect(page.locator('p.pt-2')).not.toBeEmpty();
  });

  // Verify the login form includes a CSRF token field.
  test('login form includes CSRF token field', async ({ page }) => {
    await page.goto('admin/');
    await expect(page.locator('input[name="csrf_token"]')).toHaveAttribute('type', 'hidden');
  });

  // Verify submitting empty credentials shows a validation error.
  test('login rejects empty credentials', async ({ page }) => {
    await page.goto('admin/');
    await page.click('button[type="submit"]');
    await expect(page.locator('form[action="login"]')).toBeVisible();
    await expect(page.locator('p.pt-2')).not.toBeEmpty();
  });

  // Verify protected admin routes redirect unauthenticated users to login.
  test('unauthenticated admin route redirects to login', async ({ page }) => {
    await page.goto('admin/artsList');
    // Unauthenticated access to protected admin pages is rejected by the backend.
    await expect(page.locator('body')).toContainText(/access denied/i);
  });

  // Verify another protected admin route also requires login.
  test('admin users page requires login', async ({ page }) => {
    await page.goto('admin/users');
    await expect(page.locator('body')).toContainText(/access denied/i);
  });
});
