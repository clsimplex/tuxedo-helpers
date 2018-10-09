# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.0] - 2018-10-09

### Added
- FileHelper has been moved here from Monocle, along with its tests.
- Carbon dependency is now explicit within project assets.
- Added QA tools

### Changed
- Updated ruleset.xml
- Some minor QA/QOL updates.

### Removed
- Gate import in the HelpersServiceProvider class.

## [1.3.2] - 2018-08-20

### Added
- New SpamHelper test cases based on real spam. Not yet implemented.

### Fixed
- ArrayHelper::get_attribute_string() `when the classes are modified within the view - local custom classes used to overwrite the changes. "Custom" classes are added to the default classes now.`

## [1.3.1] - 2018-06-27

### Added
- Added QA tools
- SpamHelper::get_russian_word_count `this will really help in the moment. Should we get so large for this to become a problem - we gladly accept the challege.`

### Changed
- Using the markdown links in the CHANGELOG.md properly now.
- Updating format as per QA tools
- Obscured spammer URLs from unit tests.
- Added get_russian_word_count to SpamHelper::get_spam_score. Having russian in a message will heavily penalize the spam score.

## [1.3.0] - 2018-06-25

### Added
- ArrayHelper::array_cycle `This plays with the internal array pointer - so dont treat a cycled array as normal. Call reset().`

## [1.2.1] - 2018-05-30

### Fixed
- composer.json `formatting hotfix. Keywords entries were not wrapped in double quotes.`

## [1.2.0] - 2018-05-30

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

## [1.1.0] - 2018-04-30

### Changed
- SpamHelper::get_keyword_score() `Updated keywords and scores with some keywords.`
- SpamHelper::get_blacklisted_emails() `All russian emails are blocked.`

### Removed
- composer.lock
- laravel/framework requirement

## [1.0.0] - 2018-04-01

### Added
- mailgun package
- SpamHelper `Simple spam helpers for reducing obvious spam contact entries.`

### Changed
- package name update `CLSimplex\Helpers => clsimplex\helpers`
- updated get_phase_of_current_day()

## [0.1.0] - 2018-03-07

### Added
- description in composer.json
- StorageHelper `Wrapper for Laravel Storage facade.`

## [0.0.3] - 2018-02-26

### Changed
- namespace update `CLSimplex\Tuxedo\Helpers => src/Classes/`

## [0.0.2] - 2018-02-20

### Added
- test case for get_attribute_string() bugfix.

### Changed
- package name update `CLSimplex\Tuxedo\Helpers => CLSimplex\Helpers`

### Fixed
- get_attribute_string() URL value encoding bug


## [0.0.1] - 2018-01-17

[Unreleased]: https://github.com/clsimplex/tuxedo-helpers/compare/1.4.0...develop
[1.4.0]: https://github.com/clsimplex/tuxedo-helpers/compare/1.3.2...1.4.0
[1.3.2]: https://github.com/clsimplex/tuxedo-helpers/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/clsimplex/tuxedo-helpers/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/clsimplex/tuxedo-helpers/compare/1.2.1...1.3.0
[1.2.1]: https://github.com/clsimplex/tuxedo-helpers/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/clsimplex/tuxedo-helpers/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/clsimplex/tuxedo-helpers/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/clsimplex/tuxedo-helpers/compare/0.1.0...1.0.0
[0.1.0]: https://github.com/clsimplex/tuxedo-helpers/compare/0.0.3...0.1.0
[0.0.3]: https://github.com/clsimplex/tuxedo-helpers/compare/0.0.2...0.0.3
[0.0.2]: https://github.com/clsimplex/tuxedo-helpers/compare/0.0.1...0.0.2
[0.0.1]: https://github.com/clsimplex/tuxedo-helpers/releases/0.0.1

