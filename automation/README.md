# Chatter QA Automation Framework

<img width="1692" height="930" alt="automation" src="https://github.com/user-attachments/assets/07bbeafe-7d61-44f6-a42a-7d4e5f89a981" />

## Project Status

> [!WARNING]
> Chatter is a Laravel-based chat application built as a learning project. It is not currently intended for production use or third-party installation. There are known defects, security issues, and currently only hosted in a controlled lab environment
 
🚧 Active development

This automation framework is being built as a learning and experimentation project.

The goal is to explore and implement modern QA engineering practices, including UI automation, API testing, test data management, security validation, and CI/CD integration.

This repository is not intended to be a production-ready automation framework or a plug-and-play test solution. Components will be added and refined over time as different testing approaches are explored.

---

## Implementation Roadmap

### Test Automation
- [x] Initialize Playwright framework
- [x] Create Playwright configuration and environment management
- [x] Create Python configuration and environment management
- [x] Implement authentication tests - in progress
- [x] Add Page Object Model structure - in progress
- [ ] Build reusable test fixtures
- [ ] Create smoke test suite
- [ ] Create regression test suite

### API Testing
- [x] Create Postman collections - in progress (scripted with newman currently)
- [ ] Add API authentication testing
- [ ] Add API regression scenarios
- [ ] Explore automated API validation

### Test Data & Utilities
- [ ] Python utility scripts
- [ ] Test data generation tools
- [ ] Database validation utilities

### CI/CD
- [ ] GitHub Actions integration
- [ ] Automated test execution
- [ ] Test reporting
- [ ] Failure artifact collection

### Security & Performance
- [ ] Security regression testing
- [ ] OWASP testing scenarios
- [ ] Performance testing exploration
