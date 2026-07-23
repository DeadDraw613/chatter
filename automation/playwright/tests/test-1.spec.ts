import { test, expect } from '@playwright/test';
import { LoginPage } from '../lib/pages/login-page-pom';
import { ChatPage } from '../lib/pages/chat-page-pom';

test('End-to-end send message worflow', async ({ page }) => {

  const loginPage = new LoginPage(page);
  const chatPage = new ChatPage(page);

  await test.step("Step 1. Login using POM", async () => {
    await page.goto('http://192.168.70.89/login');
    await loginPage.emailLocator.fill("dude@dude.com");
    await loginPage.passwordLocator.fill("password");
    await loginPage.signInButtonLocator.click();
    expect(page.url()).toContain('dashboard');
  });
  console.log('✅ Step 1 completed successfully');

  await test.step("Step 2. Go to Chat Page", async () => {
    await chatPage.navChatLink.click();
    expect(page.url()).toContain('chat');
  });
  console.log('✅ Step 2 completed successfully');

  await test.step("Step 3. Select Dougs chat history", async () => {
    await chatPage.connectionUserDoug.click();
    await expect(page.locator('section')).toContainText('Chat with Doug');
    //verify existing message - this might fail if not scrolled to TBFT
    await expect(page.locator('#messages')).toContainText('Jackie Treehorn treats objects like women man.');
  });
  console.log('✅ Step 3 completed successfully');

  await test.step("Step 4. Send message to Doug", async () => {
    await chatPage.messageInputBox.fill('Playwright generated message');
    await chatPage.sendMessageButton.click();
    //verify message is in the sent column (background color)
    await expect(page.locator('#messages')).toContainText('Playwright generated message');
  });
  console.log('✅ Step 4 completed successfully');

  await test.step("Step 5. Log out", async () => {
    await page.getByRole('button', { name: 'Dude' }).click();
    await page.getByRole('link', { name: 'Log Out' }).click();
  });
  console.log('✅ Step 5 completed successfully');

})











// test('test', async ({ page }) => {
  // await page.goto('http://192.168.70.89/');
  // await page.getByRole('link', { name: 'Log in' }).click();
  // await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
  // await page.getByRole('textbox', { name: 'Password' }).fill('password');
  // await page.getByRole('button', { name: 'Log in' }).click();

  // await page.getByRole('link', { name: 'Chat' }).click();
  // await page.getByRole('link', { name: 'Doug doug@doug.com' }).click();
  // await page.getByRole('textbox', { name: 'Type your message...' }).fill('Playwright sent this message');
  // await page.getByRole('button', { name: '📩' }).click();
  // await expect(page.getByText('Playwright sent this message')).toBeVisible();

  // await page.getByRole('button', { name: 'Dude' }).click();
  // await page.getByRole('link', { name: 'Log Out' }).click();

  // await page.getByRole('link', { name: 'Log in' }).click();
  // await page.getByRole('textbox', { name: 'Email' }).fill('dude@dude.com');
  // await page.getByRole('textbox', { name: 'Password' }).fill('password');
  // await page.getByRole('button', { name: 'Log in' }).click();

  // await page.getByRole('link', { name: 'Chat' }).click();

  // await page.getByRole('link', { name: 'Doug doug@doug.com' }).click();
  // await expect(page.getByText('Playwright sent this message')).toBeVisible();

  
  // await expect(page.locator('#messages')).toContainText('Jackie Treehorn treats objects like women man.');
  // await page.getByRole('textbox', { name: 'Type your message...' }).dblclick();
  // await page.getByRole('textbox', { name: 'Type your message...' }).fill('Playwright generated message');
  // await page.getByRole('button', { name: '📩' }).click();

// });