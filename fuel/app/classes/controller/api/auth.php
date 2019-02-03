<?php
use \Firebase\JWT\JWT;

class Controller_Api_Auth extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;

	public function post_login()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return [
				'result' => false,
				'error' => [
					'message' => 'お手数ですが、再度送信してください'
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
		if (! $val->run($data)) {
			$this->status_code = 400;
			$messages = [];
			foreach ($val->error() as $error) {
				$messages[] = $error->get_message();
			}
			return [
				'result' => false,
				'error' => [
					'messages' => $messages,
				]
			];
		}

		// select from users
		$user_data = Auth::validate_user(Input::json('name'), Input::json('password'));
		if (! $user_data) {
			$this->status_code = 401;
			return [
				'result' => false,
				'error' => [
					'message' => 'アカウントが存在しません'
				]
			];
		}

		// return JWT token
		return [
			'result' => true,
			'token' => self::create_jwt_token($user_data['username'], $user_data['email']),
		];
	}

	public function post_register()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return [
				'result' => false,
				'error' => [
					'message' => 'お手数ですが、再度送信してください'
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
			$messages = [];
			foreach ($val->error() as $error) {
				$messages[] = $error->get_message();
			}
			return [
				'result' => false,
				'error' => [
					'messages' => $messages,
				]
			];
		}

		// create user
		try {
			$result = Auth::create_user($data['name'], $data['password'], $data['email']);
		} catch (SimpleUserUpdateException $e) {
			$this->status_code = 403;
			switch ($e->getCode()) {
				case 2:
					$message = 'このメールアドレスはすでに登録されています';
					break;
				case 3:
					$message = 'このユーザー名はすでに登録されています';
					break;
				default:
					$message = 'サーバーエラーです';
			}
			return [
				'result' => false,
				'error' => [ 'message' => $message ],
			];
		}
		if (! $result) {
			$this->status_code = 403;
			return [
				'result' => false,
				'error' => [
					'message' => 'サーバーエラーです'
				]
			];
		}
		return [
			'result' => true,
			'token' => self::create_jwt_token($data['name'], $data['email']),
		];
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