<?php

require 'recipe/symfony.php';

/**
 * Install Symfony and eZPublish assets
 */
task('deploy:assets:install', function () {
    run('php {{release_path}}/' . trim(get('bin_dir'), '/') . '/console assets:install --symlink {{release_path}}/web');
    run('php {{release_path}}/' . trim(get('bin_dir'), '/') . '/console ezpublish:legacy:assets_install --symlink {{release_path}}/web');
})->desc('Install eZ Publish assets');

/**
 * Generate eZPublish autoloads
 */
task('ezpublish:autoloads:generate', function () {
    run( "if [ ! -d {{release_path}}/ezpublish_legacy/autoload ]; then mkdir -p {{release_path}}/ezpublish_legacy/autoload; fi;" );
    run('cd {{release_path}}/ezpublish_legacy && sudo -u deploy php bin/php/ezpgenerateautoloads.php --extension');
    run('cd {{release_path}}/ezpublish_legacy && sudo -u deploy php bin/php/ezpgenerateautoloads.php --kernel-override');
})->desc('Generate eZ Publish legacy autoloads');

/**
 * Rename files
 * Eg: rename ezpublish/config/ezpublish.yml.prod => ezpublish/config/ezpublish.yml
 */
task('ezpublish:settings:deploy', function () {
    $file_changes = get('file_changes');

    foreach ($file_changes as $file_change) {
        $fileToRename = $file_change[0];
        $newFilename  = $file_change[1];

        run( "if [ -f {{release_path}}/$fileToRename ]; then cp {{release_path}}/$fileToRename {{release_path}}/$newFilename; fi;" );
    }
})->desc('Rename files for environments');


task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:writable',
    'ezpublish:autoloads:generate',
    'ezpublish:settings:deploy',
    'deploy:assets:install',
    'deploy:assets',
    'deploy:vendors',
    'deploy:assetic:dump',
    'deploy:cache:warmup',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');