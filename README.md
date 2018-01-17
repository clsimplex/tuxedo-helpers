# Tuxedo Helpers

Tuxedo Helpers is a collection of simple helpers functions.

## License
The Tuxedo software product is the sole property of the [**CL Simplex Consulting and IT Management Corporation**](https://clsimplex.com "CL Simplex Consulting and IT Management Corporation")

See Hosting and Technology Agreement for details regarding license with client.

## QA Commands
These are the various commands used to measure and manage the quality of Tuxedo.
PHPMetric numbers are only landmarks to denote improvement or measure change in the codebase.

`phpunit`

`php vendor/phpmd/phpmd/src/bin/phpmd src/ text phpmd.xml`

`php vendor/phpmetrics/phpmetrics/bin/phpmetrics --quiet --ignore-errors --report-html=phpmetrics --config=phpmetrics.yml --exclude=phpmetrics,vendor,.git src`

`php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix`
