Remove Unnecessary Shortcode and Clean Up Codebase

### Context:
- The plugin currently has two shortcodes:
  1. `[agent_view_link]` → Outputs plain Agent Mode URL (handled by `AgentViewLink.php`)
  2. `[agent_view_link_button]` → Outputs interactive button for copying the URL (handled by `AgentViewLinkButton.php`)
- Client confirmed that **only `[agent_view_link_button]` is needed** for this project.

---

### Requirements:

#### 1. Remove Redundant Shortcode
- Delete `AgentViewLink.php` file from `/includes/Shortcodes/`.
- Remove class `AgentViewLink` and all its references.
- Update `Loader.php`:
  - Remove registration of `AgentViewLink`.
  - Ensure only `AgentViewLinkButton` is loaded.

#### 2. Validate `AgentViewLinkButton` Implementation
- Ensure `[agent_view_link_button]`:
  - Works correctly in property context.
  - Checks for logged-in user and required capability.
  - Includes i18n for all texts.
  - Loads JS/CSS assets only when necessary.
  - Uses minimal modern CSS (scoped under `.agent-mode`).
  - JS handles clipboard copy and feedback ("Copied!").

#### 3. Add Filter for Capability
- Allow developers to modify the required capability via a filter:
  ```php
  $required_cap = apply_filters('pbcr_agent_button_capability', 'edit_posts');

	•	Use $required_cap in the capability check logic.

4. Clean Up and Document
	•	Create log file:
	•	Name: YYYY-MM-DD_remove-agentview-shortcode.md
	•	Include:
	•	Reason for removal.
	•	Files deleted or modified.
	•	Testing steps.
	•	Update README.md:
	•	Remove reference to [agent_view_link].
	•	Document [agent_view_link_button] usage with example.

⸻
