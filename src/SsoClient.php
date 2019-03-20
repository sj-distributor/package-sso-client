<?php

namespace Phpteam\SsoClient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Phpteam\SsoClient\Model\SsoClientUser;

class SsoClient
{
	//定义http请求client
	private $client;

	/**
	 * Note: 初始化
	 */
	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client();
	}

	/**
	 * Note: 回调方法
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function callback(Request $request)
	{
		try {
			$tokenInfo = $this->getToken($request->code);
		} catch (\Exception $oException) {
			//跳转至登录页面
			$this->clearStatus();
			return $this->getLoginUrl();
		}
		//回调保存token到cookie
		setcookie('SJOA_TICKET', $tokenInfo['access_token'], time() + 172800, '/', '.' . config('ssoClient.domain'), false);

		return redirect(config('ssoClient.web_redirect_uri'));
	}

	/**
	 * Note: 验证是否登录
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @return bool
	 */
	public function checkLogin()
	{
		$token = empty($_COOKIE['SJOA_TICKET']) ? null : $_COOKIE['SJOA_TICKET'];
		if (empty($token)) {
			return false;
		}
		$UserInfo = $this->checkToken($token);
		if (!$UserInfo) {
			return false;
		}
		$this->login($UserInfo);
		return true;
	}

	/**
	 * Note: 清除登录所有状态
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 */
	public function clearStatus()
	{
		Auth::guard('ssoClient')->logout();
		Session::flush();
		setcookie('SJOA_TICKET', null, null, '/', '.' . config('ssoClient.domain'));
		setcookie('ApplicationCookie', null, null, '/', '.' . config('ssoClient.domain'));
	}

	/**
	 * Note: 跳转登录地址
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function getLoginUrl()
	{
		return redirect(
			config('ssoClient.authorization_server') . config('ssoClient.authorize_end_point') .
			"?client_id=" . config('ssoClient.client_id') .
			"&redirect_uri=" . config('ssoClient.redirect_uri') .
			"&response_type=" . config('ssoClient.response_type')
		);
	}

	/**
	 * Note: 登出
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function logout()
	{
		$this->clearStatus();
		return $this->getLoginUrl();
	}

	/**
	 * Note: 获取登录用户Id
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @return mixed
	 */
	public function getUserId()
	{
		return Auth::guard('ssoClient')->user()->userId;
	}

	/**
	 * Note: 获取登录用户名称
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 */
	public function getUserName()
	{
		return Auth::guard('ssoClient')->user()->userName;
	}

	/**
	 * Note: 获取token(by code)
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @param $code
	 * @return mixed
	 */
	private function getToken($code)
	{
		$tokenUrl = config('ssoClient.authorization_server') . config('ssoClient.token_path');
		$result = $this->client->request('POST', $tokenUrl, [
			'form_params' => [
				'code' => $code,
				'grant_type' => config('ssoClient.grant_type'),
				'redirect_uri' => config('ssoClient.redirect_uri'),
			],
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode(config('ssoClient.client_id') . ':' . config('ssoClient.client_secret')),
			],
			'verify' => false
		]);
		$tokenInfo = json_decode((string)$result->getBody(), true);
		return $tokenInfo;
	}

	/**
	 * Note: 校验token获取的用户信息判断token的有效性(有效则返回用户信息，无效返回false)
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @param $token
	 * @return bool|mixed
	 */
	private function checkToken($token)
	{
		$principalUrl = config('ssoClient.authentication_token_address');
		$result = $this->client->request('GET', $principalUrl, [
			'headers' => ['Authorization' => 'Bearer ' . $token]
		]);
		$userInfo = json_decode($result->getBody(), true);
		if ($userInfo['UserName'] == null) {
			return false;
		}
		return $userInfo;
	}

	/**
	 * Note: 认证登录信息
	 * Author: ORANGE.O
	 * Date: 2018/10/24
	 * @param $userInfo
	 */
	private function login($userInfo)
	{
		$user = new SsoClientUser();
		$user->setSsoClientUserData($userInfo['UserID'], $userInfo['UserName']);
		Auth::guard('ssoClient')->login($user);
	}
}