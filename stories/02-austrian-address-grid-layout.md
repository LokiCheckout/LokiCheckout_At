---
status: complete
---

# Austrian address grid layout

As a customer from Austria, I want the address form to use a layout tailored to Austria, so that the address fields are arranged in a way that fits Austrian addresses.

## Acceptance criteria
- `etc/loki_checkout.xml` defines a grid named `austria` within the grid layout `default_grid`.
- The `austria` grid applies to the country ID `AT`.
- The `austria` grid inherits from the `default` grid.
- Within the `austria` grid, the `country_id` field spans 12 columns on medium screens and up (`mdColSpan="12"`).
- The Tailwind configuration of the module scans `etc/loki_checkout.xml` and the layout XML files, so that the CSS classes used by the grid are generated.
