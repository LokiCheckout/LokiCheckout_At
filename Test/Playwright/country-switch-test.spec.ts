import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {expect, test} from '@loki/test';
import atConfig, {THEMES, withTheme} from './config/config';
import {labelText, skipIfThemeIsMissing, switchCountry} from './helpers/austria';

for (const theme of THEMES) {
    test.describe(`country-switch (${theme})`, function () {
        test.beforeEach(async function ({page}) {
            await skipIfThemeIsMissing(page, test, theme);
        });

        test('should show Austrian labels and placeholders only for Austria', async function ({page, context}) {
            await setupCheckout(page, context, withTheme(atConfig, theme));

            const street0Field = new Field(page, 'shipping.street0');
            const street1Field = new Field(page, 'shipping.street1');
            const street2Field = new Field(page, 'shipping.street2');
            const postcodeField = new Field(page, 'shipping.postcode');
            const regionField = new Field(page, 'shipping.region');

            for (let round = 0; round < 2; round++) {
                await switchCountry(page, 'shipping', 'AT');
                await expect(street0Field.getLabelLocator()).toHaveText(labelText('Street Name'));
                await expect(street1Field.getLabelLocator()).toHaveText(labelText('Housenumber'));
                await expect(street2Field.getLabelLocator()).toHaveText(labelText('Addition'));
                await expect(postcodeField.getLabelLocator()).toHaveText(labelText('Postcode'));
                await expect(regionField.getLabelLocator()).toHaveText(labelText('State'));
                await expect(postcodeField.getLocator()).toHaveAttribute('placeholder', '1234');

                await switchCountry(page, 'shipping', 'US');
                await expect(street1Field.getLabelLocator().filter({hasText: labelText('Housenumber')})).toHaveCount(0);
                await expect(street2Field.getLabelLocator().filter({hasText: labelText('Addition')})).toHaveCount(0);
                await expect(postcodeField.getLabelLocator()).toHaveText(labelText('Zip/Postal Code'));
                await expect(regionField.getLabelLocator()).toHaveText(labelText('State/Province'));
                await expect(postcodeField.getLocator()).not.toHaveAttribute('placeholder', /.+/);
            }
        });
    });
}
