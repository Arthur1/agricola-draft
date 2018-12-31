<?php
use \Firebase\JWT\JWT;

class Controller_Api_Auth extends Controller_Rest
{
	protected $format = 'json';

	public function post_login()
	{
		$user_data = Auth::validate_user(Input::post('name'), Input::post('password'));
		if (! $user_data) {
			return null;
		}
	}

	public function post_create()
	{
		Auth::create_user(
			Input::post('name'),
			Input::post('password'),
			Input::post('email')
		);
	}

	public function post_test()
	{
		$token = Input::headers(Constant::HEADER_INDEX_CSRF);
		if (Security::check_token($token)) {
			return ['result' => 'ok'];
		}
		return ['result' => 'ng', 'token' => $token];
	}
}