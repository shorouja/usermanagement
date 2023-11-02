import { test, expect } from '@playwright/test';
// tbc
test('testLoadPage', async ({ page }) => {
  await page.goto('http://localhost/usermanagement/');
  await page.getByRole('button', { name: 'Get all' }).click();
  await page.locator('#user_9').getByRole('button', { name: 'bearbeiten' }).click();
  await page.locator('#user_9').getByRole('button', { name: 'bearbeiten' }).click();
  await page.locator('#user_9').getByRole('button', { name: 'löschen' }).click();
  await page.getByRole('button', { name: 'Neuer Benutzer' }).click();
  await page.getByPlaceholder('Vorname').click();
  await page.getByPlaceholder('Vorname').fill('Tester');
  await page.getByPlaceholder('Vorname').press('Tab');
  await page.getByPlaceholder('Nachname').fill('123');
  await page.getByPlaceholder('Nachname').press('Tab');
  await page.getByPlaceholder('Email').fill('a');
  await page.getByPlaceholder('Email').press('Tab');
  await page.getByPlaceholder('Passwort').fill('123');
  await page.getByRole('button', { name: 'Abschicken' }).click();
  await page.getByPlaceholder('Email').click();
  await page.getByPlaceholder('Email').fill('a@b.d');
  await page.getByRole('button', { name: 'Abschicken' }).click();
});