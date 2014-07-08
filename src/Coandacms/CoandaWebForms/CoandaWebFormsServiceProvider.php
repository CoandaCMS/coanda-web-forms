<?php namespace CoandaCMS\CoandaWebForms;

use Illuminate\Support\ServiceProvider;

class CoandaWebFormsServiceProvider extends ServiceProvider {

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
		$this->package('coandacms/coanda-web-forms');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['coandawebforms.processsubmissions'] = $this->app->share(function($app)
		{
		    return new \CoandaCMS\CoandaWebForms\Artisan\ProcessSubmissions($app);
		});
		
		$this->commands('coandawebforms.processsubmissions');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
