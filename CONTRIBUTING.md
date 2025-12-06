# Contributing to WatchTheFlix

Thank you for your interest in contributing to WatchTheFlix! This document provides guidelines and instructions for contributing to the project.

## Code of Conduct

Please be respectful and constructive in all interactions. We aim to maintain a welcoming and inclusive community.

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://github.com/YOUR_USERNAME/WatchTheFlix.git`
3. Create a new branch: `git checkout -b feature/your-feature-name`
4. Install dependencies: `composer install && npm install`
5. Copy `.env.example` to `.env` and configure
6. Run migrations: `php artisan migrate`
7. Seed the database: `php artisan db:seed`

## Development Workflow

### Making Changes

1. Follow Laravel's coding standards
2. Write clear, self-documenting code
3. Add comments for complex logic
4. Update documentation as needed
5. Write or update tests for new features

### Code Style

We use Laravel Pint for code styling:

```bash
./vendor/bin/pint
```

### Testing

Run tests before committing:

```bash
php artisan test
```

### Committing

- Write clear, descriptive commit messages
- Use present tense ("Add feature" not "Added feature")
- Reference issues in commits when applicable

Example:
```
Add user profile editing feature

- Implement profile update controller
- Create profile edit view
- Add validation for profile fields
- Update tests

Fixes #123
```

## Pull Request Process

1. Update the README.md with details of changes if needed
2. Update documentation for new features
3. Ensure all tests pass
4. Run code style checks
5. Create a pull request with a clear title and description
6. Link related issues in the PR description
7. Wait for code review

### PR Title Format

- `Feature: Add user authentication`
- `Fix: Resolve media loading issue`
- `Docs: Update installation instructions`
- `Refactor: Improve database queries`

## Feature Requests

- Open an issue with the "enhancement" label
- Provide clear use cases
- Explain the expected behavior
- Discuss alternatives considered

## Bug Reports

Include:
- Laravel version
- PHP version
- Browser and OS (if frontend issue)
- Steps to reproduce
- Expected vs actual behavior
- Screenshots if applicable
- Error messages and stack traces

## Areas for Contribution

### High Priority
- Test coverage improvements
- Documentation enhancements
- Performance optimizations
- Accessibility improvements

### Features
- TMDB API integration
- Advanced search filters
- Subtitle support
- Multi-language support
- Mobile apps

### Bug Fixes
- Check open issues labeled "bug"
- Reproduce and fix
- Add tests to prevent regression

## Development Guidelines

### Database Changes
- Always create migrations for schema changes
- Never modify existing migrations that have been deployed
- Use descriptive migration names
- Add rollback logic

### API Changes
- Maintain backward compatibility when possible
- Version breaking API changes
- Update API documentation

### Frontend
- No inline JavaScript in Blade views
- Use TailwindCSS utility classes
- Keep components reusable
- Ensure mobile responsiveness
- Test on multiple browsers

### Security
- Never commit sensitive data
- Use Laravel's security features
- Validate all user input
- Sanitize output
- Follow OWASP guidelines

## Questions?

- Open a discussion on GitHub
- Check existing issues and PRs
- Review Laravel documentation

Thank you for contributing to WatchTheFlix!
