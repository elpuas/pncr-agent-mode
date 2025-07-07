# Prompt 0003 – Migrate Cursor Rules to GitHub Copilot Instructions

## Goal

Migrate all project-specific rules and expectations currently stored in the `.cursor/rules` folder to a Copilot-compatible format for use in **Visual Studio Code**.

## Context

You are now working in VS Code with **GitHub Copilot** instead of Cursor. Copilot uses a `.github/copilot-instructions.md` file to read project-specific instructions and guide code completions or AI agents accordingly.

The Cursor-specific folder `.cursor/rules` is no longer used by GitHub Copilot. To ensure consistent behavior and development standards, the rules must be converted into Copilot-style prompting instructions.

## Task

1. Read and extract the rules defined in `.cursor/rules` or project-specific prompts (including CSS, PHP, JS architecture, logging requirements, file structure, etc.).
2. Create a new markdown file: `.github/copilot-instructions.md`
3. Organize the content in the following structure:

```md
# GitHub Copilot Project Instructions

## Goal
Brief summary of what this project does.

## Coding Standards

### PHP
- [list rules here]

### CSS
- [list rules here]

### JavaScript
- [list rules here]

## Plugin Architecture
[Insert key architecture expectations from the dev guidelines]

## Folder Structure
[Brief summary or sample tree]

## AI Expectations
- Always use modern syntax (PHP 8+, ES6+, CSS container queries, etc.).
- Escape all outputs properly.
- Never depend on theme code.
- Add a log entry in `/logs` after each task, named with the format `YYYY-MM-DD_task-name.md`.
