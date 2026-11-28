import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

export const THEMES = ['Magento/luma', 'Hyva/default-csp'];

// Hyva/default-csp ships prebuilt CSS without the Loki grid classes, so layout tests need a theme built with Loki modules
export const LAYOUT_THEMES = ['Magento/luma', 'Loki/hyva-dev'];

export const withTheme = (config: any, theme: string, extra: any = {}) => merge(config, {...extra, theme});

export default merge(coreConfig, {
    modules: [
        'LokiCheckout_At',
    ],
    config: {
        'loki_checkout/fields/hide_region_when_optional': 0,
    },
});
