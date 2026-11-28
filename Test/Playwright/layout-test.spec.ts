import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {expect, test} from '@loki/test';
import atConfig, {LAYOUT_THEMES, withTheme} from './config/config';
import {skipIfThemeIsMissing, switchCountry} from './helpers/austria';

for (const theme of LAYOUT_THEMES) {
    test.describe(`layout (${theme})`, function () {
        test.use({viewport: {width: 1280, height: 900}});

        test('should show house number and addition side by side with the default skin', async function ({page, context}) {
            await skipIfThemeIsMissing(page, test, theme);
            await setupCheckout(page, context, withTheme(atConfig, theme, {
                config: {
                    'loki_checkout/general/theme': 'default',
                },
            }));
            await switchCountry(page, 'shipping', 'AT');

            const street0 = new Field(page, 'shipping.street0').getLocator();
            const street1 = new Field(page, 'shipping.street1').getLocator();
            const street2 = new Field(page, 'shipping.street2').getLocator();
            const country = new Field(page, 'shipping.country_id').getLocator();
            const postcode = new Field(page, 'shipping.postcode').getLocator();

            await expect(async () => {
                const street1Box = await street1.boundingBox();
                const street2Box = await street2.boundingBox();
                expect(street1Box).not.toBeNull();
                expect(street2Box).not.toBeNull();
                expect(Math.abs(street1Box!.y - street2Box!.y)).toBeLessThan(2);
                expect(street2Box!.x).toBeGreaterThan(street1Box!.x + street1Box!.width - 1);
                expect(Math.abs(street1Box!.width - street2Box!.width)).toBeLessThan(2);

                const street0Box = await street0.boundingBox();
                const countryBox = await country.boundingBox();
                const postcodeBox = await postcode.boundingBox();
                expect(countryBox!.width).toBeGreaterThan(postcodeBox!.width * 1.5);
                expect(street1Box!.width).toBeLessThan(street0Box!.width);
            }).toPass();
        });
    });
}
