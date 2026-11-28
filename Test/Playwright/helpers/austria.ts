import {Field} from '@loki-checkout/checkout-objects';

export const labelText = (text: string): RegExp => {
    const escapedText = text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return new RegExp('^\\s*' + escapedText + '\\s*\\*?\\s*$');
};

export async function skipIfThemeIsMissing(page, test, theme: string) {
    if (theme.startsWith('Magento/')) {
        return;
    }

    const response = await page.request.post(
        '/loki_functional_tests/index/configure?token=' + encodeURIComponent(process.env.TEST_TOKEN || ''),
        {form: {config: JSON.stringify({theme})}}
    );
    const data = await response.json().catch(() => ({}));
    const errors: string[] = data.result?.theme?.errors ?? [];
    test.skip(errors.some(error => error.includes('not found')), 'Theme "' + theme + '" is not installed');
}

export async function switchCountry(page, addressType: string, country: string) {
    const countryField = new Field(page, addressType + '.country_id');
    await Promise.all([
        page.waitForResponse(response => response.status() === 200),
        countryField.fill(country),
    ]);
}
