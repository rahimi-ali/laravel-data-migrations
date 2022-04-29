# Laravel Data Migrations

Available in [Packagist](https://packagist.org/packages/ali-rahimi-coder/laravel-data-migrations)

A simple package to allow rarely changed data to live in your code and get ported to database when used.

Lets say you want to have a database table with world countries. Should you hand out a SQL file with the proper inserts to anyone that wants to clone and use your project? This package tries to solve that issue by keeping that data in you source code which allows anyone to clone your project and run the `data-migration:all` command to get all the required data in their database.

## Commands
- `data-migration:make <name> <table> <path> {--F|force} {--exclusive}`
- `data-migration:migrate <path>`
- `data-migration:all {--exclusive}`

## Usage Notes
- Exclusive migration delete any data that is not in your data migration, use them with great care especially in production.
- The `data-migrations.php` config file can be published to allow the `data-migration:all` command to run a bunch of data migrations you specify with one command.
- All paths should be relative to the project root and start with a directory separator. Example: `data-migration:migrate /Modules/Auth/UserRelatedPermissionsDataMigration.php`

## Todo
- [ ] write automated tests.
- [ ] add examples.

## Ideas
- List of countries?
- Permissions for Authorization?
- Translation strings?

## License
MIT