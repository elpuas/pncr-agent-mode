# PBCR Agent Mode Plugin

A WordPress plugin that enables a "Broker Mode" or "Agent Mode" feature for sharing property listings via unique URLs with minimal, unbranded views.

## Purpose

This plugin allows agents to share specific properties without exposing the company's brand, layout, or global elements. The shared link loads only the property information in a neutral, minimal layout optimized for mobile and direct client interaction.

## Features

- **Unbranded Property Views**: Clean, minimal property display without theme branding
- **Mobile Optimized**: Responsive design optimized for mobile devices
- **Secure Access**: Properties must be published to be accessible
- **Shortcode Integration**: `[agent_view_link]` shortcode for easy URL generation
- **Independent Operation**: Works independently from RealHomes theme templates

## Installation

1. Upload the plugin files to the `/wp-content/plugins/pbcr-agent-mode/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The plugin will automatically register its functionality

## Usage

### Generating Agent View Links

Use the `[agent_view_link]` shortcode within any property post to generate the agent view URL:

```
[agent_view_link]
```

This will output a full URL like: `https://yourdomain.com/property/property-name/?agent_view=1`

### Accessing Agent Views

Agent view URLs can be shared directly with clients. The URLs will display:

- Property title
- Featured image or gallery
- Price (when implemented)
- Location (when implemented)  
- Property description
- Contact button

## Technical Requirements

- WordPress 5.0 or higher
- PHP 8.0 or higher
- RealHomes Theme 4.4.1 (for property data compatibility)

## File Structure

```
pbcr-agent-mode/
├── pbcr-agent-mode.php          # Main plugin file
├── includes/
│   ├── Plugin.php               # Main plugin class
│   ├── Loader.php              # Component loader
│   ├── Shortcodes/
│   │   └── AgentViewLink.php   # Shortcode handler
│   ├── Views/
│   │   └── agent-template.php  # Agent view template
│   └── Css/
│       └── agent-style.css     # Plugin styles
├── logs/                       # Development logs
└── README.md                   # This file
```

## Development

This plugin follows WordPress coding standards and uses:

- Object-oriented PHP structure with namespacing
- Modern vanilla CSS with container queries
- Scoped CSS classes (`.agent-mode` namespace)
- Proper sanitization and escaping
- Security best practices

## License

GPL v2 or later

## Changelog

### 1.0.0
- Initial plugin scaffold
- Basic agent view functionality
- Shortcode implementation
- Responsive CSS framework

---

For development guidelines and detailed documentation, see the `/logs/` directory. 