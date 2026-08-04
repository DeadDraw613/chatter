import { test, expect } from '@playwright/test';
import { LoginPage } from '../../lib/pages/login-page-pom';

// login in to dude@dude.com
// ToDo: generate script to populate user data, and conversation data

test('Registered user can log in', async ({ page }) => {

  const loginPage = new LoginPage(page);

  const username = 'Dude';
  const email = 'dude@dude.com';
  const passwd = 'password';

  await page.goto('/login');
  await expect(loginPage.emailLocator).toBeVisible;

  await loginPage.emailLocator.fill(email);
  await loginPage.passwordLocator.fill(passwd);
  await loginPage.signInButtonLocator.click();
  expect(page.url()).toContain('dashboard');
  console.log(`✅ STATUS: User ${username} logged in successfully`);

  // User profile
  await page.getByRole('button', { name: username }).click();
  await page.getByRole('link', { name: 'Profile' }).click();

  // Logout
  await page.getByRole('button', { name: username }).click();
  await page.getByRole('link', { name: 'Log Out' }).click();
  console.warn(`✅ STATUS: User ${username} logged out`);
});