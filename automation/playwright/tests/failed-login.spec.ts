import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://192.168.59.131:8000/');
  await page.getByRole('link', { name: 'Log in' }).click();

  // incorrect password
  await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
  await page.getByRole('textbox', { name: 'Password' }).fill('pssword');
  await page.getByRole('button', { name: 'Log in' }).click();
  await expect(page.getByRole('listitem')).toContainText('These credentials do not match our records.');

  // non existant user
  await page.getByRole('textbox', { name: 'Email' }).fill('ff@ff.com');
  await page.getByRole('textbox', { name: 'Password' }).fill('password');
  await page.getByRole('button', { name: 'Log in' }).click();
  await expect(page.getByRole('listitem')).toContainText('These credentials do not match our records.');

  // succesful login
  await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
  await page.getByRole('textbox', { name: 'Password' }).fill('password');
  await page.getByRole('button', { name: 'Log in' }).click();
  await expect(page.getByRole('navigation')).toContainText('Dude');
});