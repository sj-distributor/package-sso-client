<?php
return [
	'token_path' => '/Token',
	'grant_type' => 'authorization_code',
	'response_type' => 'code',
	'authorize_end_point' => 'oauth2/authorize',
	'domain' => env('SSO_CLIENT_DOMAIN', ''),
	'client_id' => env('SSO_CLIENT_CLIENT_ID', ''),
	'logout_uri' => env('SSO_CLIENT_LOGOUT_URI', ''),
	'redirect_uri' => env('SSO_CLIENT_REDIRECT_URI', ''),
	'client_secret' => env('SSO_CLIENT_CLIENT_SECRET', ''),
	'web_redirect_uri' => env('SSO_CLIENT_WEB_REDIRECT_URI', ''),
	'authorization_server' => env('SSO_CLIENT_AUTHORIZATION_SERVER', ''),
	'authentication_token_address' => env('SSO_CLIENT_AUTHENTICATION_TOKEN_ADDRESS', ''),
];