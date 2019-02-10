<?php
use \Firebase\JWT\JWT;

class Service_Auth
{
	private $is_authenticated = null;
	private $name = null;
	private $email = null;
	private $gravatar = null;
	private $jwt_header = '';

	public function __construct() {
		$header = Input::headers(Constant::JWT_HEADER_KEY);
		$jwt_header_array = explode(' ', $header);
		$jwt_header = Arr::get($jwt_header_array, 1);
		$this->jwt_header = $jwt_header;
		return $this;
	}

	public function check()
	{
		if (! is_null($this->is_authenticated)) {
			return $this->is_authenticated;
		}
		$public_key = File::read(Constant::JWT_PUBLIC_KEY_PATH, true);
		try {
			$jwt_token = JWT::decode($this->jwt_header, $public_key, [Constant::JWT_ALGORITHM]);
			$this->is_authenticated = true;
			$this->name = $jwt_token->name;
			$this->email = $jwt_token->email;
		} catch (Exception $e) {
			$this->is_authenticated = false;
			$this->name = null;
			$this->email = null;
		}
		return $this->is_authenticated;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function get_gravatar()
	{
		return $this->gravatar;
	}

	public static function create_jwt_token($name, $email, $gravatar_url)
	{
		$token = [
			'name' => $name,
			'email' => $email,
			'gravatar_url' => $gravatar_url,
			'iat' => time(),
			'exp' => time() + Constant::JWT_EXPIRATION,
		];
		$private_key = File::read(Constant::JWT_PRIVATE_KEY_PATH, true);
		return JWT::encode($token, $private_key, Constant::JWT_ALGORITHM);
	}
}