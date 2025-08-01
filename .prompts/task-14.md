Perfecto Alfredo. Aquí tenés el prompt claro, directo y sin ambigüedades para tu agente, listo para ser ejecutado en tu entorno de desarrollo:

⸻

🧠 AI Agent Prompt: Enhance PropertyData::get_property_data() Without Breaking Existing Logic

You are working on the PBCRAgentMode\Helpers\PropertyData class in the pbcr-agent-mode WordPress plugin.

This class currently exposes a method get_property_data( $property_id ) used to fetch RealHomes property data and inject it via register_rest_field into the standard WordPress REST API response for propiedad post types.

⸻

✅ Objective

Extend PropertyData::get_property_data() by adding missing commercial fields based on the latest client review. You must not remove or rename existing keys, only append new ones. The output is consumed by front-end components, so backward compatibility is critical.

⸻

📦 What to Add (New Keys in Return Array)

Key	Description
status	Extract post status taxonomy (Para Alquiler, Para Venta, etc). Return human-readable string.
type	Extract post type taxonomy (Casa, Apartamento, etc). Return label.
breadcrumbs	Return array of city/zone hierarchy from taxonomies (property-city, property-state). Ex: ["San José", "Escazú", "San Rafael"]
land_size	From REAL_HOMES_property_lot_size, numeric only.
land_unit	From REAL_HOMES_property_lot_size_postfix, default to "m²" if empty.
currency_prefix	From REAL_HOMES_currency_symbol or equivalent, e.g. $, ₡.
currency_suffix	From REAL_HOMES_currency_suffix, if any.
description	From post_content, stripped of shortcodes like [agent_view_link_button]
features	Extract from class_list all property-feature-* classes, return as array of strings with formatted labels (e.g. property-feature-secadora → Secadora)
gallery_urls	Return an array of image URLs from gallery_ids, using wp_get_attachment_image_url( id, 'full' ).
featured_url	Return the full-size featured image URL using _thumbnail_id.


⸻

🔍 Notes
	•	Keep using get_post_meta() and fallback checks as you’re already doing.
	•	Use maybe_unserialize() where applicable.
	•	Use wp_get_post_terms() to extract taxonomy terms (status, type, city).
	•	Use wp_get_attachment_image_url() for image URLs.
	•	For features, parse them from the class_list if available or from property-feature taxonomy if needed.
	•	For description, strip shortcodes with strip_shortcodes( get_post_field( 'post_content', $id ) ).

⸻

🧪 Testing
	•	Do not register a new REST endpoint. The current data is injected via register_rest_field under pbcr_agent_data.
	•	Validate with the following request:
GET https://pb-cr.com/wp-json/wp/v2/propiedad/{id}
	•	Confirm that new fields appear alongside the old ones, no breaking changes.

⸻

🚫 Do Not
	•	Do not change existing return keys in get_property_data()
	•	Do not rename, move, or unregister any shortcode or block code
	•	Do not overwrite the REST response, just extend the data array

⸻

I add the actual JSON response from a sample property to validate structure and values 20290.json.
