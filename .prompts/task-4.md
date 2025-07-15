# Prompt 0004 – Display Full Property Data in Agent Mode Template

## Goal

Enhance the Agent Mode template (`agent-template.php`) by displaying all relevant property details extracted from RealHomes meta fields.

## Context

The property post type (`property`) includes various meta fields such as price, size, bedrooms, bathrooms, garage, address, reference ID, featured image, gallery, and additional details. These fields must be extracted, formatted, and displayed in the Agent Mode layout following the design and coding guidelines already defined in the project.

## Tasks

### 1. Create a helper function

Add a helper function (e.g. in `includes/Helpers/PropertyData.php`) named `pbcr_agent_get_property_data()` that returns an associative array with the following keys and formatted values:

| Key          | Source Meta Key                         |
|--------------|------------------------------------------|
| price        | `REAL_HOMES_property_price`              |
| size         | `REAL_HOMES_property_size`               |
| size_unit    | `REAL_HOMES_property_size_postfix`       |
| bedrooms     | `REAL_HOMES_property_bedrooms`           |
| bathrooms    | `REAL_HOMES_property_bathrooms`          |
| garage       | `REAL_HOMES_property_garage`             |
| address      | `REAL_HOMES_property_address`            |
| ref_id       | `REAL_HOMES_property_id`                 |
| gallery_ids  | `REAL_HOMES_property_images`             |
| featured_id  | `_thumbnail_id`                          |
| description  | Use `get_the_content()` inside template  |
| extras_raw   | `REAL_HOMES_additional_details_list`     |

If the additional details list is serialized, use `maybe_unserialize()`.

### 2. Update `agent-template.php`

- Call the helper function and extract all values.
- Display the following in a clean, readable format:
  - Title (from `the_title()`)
  - Featured image (use `wp_get_attachment_image()`)
  - Price (formatted using `number_format()`)
  - Size (e.g. "3,550 Sq Ft")
  - Bedrooms, Bathrooms, Garage
  - Reference ID
  - Address
  - Description (use `the_content()` or `wp_kses_post( get_the_content() )`)
  - Image Gallery (loop over `gallery_ids` using `wp_get_attachment_image()`)
  - Additional Details: render as `<ul><li>Label: Value</li></ul>`

### 3. Apply clean HTML and CSS classes

Wrap all content inside `.agent-mode` scoped structure.

Use semantic tags (`<section>`, `<h2>`, `<ul>`, `<figure>`, etc.) and accessibility best practices.

### 4. Escape and sanitize all output

Use:
- `esc_html()` for plain text
- `esc_url()` for image URLs
- `wp_kses_post()` for the description
- `number_format()` for price and size

### 5. Do not include theme styles, functions, or layout wrappers

The layout must be standalone, responsive, and mobile-friendly.

## Output

- Modified: `includes/Views/agent-template.php`
- New: `includes/Helpers/PropertyData.php` (or equivalent)
- Updated: `css/agent-style.css` with layout support for the new sections
- New log file: `logs/YYYY-MM-DD_agent-mode-data.md`

## Constraints

- Must follow the CSS scoping and structure guidelines
- Do not use ACF or any theme dependency
- No inline styles or scripts
- All output must be properly escaped
- Layout must be mobile-first and visually clear

## Tests

- Test with a property that contains all fields
- Confirm gallery images render correctly
- Verify proper formatting of price and size
- Ensure additional details render as a readable list
- Confirm layout adapts on mobile
