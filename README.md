# CanYouRunIt Tool

**CanYouRunIt Tool** is a simple application built in **PHP** that will allow you to validate your environment for a specific set of constraints set by you such as **PHP Modules**, **PHP Configurations**, **Databases**, **Work Queues** and more. 

## What can it do

Currently you can validate your environment only for the following constraints:

- Check for **PHP** and **PHP Modules**
  - Check if specific module is installed e.g. `xdebug`
  - Check if the module version matches your expectations e.g. `xdebug is 2.4.*`
  - Add required check to mark the module as **required** or **good to have**
- Check for **PHP Configuration**
  - Check if some php config value is set e.g. `date.timezone is different than null or ''`
  - Check if some php config value is set to expected value e.g. `date.timezone is equal to 'Europe\Berlin'`
- Check for **Database** installation
  - Check if database with specified version is installed e.g. `PostgreSQL is 9.3.*`

## Installation

 1. `git clone` this repository.
 2. Download composer: `curl -s https://getcomposer.org/installer | php`
 3. Install dependencies: `php composer.phar install`

## Usage

 1. Install this tool using the guide above
 2. Copy the `config.sample.php` file and paste it as `config.php`
 3. Change the `config.php` file to suite your needs
 4. Run the application to check for your environment `./console check`

## Future plans for adding new services

- MySQL
- Redis
- Beanstalkd
- phpunit
- node
- apache
- nginx
- Anything else you would like to see added?

## Config Example

```php
return [
    // PHP Modules
    new PHPModule('php', '5.5.9', true),
    new PHPModule('pdo_pgsql', Version::ANY_VERSION, true),
    new PHPModule('mcrypt', Version::ANY_VERSION, true),
    new PHPModule('mongo', Version::ANY_VERSION, true),
    new PHPModule('xdebug', '2.*', false),
    new PHPModule('tideways', '3.*', false),

    // PHP Configuration
    new PHPConfiguration('date.timezone'),
    new PHPConfiguration('short_open_tag', '0'),

    // Services
    new PostgreSQL('9.3.*', 'hostname', 'username', 'password', ['database1', 'database2', 'database3']),
];
```

## Contribution Guide

**CanYouRunIt Tool** follows the **PSR-2** coding standard and the **PSR-4** autoloading standard, if you wish to contribute please follow these standards.

We also strongly encourage you to file any **bug reports** as a **GitHub issue** and fix them in a **pull request** if possible. In the **bug reports** please include a title and a clear description of the issue with as much information as possible.

 
## License

**CanYouRunIt Tool** is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT). 