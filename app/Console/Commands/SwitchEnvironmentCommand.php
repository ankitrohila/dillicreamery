<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwitchEnvironmentCommand extends Command
{
    protected $signature = 'env:switch {environment : local or production}';
    protected $description = 'Switch between local and production environment configurations';

    public function handle(): int
    {
        $env = $this->argument('environment');

        if (!in_array($env, ['local', 'production'])) {
            $this->error('Environment must be "local" or "production"');
            return self::FAILURE;
        }

        $envFile = file_get_contents(base_path('.env'));
        if ($envFile === false) {
            $this->error('Could not read .env file');
            return self::FAILURE;
        }

        if ($env === 'production') {
            if (!$this->confirm('⚠️  Switching to PRODUCTION will disable debug mode and change the app URL. Continue?')) {
                $this->info('Cancelled.');
                return self::SUCCESS;
            }

            $envFile = preg_replace('/^APP_ENV=.*/m',       'APP_ENV=production',        $envFile);
            $envFile = preg_replace('/^APP_DEBUG=.*/m',     'APP_DEBUG=false',           $envFile);
            $envFile = preg_replace('/^APP_URL=.*/m',       'APP_URL=https://dillicreamery.in', $envFile);
            $envFile = preg_replace('/^SESSION_DRIVER=.*/m','SESSION_DRIVER=redis',      $envFile);
            $envFile = preg_replace('/^CACHE_STORE=.*/m',   'CACHE_STORE=redis',         $envFile);
            $envFile = preg_replace('/^QUEUE_CONNECTION=.*/m','QUEUE_CONNECTION=redis',  $envFile);
            $envFile = preg_replace('/^LOG_LEVEL=.*/m',     'LOG_LEVEL=error',           $envFile);

            $this->info('✅  Switched to <fg=red>PRODUCTION</> environment');
            $this->warn('   APP_URL = https://dillicreamery.in');
            $this->warn('   APP_DEBUG = false');
        } else {
            $envFile = preg_replace('/^APP_ENV=.*/m',       'APP_ENV=local',             $envFile);
            $envFile = preg_replace('/^APP_DEBUG=.*/m',     'APP_DEBUG=true',            $envFile);
            $envFile = preg_replace('/^APP_URL=.*/m',       'APP_URL=http://localhost:8001', $envFile);
            $envFile = preg_replace('/^SESSION_DRIVER=.*/m','SESSION_DRIVER=file',       $envFile);
            $envFile = preg_replace('/^CACHE_STORE=.*/m',   'CACHE_STORE=file',          $envFile);
            $envFile = preg_replace('/^QUEUE_CONNECTION=.*/m','QUEUE_CONNECTION=database',$envFile);
            $envFile = preg_replace('/^LOG_LEVEL=.*/m',     'LOG_LEVEL=debug',           $envFile);

            $this->info('✅  Switched to <fg=green>LOCAL</> environment');
            $this->warn('   APP_URL = http://localhost:8001');
            $this->warn('   APP_DEBUG = true');
        }

        file_put_contents(base_path('.env'), $envFile);

        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');

        $this->newLine();
        $this->info('🎯  All caches cleared. Environment is now: <fg=yellow>' . strtoupper($env) . '</>');

        return self::SUCCESS;
    }
}
