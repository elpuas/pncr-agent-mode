# GitHub Copilot Project Instructions

## Goal

This project is a WordPress plugin that enables a "Broker Mode" or "Agent Mode" feature for sharing property listings via unique URLs with minimal, unbranded views. The plugin allows agents to share specific properties without exposing the company's brand, layout, or global elements. It operates entirely independently from the RealHomes Theme (Version 4.4.1) while maintaining compatibility.

## Coding Standards

### PHP

- **Version**: PHP 8+ syntax only
- **Standards**: Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- **Arrays**: Use `[]` syntax instead of `array()`
- **Comparisons**: Use strict comparisons (`===`) always
- **Output Escaping**: Sanitize and escape ALL dynamic output:
  - `esc_html()` for text
  - `esc_url()` for URLs
  - `wp_kses_post()` for HTML content
  - `esc_attr()` for attributes
- **Data Fetching**: Use `WP_Query` for fetching properties, never rely on RealHomes theme functions
- **Architecture**: Object-Oriented Programming (OOP) structure with proper namespacing (`PBCRAgentMode\`)
- **Autoloading**: Use PSR-4 autoloading structure with custom autoloader
- **Dependencies**: Prefer dependency injection over global state
- **Security**: Include ABSPATH checks in all PHP files

### CSS

- **Approach**: Modern vanilla CSS only (no Tailwind, Bootstrap, or frameworks)
- **Scoping**: ALL CSS must be scoped to `.agent-mode` namespace
- **Layout**: Use CSS Grid or Flexbox exclusively, no floats
- **Responsive**: Implement container queries for responsive design
- **Modern Features**: Use container queries, CSS nesting, `:has()`, `:is()`, `:not()` selectors
- **Container Queries**: Define proper `container-type: inline-size` and `container-name`
- **Naming**: Semantic class naming (`.property-title`, `.agent-price`, etc.)
- **Accessibility**: Include reduced motion and dark mode support
- **Loading**: Enqueue via `wp_enqueue_style` with proper scoping

### JavaScript

- **Version**: Vanilla JS (ES6+) only unless React is strictly required
- **Loading**: Use `defer` attribute for script loading
- **Scope**: All scripts must be modular and scoped to Agent Mode views only
- **Modern APIs**: Use `fetch`, `class`, `const`, `let`, arrow functions
- **Restrictions**: No jQuery, avoid external libraries unless explicitly approved
- **React**: If required, use functional components with hooks, isolated in `/js/components/`

## Plugin Architecture

### Core Principles

- **Independence**: Must work independently from RealHomes theme templates, styles, and assets
- **OOP Structure**: All classes follow WordPress naming conventions with namespacing
- **No Global State**: Avoid procedural code outside bootstrap file
- **Template Override**: Hook into `template_include` for custom agent view template
- **Query Handling**: Use `?agent_view=1` parameter or custom rewrite endpoint
- **Security**: Verify property exists and is published, return 404 if not

### Class Structure

- **Plugin.php**: Main plugin class with template system integration
- **Loader.php**: Component loader for registering shortcodes and assets
- **Shortcodes/**: Individual shortcode classes (e.g., AgentViewLink.php)
- **Views/**: Template files (agent-template.php)

### Shortcode Requirements

- Register shortcodes via dedicated classes in `Shortcodes\` namespace
- Must return full HTML string with proper escaping
- Only active in `property` post type context
- `[agent_view_link]` must output fully qualified URLs

## Folder Structure

```
pbcr-agent-mode/
├── pbcr-agent-mode.php          # Main plugin bootstrap file
├── includes/
│   ├── Plugin.php               # Main plugin class
│   ├── Loader.php              # Component loader
│   ├── Shortcodes/
│   │   └── AgentViewLink.php   # Shortcode handler
│   ├── Views/
│   │   └── agent-template.php  # Agent view template
│   └── css/
│       └── agent-style.css     # Scoped plugin styles
├── logs/                       # Development logs (mandatory)
├── .github/
│   └── copilot-instructions.md # This file
└── README.md                   # Plugin documentation
```

## Agent View Requirements

### Must Include

- Property title
- Featured image or gallery
- Price
- Location
- Description
- Contact button (WhatsApp/mailto)

### Must Exclude

- Theme branding (no logo, navigation, header/footer)
- Theme styles or scripts
- Private metadata or non-frontend fields
- Authentication requirements

### Technical Requirements

- Mobile-optimized responsive design
- Fast loading performance
- Works without JavaScript frameworks
- No external libraries or CDNs
- Returns 404 for unpublished properties

## AI Expectations

- **Modern Syntax**: Always use PHP 8+, ES6+, CSS container queries, modern selectors
- **Security First**: Escape all outputs properly, validate inputs, include security checks
- **Independence**: Never depend on theme code or RealHomes functions
- **Performance**: Minimal, efficient code with fast mobile loading
- **Standards**: Follow WordPress coding standards strictly
- **Scoped Styles**: All CSS must be namespaced to avoid conflicts
- **Documentation**: Include clear, concise comments for complex logic

## Mandatory Logging

After **each task or significant commit**, create a log file in `/logs/`:

### Log Format: `YYYY-MM-DD_task-name.md`

```markdown
# YYYY-MM-DD – Task: [Short Description]

## Summary
- Description of what was implemented
- Files created or modified
- Known issues or follow-ups required
- Time spent (approximate)

## Technical Implementation Details
[Detailed notes about implementation choices]

## Known Issues or Follow-ups Required
[Any pending work or issues discovered]

## Time Spent
[Approximate time investment]
```

## Development Workflow

1. **Read Context**: Always understand existing code before making changes
2. **Follow Standards**: Adhere to all coding standards listed above
3. **Test Security**: Verify proper escaping, sanitization, and access controls
4. **Mobile First**: Ensure responsive design works on mobile devices
5. **Independent Operation**: Verify plugin works without theme dependencies
6. **Document Changes**: Create mandatory log entry for each significant change
7. **Code Review Ready**: All code must be ready for review at any time

## Restrictions

- **No Theme Dependencies**: Cannot rely on RealHomes theme functions or styles
- **No External Libraries**: Avoid external dependencies unless pre-approved
- **No Inline Styles**: Use CSS classes and external stylesheets only
- **No Global Variables**: Use proper OOP structure and dependency injection
- **No Procedural Code**: Keep procedural code limited to bootstrap file only

This plugin provides a neutral, controlled environment for property sharing without brand exposure, optimized for mobile agent-to-client interactions.
