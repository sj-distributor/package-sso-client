<?php

namespace Phpteam\SsoClient\Facade;

use Illuminate\Support\Facades\Facade;

class SsoClient extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'SsoClient';
	}
}