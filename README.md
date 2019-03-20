# sso-client扩展包

### 1.安装

```
composer require sj-phpteam/package-sso-client dev-master
```

### 2.配置

1. 生成config文件和controller

   ```
   php artisan vendor:publish --tag=ssoClient
   ```

   config/ssoClient.php(如有需要自行配置)

   app/Http/Controllers/SsoClientController.php(如有需要自行编写function)

2. .env配置

   ```tcl
   SSO_CLIENT_DOMAIN=域
   SSO_CLIENT_CLIENT_ID=客户端ID
   SSO_CLIENT_CLIENT_SECRET=客户端密钥
   SSO_CLIENT_REDIRECT_URI=回调地址
   SSO_CLIENT_AUTHORIZATION_SERVER=认证服务器地址
   SSO_CLIENT_AUTHENTICATION_TOKEN_ADDRESS=获取token地址
   SSO_CLIENT_WEB_REDIRECT_URI=返回主页地址
   ```

3. 添加路由中间件

   找到app/Http/Middleware/Kernel.php

   ```php
   protected $routeMiddleware = [
   	'ssoClient'=>\Phpteam\SsoClient\Middleware\SsoClientVerity::class,
   ];
   ```

4. 设置路由

   找到route/web.php

   ```php
   Route::group(['middleware' => 'ssoClient'], function () {
   	//填写要登录后才能进入到路由
   });
   ```

5. 注册守卫

   找到config/auth.php

   ```php
   'guards' => [
   	'ssoClient' => [
   		'driver' => 'session',
   		'provider' => 'ssoClientUser'
   	],
   ],
   'providers' => [
   	'ssoClientUser' => [
   		'driver' => 'eloquent',
   		'model' => \Phpteam\SsoClient\Model\SsoClientUser::class,
   	],
   ],
   ```

### 3.用法

1. 获取登录用户信息

   ```php
   //用户id
   SsoClient::getUserId();
   //用户名称
   SsoClient::getUserName();
   ```

   提示:用户信息目前仅有userId和userName