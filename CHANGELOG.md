# Changelog

All Notable changes to Coldreader will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 2.3.1 - 2019-11-22
- Breaking changes since 2.2.1: Dockerized the application, changes to the folder structure, added support for CapRover deployments

## 2.2.1 - 2018-04-22
- Breaking changes since 2.1.1.  Method signatures have changed for the Aspect type in the CRPS package, and I updated the basic aspect types to reflect it.

### Added
- Artisan command for generating boilerplate for Aspect Types. (php artisan coldreader:new aspect_type)
- UsersAspectsJobs for queueing
- firstrun.php file to make installation easier
- Added some basic tests to phpunit.xml
- Moved most of the installation process to an artisan command. (php artisan coldreader:install)
- Added a quicky artisan command for running migrations on the system within a Docker container, to facilitate testing with Docker.

### Deprecated
- Nothing

### Fixed
- Corrected method signatures for Aspects edit_form method
- Lots of style fixes
- New versions of php libraries in the composer.lock

### Removed
- Nothing

### Security
- Nothing


## 2.1.1 - 2018-04-22
- First stable version.  This should be reasonably useable by production systems.

### Added
- Laravel 5.6 support
- Working installation method
- Basic aspect types
- Basic network aspect types
- A simple theme system, with both a light and dark theme
- Basic user preference settings
- Timezone autoguessing
- Lot of Vue.JS front-end widgetry
- Search provider registry
- User preference registry
- Media library functionality through the spatie/medialibrary package

### Deprecated
- Since this is the first stable version with a working installer, all previous versions are deprecated.

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
