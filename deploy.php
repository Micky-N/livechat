<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('application', 'livechat');
set('repository', 'https://github.com/Micky-N/livechat.git');
set('branch', 'master');
set('git_tty', false);

add('shared_files', []);
add('shared_dirs', [
    'storage',
]);

set('release_name', function () {
    $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

    return $date->format('Ymd_His');
});

// Hosts

host('development')
    ->set('hostname', '5.250.181.186')
    ->set('labels', ['stage' => 'dev'])
    ->set('environment', 'development')
    ->set('remote_user', 'livechat')
    ->setSshMultiplexing(false)
    ->set('deploy_path', '/home/livechat/www')
    ->set('http_user', 'livechat')
    ->set('writable_mode', 'chown')
    ->set('keep_releases', 1);

// Hooks

// define the paths to PHP & Composer binaries on the server
set('bin/php', '/usr/bin/php');
set('bin/npm', '/usr/bin/npm');
set('bin/composer', '{{bin/php}} /usr/bin/composer');

// compile our production assets
task('npm:build', function () {
    run('cd {{release_path}} && {{bin/npm}} install');
    run('cd {{release_path}} && {{bin/npm}} run build');
    run('cd {{release_path}} && {{bin/npm}} install --omit=dev');
})->desc('Compile npm files locally');

// Task to upload .env file
task('build:env', function () {
    run('cd {{release_path}} && cp .env.{{environment}} .env');
    writeln('Copied .env.{{environment}} to .env');
})->desc('Create environment file');

task('test', function () {
    run('ls -a');
    writeln('success');
});

task('artisan:migrate')->disable();

after('deploy:vendors', 'build:env');
after('deploy:vendors', 'npm:build');
// automatically unlock when a deploy fails
after('deploy:failed', 'deploy:unlock');
// after a deploy, clear our cache and run optimisations
after('deploy:cleanup', 'artisan:cache:clear');
after('deploy:cleanup', 'artisan:optimize');
