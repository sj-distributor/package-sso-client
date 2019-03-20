<?php

namespace Phpteam\SsoClient\Middleware;

use Closure;
use Phpteam\SsoClient\SsoClient;

class SsoClientVerity
{
	private $ssoClient;

	public function __construct(SsoClient $ssoClient)
	{
		$this->ssoClient = $ssoClient;
	}

	public function handle($request, Closure $next)
	{
		//检查是否登录
		if ($this->ssoClient->checkLogin()) {
			return $next($request);
		}
		//清空所有状态
		$this->ssoClient->clearStatus();
		//重新请求登录页面
		return $this->ssoClient->getLoginUrl();
	}
}
