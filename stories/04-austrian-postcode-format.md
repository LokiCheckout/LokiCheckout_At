---
status: complete
---

# Austrian postcode format

As a customer from Austria, I want the postcode field to guide me towards a 4-digit Austrian postcode, so that I enter my postcode in the correct format.

## Acceptance criteria
- When the billing address country is `AT`, the field `loki-checkout.billing.address.postcode`:
  - is labelled "Postcode" (translatable);
  - shows the placeholder `1234`;
  - has the input mask attribute `x-mask="9999"`, restricting input to 4 digits.
- When the shipping address country is `AT`, the field `loki-checkout.shipping.address.postcode`:
  - is labelled "Postal code" (translatable);
  - shows the placeholder `1234`;
  - has the input mask attribute `x-mask="9999"`, restricting input to 4 digits.
