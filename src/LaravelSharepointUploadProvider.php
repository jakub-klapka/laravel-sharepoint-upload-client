<?php

namespace JakubKlapka\LaravelSharepointUploadClient;

use Illuminate\Support\ServiceProvider;
use JakubKlapka\LaravelSharepointUploadClient\Factories\ClientFactory;

class LaravelSharepointUploadProvider extends ServiceProvider {

	/**
	 * Register to laravel IoC
	 */
	public function register() {

		$this->app->singleton( ClientFactory::class );

	}

}