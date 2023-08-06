# Changelog

Progression and changes will be documented in this file and adhere to the standards listed below:

- [Keep a Changelog guidelines](http://keepachangelog.com/)
- [Semantic Versioning](http://semver.org/)

## [Unreleased]

### Added

- Dashboard functionality.
- Architecture Documentation.
- Laravel Telescope Package.
- Logout functionality.
- Forgot Password functionality.
- Recaptcha for login / register / resend verification email pages.

### Fixed

- Failed Jobs Table Migration.

### Changed

- Tailwind UI Redesign.

## [1.0.0] - 2023-08-06

Initial Release with all prior features.

## [0.5.0] - 2023-08-05

### Added

- Added issue templates for a bug report.
- Added a pull request template.
- 
### Changed

- Changed feature request template to include labels.

## [0.4.0] - 2023-08-05

### Added

- Added issue templates for a new feature request.

## [0.3.0] - 2023-08-05

### Added

- Added dependabot.yml to keep npm packages, composer packages and GitHub Actions up to date.
- Added Laravel Pint workflow to make sure code adheres to PSR-12 standards which runs on pull requests to development and production.
- Added PHPUnit workflow to run tests on pull requests to development and production.

### Changed

- Changed APP_NAME env variable to IdeaHub.
- Changed MAIL_FROM_ADDRESS env variable to info@idea-hub.net.
- Updated composer.lock.

### Removed

- Removed Unit Test Directory in phpunit.xml.

## [0.2.0] - 2023-08-05

### Added

- Added Email Verification Controller.
- Added Email Verification show notice page ('You must verify your email address' page).
- Added Email Verification show notice page routes.
- Added Email Verification show notice page tests.
- Added Email Verification resend functionality.
- Added Email Verification resend route.
- Added Email Verification resend tests.
- Added Email Verification verify functionality.
- Added Email Verification verify route.
- Added Email Verification verify tests.
- Added Login Controller.
- Added Login page.
- Added Login page routes.
- Added Login page tests.
- Added Login functionality.
- Added Login routes.
- Added Login tests.
- Added Login Request.
- Added Login Request custom messages.
- Added Login Request tests.
- Added Register Controller.
- Added Register page.
- Added Register page routes.
- Added Register page tests.
- Added Register functionality.
- Added Register routes.
- Added Register tests.
- Added Register Request.
- Added Register Request custom messages.
- Added Register Request tests.
- Added RegisteredUser Mailable class to send user a welcome email when they register.
- Added RegisteredUser Mailable class tests.
- Added RegisteredUser Mailable markdown view.
- Added Dashboard Controller.
- Added Dashboard page.
- Added Dashboard page routes.
- Added auth routes file include to the Route Service Provider.
- Added userIdentifierExists custom rule to check if a user exists with that email or username.
- Added auth routes file.
- Added RefreshDatabase and WithFaker traits to TestCase class.
- Added fillable, hidden and casts attributed to User Model.

### Changed

- Changed User Model to be able to receive notifications.
- Changed Route Service Provider to make default home route the dashboard.
- Changed default mail config to use idea-hub email address and name.
- Changed UserFactory to use first_name, last_name and username instead of name attribute.
- Changed User migration to include first_name, last_name and username attributes.
- Changed phpunit.xml to use sqlite in memory database.

### Removed

- Remove failed jobs table migration.
- Removed unnecessary includes in api routes file.
- Removed unnecessary includes in console routes file.
- Removed unnecessary includes in web routes file.


## [0.1.0] - 2023-08-03

### Removed

- Removed Laravel Sanctum Package.
- Laravel Sanctum config file.

### Changed

- Updated composer.lock.

