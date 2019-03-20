<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpteam\SsoClient\Facade\SsoClient;

class SsoClientController extends Controller
{
	/**
	 * Note: 回调方法
	 * @param Request $request
	 * @return mixed
	 */
	public function callback(Request $request)
	{
		return SsoClient::callback($request);
	}

	/**
	 * Note: 登出
	 * @return mixed
	 */
	public function logout()
	{
		return SsoClient::logout();
	}
}
