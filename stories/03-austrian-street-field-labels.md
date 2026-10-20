---
status: complete
---

# Austrian street field labels

As a customer from Austria, I want the street lines of my address to be labelled as street name, house number and addition, so that I know exactly what to fill in on each line.

## Acceptance criteria
- When the billing address country is `AT` (layout handle `loki_checkout_billing_address_country_at`):
  - the field `loki-checkout.billing.address.street0` is labelled "Street Name";
  - the field `loki-checkout.billing.address.street1` is labelled "Housenumber";
  - the field `loki-checkout.billing.address.street2` is labelled "Addition".
- When the shipping address country is `AT` (layout handle `loki_checkout_shipping_address_country_at`):
  - the field `loki-checkout.shipping.address.street0` is labelled "Street Name";
  - the field `loki-checkout.shipping.address.street1` is labelled "Housenumber";
  - the field `loki-checkout.shipping.address.street2` is labelled "Addition".
- All of these labels are marked as translatable.
