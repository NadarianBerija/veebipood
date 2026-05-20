import { test, expect } from '@playwright/test';

// Public-facing site end-to-end tests for the Vihmart application.
test.describe('Vihmart public site', () => {
  // Verify that the English homepage loads and the hero slider is visible.
  test('homepage loads and displays the hero slider', async ({ page }) => {
    await page.goto('en/');
    await expect(page).toHaveTitle(/VIHMART/i);
    await expect(page.locator('#hero-slider')).toBeVisible();
    await expect(page.locator('a[href$="/vihmart/en/shop"]').first()).toBeVisible();
    await expect(page.locator('.dropdown-toggle', { hasText: /ENGLISH/i })).toBeVisible();
  });

  // Verify the shop page loads and shows the category filter dropdown.
  test('shop page loads and exposes category filter', async ({ page }) => {
    await page.goto('en/shop');
    await expect(page.locator('h2')).toHaveText(/shop/i);
    await expect(page.locator('select[name="category_id"]')).toBeVisible();
    await expect(page.locator('.arts_list, p.text-center')).toHaveCount(1);
  });

  // Verify the cart page renders correctly when the cart is empty.
  test('empty cart page displays empty message by default', async ({ page }) => {
    await page.goto('en/cart');
    await expect(page.locator('h2')).toHaveText(/cart/i);
    await expect(page.locator('p.text-center')).toContainText(/empty/i);
  });

  // Verify the top navigation links go to the expected pages.
  test('top navigation links work', async ({ page }) => {
    await page.goto('en/');
    await page.locator('header nav .menu_links a', { hasText: /Shop/i }).click();
    await expect(page).toHaveURL(/\/en\/shop/);

    await page.goto('en/');
    await page.locator('header nav .menu_links a', { hasText: /Contact/i }).click();
    await expect(page).toHaveURL(/\/en\/contact/);
  });

  // Verify the first category block opens a gallery category page.
  test('category gallery opens the first category page', async ({ page }) => {
    await page.goto('en/');
    await page.locator('.cat_container a').first().click();
    await expect(page).toHaveURL(/\/en\/gallery\/category\?id=\d+/);
  });

  // Verify that an art details page shows the order button.
  test('art details page shows order button', async ({ page }) => {
    await page.goto('en/shop');
    await page.locator('a.shop_art').first().click();
    await expect(page.locator('h3')).toBeVisible();
    await expect(page.locator('a.btn.btn-dark').filter({ hasText: /order|go to cart/i })).toBeVisible();
  });

  // Verify the contact page loads and displays email links.
  test('contact page loads successfully', async ({ page }) => {
    await page.goto('en/contact');
    await expect(page.locator('h2')).toHaveText(/contact/i);
    await expect(page.locator('a[href^="mailto:"]')).toHaveCount(3);
    await expect(page.locator('a[href^="mailto:"]').first()).toBeVisible();
  });

  // Verify the language switch dropdown contains the alternate languages.
  test('language switch dropdown contains alternate language links', async ({ page }) => {
    await page.goto('en/');
    await expect(page.locator('.dropdown-toggle', { hasText: /ENGLISH/i })).toBeVisible();
    await page.locator('.dropdown-toggle').click();
    await expect(page.locator('.dropdown-menu a', { hasText: /EESTI/i })).toBeVisible();
    await expect(page.locator('.dropdown-menu a', { hasText: /РУССКИЙ/i })).toBeVisible();
  });
});
