# Chatter — GitHub Copilot Instructions

## Project Overview

Chatter is a Laravel-based single-page chat application.

### Technology Stack

* Laravel 12
* PHP
* Blade
* Tailwind CSS
* JavaScript
* Vite
* MySQL
* Laravel Breeze authentication
* Laravel Sanctum API authentication
* Playwright for browser automation

## General Development Guidelines

* Understand the existing implementation before making changes.
* Prefer modifying existing components and patterns over introducing new architectural approaches.
* Keep changes focused on the requested functionality.
* Do not rewrite working functionality unnecessarily.
* Preserve existing authentication, API behavior, routing, and database behavior unless the task explicitly requires changes.
* Follow the conventions already present in the project.
* Prefer simple, maintainable solutions over unnecessary abstractions.
* Use Tailwind utilities and existing styling conventions where practical.
* Avoid adding dependencies unless there is a clear technical reason.
* Do not modify unrelated files.
* Before making significant changes, inspect the relevant existing implementation and determine how it currently works.

## Responsive UI Guidelines

Chatter should provide a usable experience across desktop, tablet, and mobile screen sizes.

When implementing responsive behavior:

* Use the existing responsive implementation elsewhere in the application as the primary reference.
* Prefer Tailwind's responsive utilities and existing project conventions.
* Do not duplicate desktop and mobile implementations unless necessary.
* Ensure that content remains usable at narrow viewport widths.
* Avoid introducing horizontal scrolling as a workaround for responsive layout problems.
* Consider both layout and interaction when changing responsive behavior.
* Preserve the existing desktop experience unless the requested change requires otherwise.

## Reference Implementation

The `/profile` page currently has responsive behavior that is known to work correctly.

When working on responsive layout, inspect the `/profile` page and its associated Blade, JavaScript, and CSS/Tailwind implementation before designing a new approach.

The profile page should be treated as a reference for:

* Responsive breakpoints
* Navigation behavior
* Mobile layout
* Tailwind responsive patterns
* Existing UI conventions
* JavaScript interaction patterns

Do not blindly copy the profile implementation. Determine which patterns are reusable and adapt them to the chat interface.

## Chat Interface

The chat interface currently displays a list of Connections alongside the chat area.

The Connections list should remain easily accessible while allowing the chat interface to use the available viewport effectively on smaller screens.

When modifying the chat layout:

* Preserve the existing Connections functionality.
* Preserve the ability to select a connection and open the corresponding conversation.
* Preserve the existing chat/message functionality.
* Preserve image upload functionality.
* Preserve existing authentication behavior.
* Do not change API endpoints or database structures unless required.
* The responsive solution should work without breaking the existing desktop layout.

## Code Changes

Before modifying code:

1. Locate the current chat page/component.
2. Identify how the Connections list is rendered.
3. Identify how a selected Connection affects the chat area.
4. Identify any JavaScript controlling the chat interface.
5. Inspect the `/profile` page for its responsive implementation.
6. Determine the smallest set of files that need to change.

After modifying code:

* Check for obvious JavaScript errors.
* Check for broken Blade syntax.
* Check that existing functionality remains intact.
* Check the responsive behavior at both desktop and narrow viewport sizes.
* Explain what files were changed and why.

## Testing

When practical, verify changes using the existing development environment and Playwright tests.

For UI changes, consider:

* Desktop viewport
* Tablet-sized viewport
* Mobile-sized viewport
* Opening the Connections drawer
* Closing the Connections drawer
* Selecting a connection
* Sending/receiving messages
* Existing chat functionality
* Existing profile responsive behavior

Do not create tests solely to satisfy the task unless they provide meaningful regression coverage.

## Important Constraint

Do not make assumptions about the application's architecture when the existing code can answer the question.

Inspect the code first.
Reuse existing patterns where appropriate.
Make the smallest clean change that satisfies the requested behavior.
