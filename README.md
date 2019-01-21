# Tuxedo Helpers
Tuxedo Helpers is a collection of simple helpers functions.

## Quality Assurance

`./vendor/bin/phpmd src/ text phpmd.xml`

`./vendor/bin/phpstan analyse src`

`./vendor/bin/psalm --init`

`./vendor/bin/psalm`

`./vendor/bin/phpcs --colors --standard=ruleset.xml`

`./vendor/bin/phpcbf --standard=ruleset.xml`

`./vendor/bin/phpunit`

`./vendor/bin/phpmetrics --report-html=phpmetrics --exclude=Migrations,resources,vendor,tests,node_modules,cache .`
