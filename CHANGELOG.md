# Changelog

Progression and changes will be documented in this file and adhere to the standards listed below:

- [Keep a Changelog guidelines](http://keepachangelog.com/)
- [Semantic Versioning](http://semver.org/)

## [Unreleased]

### Added

- Dashboard functionality.
- Ability to filter team members by more fields
- Ability to paginate team members

## [4.0.0] - 2023-11-01

### Added

- Ability to create a team
- Ability to delete a team
- Ability to view all teams
- Ability to edit a team
- Ability to leave a team
- Ability to a specific view
- Ability to view all members on a team
- Ability to remove a member from a team
- Ability to create an invitation to a team
- Ability to accept an invitation to a team
- Ability to delete an invitation to a team
- Ability to view all invitations to a team
- Ability to create a join for a team
- Ability to delete a join link for a team
- Command to delete invitations that been expired for a week
- Command to delete links that have expired for a day
- Scheduled command to delete expired invitation
- Scheduled command to delete expired join links
- Add emails to email users when they have created a team
- Add emails to email users when they have been invited to a team
- Add emails to email users when they accepted a team invitation
- Ability to filter and order team members

## [3.3.0] - 2023-09-20

### Added

- Default Profile Picture Controller
- Default Profile Picture Model
- Default Profile Picture Factory
- Default Profile Picture Migration
- Default Profile Picture Tests
- Ability to select default profile pictures
- Section for default profile pictures underneath upload profile picture section on edit profile page
- Added sign up item in the profile dropdown menu when user is not logged in
- Added config file items to specify where user profile pictures and default profile pictures are stored

### Changed

- Changed edit profile page to include default profile picture section
- Changed the default profile picture that is shown when user is not logged in
- Moved profile picture functionality to ProfilePictureService
- Changed how profile picture is shown in header view file
- edit profile js file to include default profile picture functionality

## [3.2.0] - 2023-08-22

### Added

- Profile Picture Controller
- Update Profile Picture Request
- Profile Picture Service
- Migration to add profile_picture to users table
- Edit Profile Picture js file for edit profile page
- Ability to Update / Remove and add profile pictures

### Changed

- Changed Profile Edit Page to include profile picture
- Removed profile picture from update profile request
- Separated functionality of updating profile and changing profile picture
- Changed all profile picture sections to use profile picture instead of default idea hub logo
- Changed all profile picture sections to use initials of authenticated user if they do not have a profile picture

## [3.1.0] - 2023-08-20

### Added

- Profile view page
- Profile edit page
- Profile update functionality
- Added Rule to allow people to update their username only once every six hours
- Ability to view other people's profiles 
- Added username_updated_at field to enforce time constraint on username updates
- Added migration to add username_updated_at field to users table

### Changed

- Profile dropdown menu to include profile link
- User factory to include username_updated_at field

## [3.0.0] - 2023-08-19

### Changed

- Redesigned the entire application to use Tailwind CSS
- Changed all pages to display errors and status where needed
- Changed email verification routes to return status instead of message for consistency
- Changed navbar to include dropdowns and make it more mobile friendly / responsive
- Refactored tests accordingly

## [2.0.0] - 2023-08-13

### Added

- Add custom rule to check if recaptcha passes
- Add recaptcha.js to handle running recaptcha, populating form with recaptcha data and submitting form
- Added jquery npm package
- Added laravel vite plugin npm package
- Added ResendEmailVerificationRequest class for recaptcha
- Add config for recaptcha in services.php config and .env.example

### Changed

- Change forgot-password, login, register, reset-password and verify-email views to include recaptcha
- Change forgot-password, login, register, reset-password and verify-email tests to include recaptcha validation
- Change head.blade.php to include recaptcha script and css / js vite files

## [1.7.0] - 2023-08-13

### Added

- Laravel Debug Bar composer package.
- Added DEBUGBAR_ENABLED env variable to .env.example.

## [1.6.0] - 2023-08-13

### Added

- Reset Password Controller / routes / tests / views / form request
- Forgot Password Controller / routes / tests / views / form request
- Ability to send forgot password email
- Ability to reset password
- Added Scheduler to delete old password reset tokens
- Added password resets migration
- Added reset password trait and interface to user model

### Changed

- Revert phpunit.xml to previous config so GitHub Actions can run tests
- Changed name of login page / register page tests for more consistency and readability
- Removes RefreshDatabase trait from test classes class since it is already in the base test class

## [1.5.0] - 2023-08-13

### Added

- Home Controller
- Home Tests
- Home View
- Dashboard Tests
- Logout Controller
- Logout routes
- Logout Tests 
- Added .env.testing to .gitignore file
- Added logout button on Dashboard view
- Added brianium/paratest package for parallel unit testing

### Changed

- Refactored auth routes to stop calling guest middleware multiple times
- Refactor Email Verification routes to also include logout route
- Changed phpunit.xml to use .env.testng config file instead 
- Refactored Verify Email Test since it was being disrupted by other tests

## [1.4.0] - 2023-08-11

### Added

- Added Documentation pages for architecture verbs
- Added Documentation page for GET HTTP Verb
- Added Documentation page for POST HTTP Verb
- Added Documentation page for PATCH HTTP Verb
- Added Documentation page for PUT HTTP Verb
- Added Documentation page for DELETE HTTP Verb
- Added Tailwind UI to the project

## [1.3.0] - 2023-08-11

### Changed

- Updated Composer Dependencies.
- Updated NPM Dependencies.

## [1.2.1] - 2023-08-06

### Fixed

- Fix PR Template checkboxes

## [1.2.0] - 2023-08-06

### Added

- Added Telescope Composer package, configured it and ran its migrations

## [1.1.0] - 2023-08-06

### Changed

- Update PR Template to include Changelog version section

## [1.0.1] - 2023-08-06

### Fixed

- Failed Jobs Table Migration.

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

