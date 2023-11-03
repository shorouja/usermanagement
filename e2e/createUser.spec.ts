import { test, expect } from '@playwright/test';

test('createUser', async ({ page }) => {
  await page.goto('http://localhost/usermanagement/');
  await page.getByRole('button', { name: 'Neuer Benutzer' }).click();
  await page.getByPlaceholder('Vorname').click();
  await page.getByPlaceholder('Vorname').fill('Tester');
  await page.getByPlaceholder('Vorname').press('Tab');
  await page.getByPlaceholder('Nachname').fill('Schwabe');
  await page.getByPlaceholder('Nachname').press('Tab');
  await page.getByPlaceholder('Email').fill('schwabe.daniel@yahoo.de');
  await page.getByPlaceholder('Email').press('Tab');
  await page.getByPlaceholder('Passwort').fill('Test123!');
  await page.getByRole('button', { name: 'Abschicken' }).click();
  await expect(page.locator('#answerMessage')).toHaveText('Erfolgreich angelegt.');
});

test('createUserExistingEmail', async ({ page }) => {
  await page.goto('http://localhost/usermanagement/');
  await page.getByRole('button', { name: 'Neuer Benutzer' }).click();
  await page.getByPlaceholder('Vorname').click();
  await page.getByPlaceholder('Vorname').fill('Tester');
  await page.getByPlaceholder('Vorname').press('Tab');
  await page.getByPlaceholder('Nachname').fill('Schwabe');
  await page.getByPlaceholder('Nachname').press('Tab');
  await page.getByPlaceholder('Email').fill('schwabe.daniel@yahoo.de');
  await page.getByPlaceholder('Email').press('Tab');
  await page.getByPlaceholder('Passwort').fill('Test123!');
  await page.getByRole('button', { name: 'Abschicken' }).click();
  await expect(page.locator('#answerMessage')).toHaveText('Zu dieser E-Mail existiert bereits ein Account.');
});