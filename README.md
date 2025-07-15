# PBCR Agent Mode Plugin

A WordPress plugin that enables a "Broker Mode" or "Agent Mode" feature for sharing property listings via unique URLs with minimal, unbranded views.

## Purpose

This plugin allows agents to share specific properties without exposing the company's brand, layout, or global elements. The shared link loads only the property information in a neutral, minimal layout optimized for mobile and direct client interaction.

## Features

- **Unbranded Property Views**: Clean, minimal property display without theme branding
- **Mobile Optimized**: Responsive design optimized for mobile devices
- **Secure Access**: Properties must be published to be accessible
- **Interactive Copy Button**: `[agent_view_link_button]` shortcode with clipboard functionality
- **Role-Based Access**: Button only visible to authorized users
- **Independent Operation**: Works independently from RealHomes theme templates

## Installation

1. Upload the plugin files to the `/wp-content/plugins/pbcr-agent-mode/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The plugin will automatically register its functionality

## Usage

### Generating Agent View Copy Buttons

Use the `[agent_view_link_button]` shortcode within any property post to display an interactive copy button:

```html
[agent_view_link_button]
```

Or with a custom label:

```html
[agent_view_link_button label="Share Property"]
```

#### Security Features

- **Login Required**: Button only visible to logged-in users
- **Capability Check**: Default requires `edit_posts` capability
- **Role Customization**: Developers can modify required capability using filter

#### Developer Filter

Customize the required capability:

```php
add_filter( 'pbcr_agent_button_capability', function( $capability ) {
    return 'manage_options'; // Only administrators
});
```

#### Button Functionality

- **One-Click Copy**: Copies agent view URL to clipboard
- **Visual Feedback**: Shows "Copied!" message for 2 seconds
- **Fallback Support**: Works on older browsers
- **Error Handling**: Displays appropriate messages if copy fails

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
│   │   └── AgentViewLinkButton.php   # Copy button shortcode handler
│   ├── Views/
│   │   └── agent-template.php  # Agent view template
│   ├── css/
│   │   ├── agent-style.css     # Main plugin styles
│   │   └── agent-copy-btn.css  # Copy button styles
│   ├── js/
│   │   └── agent-copy-btn.js   # Clipboard functionality
│   └── Blocks/
│       └── AgentCopyButton.php # Gutenberg block support
├── logs/                       # Development logs
└── README.md                   # This file
```

## Development

This plugin follows WordPress coding standards and uses modern development practices.

### Prerequisites

- PHP 8.2 or higher
- Composer
- WordPress 6.4 or higher

### Setup

Install development dependencies:

```bash
composer install
```

Or using the provided Makefile:

```bash
make install
```

### Code Quality

Run PHP CodeSniffer to check coding standards:

```bash
composer run lint
```

Automatically fix coding standards issues:

```bash
composer run lint-fix
```

### Testing

Run the test suite:

```bash
composer run test
```

Run tests with coverage report:

```bash
composer run test-coverage
```

### Development Workflow

1. **Code Standards**: All code must follow WordPress Coding Standards
2. **Testing**: Write tests for new functionality
3. **Documentation**: Update relevant documentation
4. **Logging**: Create log entries for significant changes in `/logs/`

### Available Make Commands

- `make install` - Install development dependencies
- `make lint` - Run PHP CodeSniffer
- `make lint-fix` - Fix coding standards automatically
- `make test` - Run PHPUnit tests
- `make test-coverage` - Run tests with coverage
- `make clean` - Clean generated files
- `make check` - Run all quality checks
- `make help` - Show all available commands

### Architecture

- **Object-oriented PHP structure** with PSR-4 namespacing
- **Modern vanilla CSS** with container queries
- **Scoped CSS classes** (`.agent-mode` namespace)
- **Proper sanitization and escaping**
- **Security best practices**

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
