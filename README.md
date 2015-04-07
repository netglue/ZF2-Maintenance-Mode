# ZF2 Maintenance Mode Module

A simple module that allows you to create a lock file putting the entire app into 'maintenance mode' for HTTP requests.

## Install

Install via composer: `netglue/zf2-maintenance-mode`

Enable it in your `application.config.php` file with the module name `NetglueMaintenanceMode`

## Configure

At the very least, you will need to supply a location for the lock file that's easy for you to create and delete manually _(If you want to)_ and probably create a maintenance HTML file too.

Inspect `config/module.config.php` for available options and override them appropriately in your own config files.

## Usage

To enable maintenance mode manually, simply create a file on disk at the expected location with something like `touch /path/to/expected/file`, when you want to disable maintenance mode, just `rm` the file.

There is a simple console route setup so you can issue `php index.php maintenance -v` and maintenance mode is toggled either on or off depending on the current state.

CLI routes to your app are not affected, only HTTP routes.

---
[Made by netglue](https://netglue.uk)
