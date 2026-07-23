import { Locator, Page } from "@playwright/test";

export class ChatPage {

    public readonly navChatLink;
    public readonly connectionUserDoug;
    public readonly messageInputBox;
    public readonly sendMessageButton;

    constructor(page: Page) {
        this.navChatLink = page.getByRole('link', { name: 'Chat' });
        this.connectionUserDoug = page.getByRole('link', { name: 'Doug doug@doug.com' });
        this.messageInputBox = page.getByRole('textbox', { name: 'Type your message...' });
        this.sendMessageButton = page.getByRole('button', { name: '📩' });    

        //   await .fill('Playwright sent this message');
        //   await page.getByRole('button', { name: '📩' }).click();
        //   await expect(page.getByText('Playwright sent this message')).toBeVisible();
    }
}
