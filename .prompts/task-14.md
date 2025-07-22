We have an issue with our custom block that adds a button to copy the Agent Mode link. The problem is:
	•	When the shortcode is used, the JavaScript loads correctly.
	•	When both the block and the shortcode are present, everything works fine.
	•	But when only the block is used (without the shortcode), the JavaScript does NOT load.

Your tasks:
	1.	Investigate how the JavaScript file is currently enqueued.
	•	Check if the script enqueue logic depends on the shortcode being detected.
	•	If it’s inside a conditional for the shortcode, this is the root cause.
	2.	Ensure the script is always loaded when the block is present.
	•	If the block is registered with register_block_type, use the official keys:
	•	view_script (preferred) or
	•	enqueue_assets callback inside register_block_type.
	•	Alternatively, hook into enqueue_block_assets and check for block presence in the post content using has_block().
	3.	Maintain compatibility with both the shortcode and the block.
	•	If there’s special logic for the shortcode, keep it.
	•	Avoid script duplication when both are present.
	4.	Validate the solution with these scenarios:
	•	Page with only the block.
	•	Page with only the shortcode.
	•	Page with both block and shortcode.
	5.	Follow WordPress best practices:
	•	Use wp_enqueue_script() correctly with dependencies and versioning.
	•	Ensure the script is only loaded on pages where it is needed.
	•	Test in both frontend and block editor contexts if required.

Finally, provide the updated code that solves this issue, ensuring the JavaScript loads properly in all cases.
