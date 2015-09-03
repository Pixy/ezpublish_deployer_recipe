# Deployer eZ Publish recipe

This recipe has been created to deploy an eZ Publish 5.4 application, running into legacy_mode.
It's based on the Symfony [deployer recipe](https://github.com/deployphp/deployer)  and the [AlCapON ruby gem](http://alafon.github.io/alcapon/) .

Feel free use it, modify and adapt to your eZ Publish project, and contribute to this repo.

# Installation
To see how to install deployer to your environment, go on the [Deployer repository](https://github.com/deployphp/deployer).

# Import eZ Publish recipe
Simply clone or copy the ezpublish.php file into your project.
In the deploy.php file, require the recipe in top of the file :

```php
<?php

require 'ezpublish.php';
``` 

Then configure your deploy.php file for your environment.
Example : 

```php
<?php

require 'ezpublish.php';

// Do not use sudo
set('writable_use_sudo', false);

// Set shared directories
set('shared_dirs', ['ezpublish/logs', 'ezpublish_legacy/var/ezdemo_site_user', 'ezpublish_legacy/var/ezdemo_site_admin']);

// Set writables directories
set('writable_dirs', ['ezpublish/cache', 'ezpublish/logs', 'ezpublish_legacy/var/ezdemo_site_user', 'ezpublish_legacy/var/ezdemo_site_admin']);

// Use the ezpublish directory instead of "app" for symfony commands
set('bin_dir', 'ezpublish');
set('var_dir', 'ezpublish');

// If you want to use specifics files in you prod/staging environment, you can create a new one, and rename it in the specific environment.
set('file_changes', [
    ['ezpublish_legacy/settings/override/site.ini.append.php.prod', 'ezpublish_legacy/settings/override/site.ini.append.php'],
    // ...
] );

server('prod', 'my.server.com', 22)
    ->user('deploy') // Use the deploy user
    ->forwardAgent() // You can use identity key, ssh config, or username/password to auth on the server.
    ->stage('production') 
    ->env('deploy_path', '/var/www/myapp'); // Define the base path to deploy your project to.


set('repository', 'git@github.com:org/myapp.git');
``` 
