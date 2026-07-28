import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://192.168.59.131:8000/');
  await expect(page.getByRole('navigation')).toContainText('Register');

// Register new user account
  await page.getByRole('link', { name: 'Register' }).click();
  await page.getByRole('textbox', { name: 'Name' }).fill('testy');
  await page.getByRole('textbox', { name: 'Email' }).fill('testy@testy.com');
  await page.getByRole('textbox', { name: 'Password', exact: true }).fill('password');
  await page.getByRole('textbox', { name: 'Confirm Password' }).fill('password');
  await expect(page.getByRole('button')).toContainText('Register');
  await page.getByRole('button', { name: 'Register' }).click();

// User profile
  await page.getByRole('button', { name: 'testy' }).click();
  await page.getByRole('link', { name: 'Profile' }).click();

  // Add profile photo
  await page.locator('[data-test-id="profile-photo-button"]').click();
  await page.locator('#profile-picture-input').setInputFiles('il_794xN.8061448190_c4m2.jpg');
  await page.locator('[data-test-id="profile-photo-button"]').click();
  await expect(page.locator('#profile-picture-status')).toContainText('Profile image saved successfully!');

// Add connection
  await page.getByRole('textbox', { name: 'Add Connection by Email' }).fill('bob@bob.com');

//   Popup alert confirmation
  page.once('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: 'Send Request' }).click();

  await expect(page.getByRole('listitem')).toContainText('bob');
  await expect(page.getByRole('listitem')).toContainText('Status: requested');
  await expect(page.getByRole('listitem')).toContainText('Awaiting confirmation');
  
//   verify requested user in chat window
  await page.getByRole('link', { name: 'Chat' }).click();
  await expect(page.getByRole('link', { name: 'bob bob@bob.com Waiting for' })).toBeVisible();
  await expect(page.getByRole('paragraph')).toContainText('Waiting for confirmation...');

// Logout
  await page.getByRole('button', { name: 'testy' }).click();
  await page.getByRole('link', { name: 'Log Out' }).click();
});