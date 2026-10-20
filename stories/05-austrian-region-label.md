---
status: complete
---

# Austrian region label

As a customer from Austria, I want the region field to be labelled as a state, so that I recognize it as the Austrian federal state (Bundesland) of my address.

## Acceptance criteria
- When the billing address country is `AT`, the field `loki-checkout.billing.address.region` is labelled "State".
- When the shipping address country is `AT`, the field `loki-checkout.shipping.address.region` is labelled "State".
- Both labels are marked as translatable.
