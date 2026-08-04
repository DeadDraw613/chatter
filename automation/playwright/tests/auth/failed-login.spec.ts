import { test, expect } from '@playwright/test';
import { LoginPage } from '../../lib/pages/login-page-pom';

test('Login with invalid password', async ({ page }) => {

  const loginPage = new LoginPage(page);
  const username = 'Dude';
  const no_such_user = 'not@valid.com';
  const email = 'dude@dude.com';
  const passwd = 'notgood';

  await page.goto('/login');
  await expect(loginPage.emailLocator).toBeVisible;

  // incorrect password
  await test.step("Step 1. Incorrect password", async () => {
    await loginPage.emailLocator.fill(email);
    await loginPage.passwordLocator.fill(passwd);
    await loginPage.signInButtonLocator.click();
    await expect(page.getByRole('listitem')).toContainText('These credentials do not match our records.');
  });
  console.log(`✅ STATUS: User ${username} used the incorrect password ${passwd}`);

  // incorrect username, nonexistant account
  await test.step("Step 2. Invalid username", async () => {
    await loginPage.emailLocator.fill(no_such_user);
    await loginPage.passwordLocator.fill(passwd);
    await loginPage.signInButtonLocator.click();
    await expect(page.getByRole('listitem')).toContainText('These credentials do not match our records.');
  });
  console.log(`✅ STATUS: User ${no_such_user} is not a valid username`);
});
