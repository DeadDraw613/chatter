import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.locator('body').click();
  await page.goto("/");
  await expect(page).toHaveTitle(
    "Chatter",
  );

  await page.getByRole('link', { name: 'Log in' }).click();
//   await page.getByRole('textbox', { name: 'Email' }).click();
  await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
//   await page.getByRole('textbox', { name: 'Email' }).press('Tab');
  await page.getByRole('textbox', { name: 'Password' }).fill('password');
  await page.getByRole('button', { name: 'Log in' }).click();
  await expect(page).toHaveTitle(
    "dude",
  ); 
  
  await page.getByRole('link', { name: 'Chat' }).click();
//   await page.getByRole('link', { name: 'doug doug@doug.com' }).click();
  await page.getByRole('link', { name: 'Dave dave@dave.com' }).click();
  await page.getByRole('img').filter({ hasText: /^$/ }).nth(5).click();
  await page.locator('#lightbox-img').click();
  await expect(page.getByText('What are those squirrels')).toBeVisible()
  

  await page.getByRole('button', { name: 'dude' }).click();
  await page.getByRole('link', { name: 'Profile' }).click();


  await expect(page.locator('[data-test-id="profile-photo-button"]')).toBeVisible();;
//   await page.locator('#profile-picture-input').setInputFiles('Crazy-banana-funny-pictures-desktop.jpg');
//   await page.locator('[data-test-id="profile-photo-button"]').click();
  await page.getByRole('link', { name: 'Chat' }).click();
  await page.getByRole('link', { name: 'Dave dave@dave.com' }).click();
  await page.getByRole('button', { name: 'dude' }).click();
  await page.getByRole('link', { name: 'Log Out' }).click();
});