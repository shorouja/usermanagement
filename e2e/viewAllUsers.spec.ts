import { test, expect } from '@playwright/test';
// tbc
test('viewAllUsers', async ({ page }) => {
  await page.goto('http://localhost/usermanagement/');
  await page.getByRole('button', { name: 'Aktualisieren' }).click();
  await expect(page.locator('#answerMessage')).toHaveText('Erfolgreich aktualisiert.');
});