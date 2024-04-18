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

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'composer:install',
    'artisan:storage:link',
    'artisan:config:clear',
    'artisan:view:clear',
    'build:env',
    'npm:install',
    'npm:build',
    'deploy:cleanup',
    'deploy:unlock',
    'deploy:success',
])->desc('Deploy Laravel Project');

// Task to upload .env file
task('build:env', function () {
    run('cd {{release_path}} && cp .env.{{environment}} .env');
    writeln('Copied .env.{{environment}} to .env');
});

// Custom task to run npm install
task('npm:install', function () {
    run('cd {{release_path}} && npm install');
});

task('composer:install', function () {
    run('cd {{release_path}} && composer install');
});

// Custom task to run npm run production
task('npm:build', function () {
    run('cd {{release_path}} && npm run build');
});

task('deploy:cleanup', function () {
    $keep = get('keep_releases', 3) + 1;
    run("cd {{deploy_path}}/releases && rm -rf $(ls -t | tail -n +$keep)");
});

task('test', function () {
    run('ls -a');
    writeln('success');
});

after('deploy:failed', 'deploy:unlock');
