# Prompt 0001 – Implement Agent Mode Template Override

## Goal

Create the custom Agent Mode view for properties by intercepting requests with the `agent_view` query parameter and rendering a minimal, unbranded template.

## Context

The post type for properties is `property`. When visiting a property page with the query `?agent_view=1`, the plugin must render a stripped-down layout that excludes all theme branding (no headers, footers, menus, or RealHomes styles).

## Tasks

1. Hook into `template_include` from the main `Plugin` class.
2. If `is_singular('property')` and `$_GET['agent_view'] === '1'`:
   - Load the template located at `includes/Views/agent-template.php`
   - Prevent the default theme template from loading
3. Inside `agent-template.php`, output:
   - Property title (`the_title()`)
   - Featured image
   - Price and description via `get_post_meta()` (use existing fields only)
   - Basic contact button (can be static for now)
4. Do **not** use `get_header()` or `get_footer()`. This view must be standalone and independent from the theme.
5. Add a scoped wrapper class `.agent-mode` to the HTML layout.
6. Enqueue `css/agent-style.css` only when this view is active using `wp_enqueue_style`.
7. Ensure all output is escaped properly (`esc_html()`, `esc_url()`, etc.)

## Tests

- Create a test property post (`post_type=property`) in development.
- Visit the URL: `/property/example-property-slug/?agent_view=1`
- Verify that:
  - The Agent Mode template is rendered.
  - No theme markup or branding is present.
  - Property title and image appear correctly.
  - The view is mobile-friendly and responsive.
- Also test that the property renders **normally** when `agent_view` is **not present**.

## Output

- Updated: `includes/Plugin.php`
- New file: `includes/Views/agent-template.php`
- New file: `css/agent-style.css` (scoped with `.agent-mode`)
- Log file: `logs/YYYY-MM-DD_template-override.md`

## Constraints

- Do not use any theme functions from RealHomes.
- Do not include any inline CSS or JS.
- All logic must be self-contained and follow the plugin architecture.
- All files must follow WordPress coding standards.