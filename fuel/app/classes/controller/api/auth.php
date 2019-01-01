<?php
use \Firebase\JWT\JWT;

class Controller_Api_Auth extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;

	public function post_login()
	{
		$user_data = Auth::validate_user(Input::post('name'), Input::post('password'));
		if (! $user_data) {
			return null;
		}
	}

	public function post_register()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return [
				'error' => [
					'message' => 'CSRF Token Error'
				]
			];
		}

		$data = Input::json();

		// Validation
		$val = Validation::forge();
		$val->add('name', 'ユーザー名')
			->add_rule('required');
		$val->add('password', 'パスワード')
			->add_rule('required');
		$val->add('password_check')
			->add_rule('required');
		$val->add('email', 'メールアドレス')
			->add_rule('required')
			->add_rule('valid_email');
		if (! $val->run($data)) {
			$this->status_code = 400;
			return $val->error();
		}

		// create user
		$result = Auth::create_user($data['name'], $data['password'], $data['email']);
		if (! $result) {
			$this->status_code = 403;
			return [
				'error' => [
					'message' => 'CSRF Token Error'
				]
			];
		}
		return [
			'token' => self::create_jwt_token($data['name'], $data['email']),
		];
	}

	public function post_test()
	{
		$token = Input::headers(Constant::HEADER_INDEX_CSRF);
		if (Security::check_token($token)) {
			return ['result' => 'ok'];
		}
		return ['result' => 'ng', 'token' => $token];
	}

	public function after($response)
	{
		$response = parent::after($response);
		$response->status = $this->status_code;
		return $response;
	}

	private static function check_token()
	{
		$csrf_token = Input::headers(Constant::HEADER_INDEX_CSRF);
		return Security::check_token($csrf_token);
	}

	private static function create_jwt_token($name, $email)
	{
		$token = [
			'name' => $name,
			'email' => $email,
			'iat' => time(),
			'exp' => time() + Constant::JWT_EXPIRATION,
		];
		$private_key = File::read(Constant::JWT_KEY_PATH, true);
		return JWT::encode($token, $private_key, Constant::JWT_ALGORITHM);
	}
}