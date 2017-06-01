<?php

/*
 * This file is part of the Dotenv Instagram package.
 *
 * (c) Tiago Perrelli <tiagoyg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dotenv\Instagram\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Arr;
use Dotenv\Instagram\Instagram;
use Dotenv\Instagram\Contracts\Instagram as InstagramContract;

class InstagramServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

	public function boot()
	{
        $this->publishResources();
	}    

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('instagram', function ($app) {

        	$config = $app['config']['instagram'];

        	return new Instagram(
        		$app['request'], 
        		$config['client_id'],
        		$config['client_secret'],
        		$config['redirect'],
        		Arr::get($config, 'scopes', []),
        		Arr::get($config, 'guzzle', [])
        	);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['instagram'];
    }    
  
    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        $this->publishes([__DIR__.'/../resources/config/instagram.php' => config_path('instagram.php')], 'config');
        $this->mergeConfigFrom(__DIR__.'/../resources/config/instagram.php', 'instagram');
    }
}