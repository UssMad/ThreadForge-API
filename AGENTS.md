# AGENTS.md

## Project
ThreadForge API is a pure RESTful API built with Laravel 13.

The goal is to transform raw technical content into optimized X (Twitter) posts using AI.

## Architecture Rules

- Use Laravel 13 conventions.
- This is a HEADLESS API.
- Never generate Blade views.
- Never use web.php routes.
- All endpoints must be defined in routes/api.php.
- All responses must be JSON.
- Use Laravel API Resources for responses.
- Use Form Requests for validation.
- Use Eloquent ORM.
- Keep controllers thin.
- Put business logic inside dedicated services when needed.
- Use clear and maintainable code.

## Authentication

- Use Laravel Sanctum.
- Authenticate using Bearer Tokens.
- Protect private routes using auth:sanctum.
- Never expose sensitive fields.

## Validation

- Use Form Requests exclusively.
- Return validation errors using Laravel's default JSON 422 responses.
- Do not manually validate inside controllers.

## API Responses

Always return JSON.

Successful responses:
- 200 OK
- 201 Created
- 202 Accepted
- 204 No Content

Error responses:
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity
- 500 Internal Server Error

## Resources

Use API Resources to control outputs.

Never expose:
- password
- remember_token
- pivot
- internal attributes

## Code Quality

- Use strict typing whenever appropriate.
- Follow PSR-12.
- Use meaningful variable names.
- Keep methods focused on one responsibility.
- Avoid duplicated code.

## Git

Commit messages should follow Conventional Commits.

Examples:
- feat(auth): implement register endpoint
- feat(auth): implement login endpoint
- fix(auth): revoke current token on logout
- refactor(auth): simplify login flow

## Scope

When asked to generate code:
- Only generate what is requested.
- Do not modify unrelated files.
- Do not introduce additional packages.
- Prefer explicit code over magic.