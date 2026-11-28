import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {expect, test} from '@loki/test';
import atConfig, {THEMES, withTheme} from './config/config';
import {skipIfThemeIsMissing, switchCountry} from './helpers/austria';

for (const theme of THEMES) {
    test.describe(`postcode-mask (${theme})`, function () {
        test('should allow only 4 digits in the Austrian postcode', async function ({page, context}) {
            await skipIfThemeIsMissing(page, test, theme);
            await setupCheckout(page, context, withTheme(atConfig, theme));
            await switchCountry(page, 'shipping', 'AT');

            const postcodeField = new Field(page, 'shipping.postcode');
            await postcodeField.getLocator().clear();
            await postcodeField.getLocator().pressSequentially('1234AB56');
            await expect(postcodeField.getLocator()).toHaveValue('1234');
        });
    });
}
