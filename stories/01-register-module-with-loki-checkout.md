---
status: complete
---

# Register the Austrian settings module with Loki Checkout

As a shop owner, I want the Austrian add-on to be registered as a Loki Checkout module, so that its country-specific settings are picked up by the Loki Checkout.

## Acceptance criteria
- The module `LokiCheckout_At` is added to the `modules` argument of `LokiCheckout\Core\Config\LokiCheckoutModules` via `etc/di.xml`.
- The module is loaded after `LokiCheckout_Core` (sequence in `etc/module.xml`).
- The module can be installed via composer as `loki-checkout/magento2-at` and requires `loki-checkout/magento2-core` `^2.0`.
- The composer package is tagged with the keyword `loki-checkout-category-country` and labelled "Austrian Settings".
