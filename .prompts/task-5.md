You're working on improving the `agent-template.php` template file in the "PBCR Agent Mode" WordPress plugin.

**Goal**: Ensure all key property meta fields are rendered properly based on the output of `get_post_meta( get_the_ID() )`.

**Step 1: Audit Existing Implementation**
- First, review the current code in `agent-template.php`.
- Identify which meta fields are already being rendered correctly.
- Do **not** duplicate fields that are already present.

**Step 2: Add Missing Fields**
Implement support for the following fields if they are **not yet present** in the template:

1. **Basic Fields**:
   - `REAL_HOMES_property_price`
   - `REAL_HOMES_property_id`
   - `REAL_HOMES_property_bedrooms`
   - `REAL_HOMES_property_bathrooms`
   - `REAL_HOMES_property_garage`
   - `REAL_HOMES_property_size` + `REAL_HOMES_property_size_postfix`
   - `REAL_HOMES_property_address`
   - `REAL_HOMES_property_location`
   - `REAL_HOMES_featured`
   - `REAL_HOMES_add_in_slider`
   - `REAL_HOMES_property_map`

2. **Gallery Images**:
   - Field: `REAL_HOMES_property_images` (array of attachment IDs)
   - Render with `wp_get_attachment_image()`

3. **Additional Details**:
   - Field: `REAL_HOMES_additional_details_list`
   - Deserialize and render as a definition list (`<dl><dt><dd>`)

4. **Property Video**:
   - Field: `inspiry_video_group` (serialized)
   - Extract video URL and image ID
   - Display thumbnail and embed video if available

5. **Assigned Agents**:
   - Field: `REAL_HOMES_agents` (array of user IDs)
   - Load user with `get_userdata()`
   - Render name and avatar (`get_avatar()`)

**Requirements**:
- Use semantic HTML structure
- Add fallback for missing data (e.g. "Not available")
- Sanitize and escape all output with `esc_html()`, `esc_url()`, or `wp_kses_post()`

Only update the template with fields that are currently missing, and maintain WordPress best practices throughout.
