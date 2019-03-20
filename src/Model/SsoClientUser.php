<?php

namespace Phpteam\SsoClient\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SsoClientUser extends Authenticatable
{
	//定义用户id
	public $userId;
	//定义用户name
	public $userName;

	/**
	 * Note: 设置用户信息
	 * Author: ORANGE.O
	 * Date: 2018/10/26
	 * @param $userId
	 * @param $userName
	 */
	public function setSsoClientUserData($userId, $userName)
	{
		$this->userId = $userId;
		$this->userName = $userName;
	}
}
