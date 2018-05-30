# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## 1.2.1 - 2018-05-30

### Fixed
- composer.json `formatting hotfix. Keywords entries were not wrapped in double quotes.`

## 1.2.0 - 2018-05-30

### Added
- Added CHANGELOG.md
- Added LICENSE

### Changed
- Updated composer.json
- phpunit.xml `Removed environmental variables`


### Removed
- Most of README.md
- mailgun/mailgun-php requirement
- Mailgun requirement from MailHelper
- Github OAUTH token for private tuxedo packages

## 1.1.0 - 2018-04-30

### Changed
- SpamHelper::get_keyword_score() `Updated keywords and scores with some keywords.`
- SpamHelper::get_blacklisted_emails() `All russian emails are blocked.`

### Removed
- composer.lock
- laravel/framework requirement

## 1.0.0 - 2018-04-01

### Added
- mailgun package
- SpamHelper `Simple spam helpers for reducing obvious spam contact entries.`

### Changed
- package name update `CLSimplex\Helpers => clsimplex\helpers`
- updated get_phase_of_current_day()

## 0.1.0 - 2018-03-07

### Added
- description in composer.json
- StorageHelper `Wrapper for Laravel Storage facade.`

## 0.0.3 - 2018-02-26

### Changed
- namespace update `CLSimplex\Tuxedo\Helpers => src/Classes/`

## 0.0.2 - 2018-02-20

### Added
- test case for get_attribute_string() bugfix.

### Changed
- package name update `CLSimplex\Tuxedo\Helpers => CLSimplex\Helpers`

### Fixed
- get_attribute_string() URL value encoding bug


## 0.0.1 - 2018-01-17

[Unreleased]: https://github.com/clsimplex/tuxedo-helpers/compare/1.2.1...develop
[1.2.1]: https://github.com/clsimplex/tuxedo-helpers/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/clsimplex/tuxedo-helpers/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/clsimplex/tuxedo-helpers/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/clsimplex/tuxedo-helpers/compare/0.1.0...1.0.0
[0.1.0]: https://github.com/clsimplex/tuxedo-helpers/compare/0.0.3...0.1.0
[0.0.3]: https://github.com/clsimplex/tuxedo-helpers/compare/0.0.2...0.0.3
[0.0.2]: https://github.com/clsimplex/tuxedo-helpers/compare/0.0.1...0.0.2
[0.0.1]: https://github.com/clsimplex/tuxedo-helpers/releases/0.0.1

