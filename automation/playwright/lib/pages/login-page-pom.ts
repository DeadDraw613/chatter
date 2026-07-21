import { Locator, Page } from "@playwright/test";

export class LoginPage {

    public readonly emailLocator;
    public readonly passwordLocator;
    public readonly signInButtonLocator;
    // rememberMeCheckboxLocator
    // forgotPasswordLinkLocator

    constructor(page: Page) {
        this.emailLocator = page.getByRole('textbox', { name: 'Email' })
        this.passwordLocator = page.getByRole('textbox', { name: 'Password' })
        this.signInButtonLocator =  page.getByRole('button', { name: 'Log in' })
    }
}


//   await page.goto('http://192.168.70.89/');
//   await page.getByRole('link', { name: 'Log in' }).click();
//   await page.getByRole('textbox', { name: 'Email' }).fill('dude@xxxx@xxxxx.com');
//   await page.getByRole('textbox', { name: 'Password' }).fill('xxxxxxx');
//   await page.getByRole('button', { name: 'Log in' }).click();