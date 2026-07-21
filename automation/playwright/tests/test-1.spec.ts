import { test, expect } from '@playwright/test';
import { LoginPage } from '../lib/pages/login-page-pom';

test("Login using POM", async ({ page }) => {
  const loginPage = new LoginPage(page);
  await page.goto('http://192.168.70.89/login');
  await loginPage.emailLocator.fill("dude@dude.com");
  await loginPage.passwordLocator.fill("password");
  await loginPage.signInButtonLocator.click();
  expect(page.url()).toContain('dashboard');
})

test('test', async ({ page }) => {
  // await page.goto('http://192.168.70.89/');
  // await page.getByRole('link', { name: 'Log in' }).click();
  // await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
  // await page.getByRole('textbox', { name: 'Password' }).fill('password');
  // await page.getByRole('button', { name: 'Log in' }).click();

  await page.getByRole('link', { name: 'Chat' }).click();
  await page.getByRole('link', { name: 'Doug doug@doug.com' }).click();
  await page.getByRole('textbox', { name: 'Type your message...' }).fill('Playwright sent this message');
  await page.getByRole('button', { name: '📩' }).click();
  await expect(page.getByText('Playwright sent this message')).toBeVisible();

  await page.getByRole('button', { name: 'Dude' }).click();
  await page.getByRole('link', { name: 'Log Out' }).click();

  // await page.getByRole('link', { name: 'Log in' }).click();
  // await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
  // await page.getByRole('textbox', { name: 'Password' }).fill('password');
  // await page.getByRole('button', { name: 'Log in' }).click();

  // await page.getByRole('link', { name: 'Chat' }).click();

  // await page.getByRole('link', { name: 'Doug doug@doug.com' }).click();
  // await expect(page.getByText('Playwright sent this message')).toBeVisible();
});