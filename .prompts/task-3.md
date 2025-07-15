# Prompt 000X – Initialize Developer Tooling and WordPress Standards

## Goal

Prepare the plugin to support modern developer workflows. Set up the minimum required tooling and configuration files to ensure code quality, testability, and adherence to the WordPress Coding Standards.

## Context

The project currently lacks:

- Composer support
- Linter integration (PHP and JS)
- Unit test environment (PHPUnit)
- CI-compatible folder structure
- Developer documentation for standards and contributions

Your task is to scaffold a developer-friendly plugin structure, compatible with modern PHP practices and WordPress Coding Standards.

## Tasks

### 1. Initialize Composer

- Create a `composer.json` file in the plugin root
- Set up autoloading for the `PBCRAgentMode\` namespace (PSR-4)
- Require the following packages:

```json
{
  "require-dev": {
    "squizlabs/php_codesniffer": "^3.7",
    "wp-coding-standards/wpcs": "^3.0",
    "phpunit/phpunit": "^9.6"
  },
  "autoload": {
    "psr-4": {
      "PBCRAgentMode\\": "includes/"
    }
  },
  "scripts": {
    "lint": "phpcs --standard=WordPress --ignore=vendor .",
    "lint-fix": "phpcbf --standard=WordPress --ignore=vendor .",
    "test": "phpunit"
  }
}
```

### 2. Install and configure WordPress Coding Standards

 • Add a .phpcs.xml config file with the following base:

<?xml version="1.0"?>
<ruleset name="PBCRAgentMode Standards">
  <rule ref="WordPress"/>
  <exclude-pattern>*/vendor/*</exclude-pattern>
  <exclude-pattern>*/node_modules/*</exclude-pattern>
</ruleset>

### 3. Scaffold PHPUnit configuration

 • Create a phpunit.xml.dist file with a basic config for plugin testing
 • Create a /tests folder with an example test case in tests/test-sample.php

### 4. Editor & CI-friendly configs

 • Add .editorconfig (tabs, no trailing spaces, etc.)
 • Add .gitignore with:

```
/vendor/
/node_modules/
/logs/
/tests/output/
/phpunit.xml
```

	• Optionally add a Makefile with shortcuts for make lint, make test, etc.

### 5. Document in README.md

 • Add a ## Development section in README.md with:

## Development

Install dependencies:

```bash
composer install

Run code lint:

composer run lint

Run unit tests:

composer run test

## Output

- New: `composer.json`, `.phpcs.xml`, `phpunit.xml.dist`, `.editorconfig`, `.gitignore`
- New folder: `/tests` with `test-sample.php`
- Updated: `README.md` with dev instructions
- New log: `/logs/YYYY-MM-DD_setup-dev-env.md`

## Constraints

- Do not include Node.js or frontend build tooling at this stage
- Composer setup must be compatible with PHP 8+
- Tests and linters must not require a full WordPress install yet (use stubs)
- All config files must follow plugin coding and formatting conventions
