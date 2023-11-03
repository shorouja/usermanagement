import { test, expect } from '@playwright/test';
// assuming there is at least one user
test('deleteUserifExists', async ({ page }) => {
  await page.goto('http://localhost/usermanagement/');
  await page.getByRole('button', { name: 'Aktualisieren' }).click();
  await page.getByRole('button', { name: 'löschen' }).nth(0).click();
  await expect(page.locator('#answerMessage')).toHaveText('Erfolgreich gelöscht.');
});