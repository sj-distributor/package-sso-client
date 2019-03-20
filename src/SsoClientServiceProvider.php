<?php

namespace Phpteam\SsoClient;

use Illuminate\Support\ServiceProvider;

class SsoClientServiceProvider extends ServiceProvider
{
	public function boot()
	{
		//创建文件生成
		$this->publishes([
			__DIR__ . '/../config/ssoClient.php' => app()->basePath() . '/config/ssoClient.php',
			__DIR__ . '/Controller/SsoClientController.php' => app()->basePath() . '/app/Http/Controllers/SsoClientController.php'], 'ssoClient');

		//自动载入路由
		$this->loadRoutesFrom(__DIR__ . '/Route/SsoClientRoute.php');
	}

	public function register()
	{
		$this->app->singleton('SsoClient', function () {
			return new SsoClient();
		});
	}
}
