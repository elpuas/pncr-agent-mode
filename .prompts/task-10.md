Implement Agent View Copy Link Button Shortcode with Role Restriction and Clipboard Feature

### Goal:
Create a WordPress shortcode `[agent_view_link_button]` that displays a button for copying the Agent Mode URL, but **only visible to logged-in users with a specific role (or capability)**, such as `administrator` or `agent`.

---

### Requirements:

#### 1. Shortcode Implementation
- Shortcode tag: `[agent_view_link_button]`.
- Register this shortcode in `AgentViewLinkButton` class.
- Conditions:
  - Must only render if `is_user_logged_in()` is true.
  - Additionally, check `current_user_can('edit_posts')` or a specific capability (add a placeholder to easily change later).
  - Must only render in `property` post type context.
- Attributes:
  - `label` (default: "Copy Agent Link", translatable).
- Output:
  ```html
  <button class="agent-copy-btn" data-url="[agent-mode-url]">Copy Agent Link</button>

2. URL Generation
	•	Build the Agent Mode URL using:

$agent_url = add_query_arg('agent_view', '1', get_permalink($post->ID));


	•	Prepare placeholder for future YOURLS integration (commented section).

3. Role/Capability Check
	•	Add logic to verify user role or capability before rendering the button.
	•	If user does not meet the role/capability requirement, return empty string.

4. JavaScript for Clipboard Copy
	•	Add JS script:
	•	Listens for clicks on .agent-copy-btn.
	•	Copies the URL from data-url to clipboard using navigator.clipboard.writeText().
	•	Displays temporary feedback:
	•	Change button text to “Copied!” (translatable) for 2 seconds, then revert to original label.
	•	Use wp.i18n.__() for translations.

5. CSS Styling
	•	Add minimal styles under .agent-mode .agent-copy-btn:
	•	Primary button style (padding, background, border radius).
	•	Hover effect.
	•	Responsive container query adjustments.

6. Asset Loading
	•	Enqueue JS and CSS only when:
	•	Viewing a single property post.
	•	The shortcode [agent_view_link_button] is present in the content (use has_shortcode()).
	•	Use wp_enqueue_script() with wp-i18n as a dependency for translation support.

7. Gutenberg Block (Optional)
	•	Add a simple block inserter for Gutenberg that inserts [agent_view_link_button]:
	•	Title: “Agent Copy Link Button”
	•	Category: Widgets or Custom
	•	Icon: Link or Clipboard
	•	Description: “Adds a button to copy the Agent Mode link for the current property.”

8. Logging
	•	Create a log file in /logs/:
	•	Name: YYYY-MM-DD_agent-view-button-role-check.md
	•	Include:
	•	Summary of implementation
	•	Files modified or created
	•	How role/capability checks are implemented
	•	Testing instructions

⸻
