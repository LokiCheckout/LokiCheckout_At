---
status: complete
---

# House number and addition side by side

As a customer from Austria using the default checkout skin, I want the house number and addition fields to be shown next to each other, so that the address form is compact and easy to fill in.

## Acceptance criteria
- With the default skin and billing address country `AT` (layout handle `loki_checkout_skin_default_billing_address_country_at`):
  - the field `loki-checkout.billing.address.street1` (house number) has a column span of 6 and a medium column span of 3;
  - the field `loki-checkout.billing.address.street2` (addition) has a column span of 6 and a medium column span of 3.
- With the default skin and shipping address country `AT` (layout handle `loki_checkout_skin_default_shipping_address_country_at`):
  - the field `loki-checkout.shipping.address.street1` (house number) has a column span of 6 and a medium column span of 3;
  - the field `loki-checkout.shipping.address.street2` (addition) has a column span of 6 and a medium column span of 3.
