<?php namespace Samkitano\Stats;

use Illuminate\Support\ServiceProvider;
use Samkitano\Stats\Facades\Stats;

class StatsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('stats',function()
        {
            return new \Samkitano\Stats\Stats();
        });

        $this->registerAliases();
	}


	protected function registerAliases()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Stats', Stats::class);
    }

}
