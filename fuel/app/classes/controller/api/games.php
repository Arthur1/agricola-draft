<?php
use \Firebase\JWT\JWT;

class Controller_Api_Games extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;

	public function post_create()
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

		// Auth check
		$auth = new Service_Auth();
		if (! $auth->check()) {
			$this->status_code = 401;
			return [
				'result' => false,
				'error' => [
					'message' => 'お手数ですが、再度ログインしてください'
				]
			];
		}
		return [
				'result' => true,
				'error' => [
					'message' => 'お手数ですが、再度ログインしてください'
				]
			];
	}

	private static function check_token()
	{
		$csrf_token = Input::headers(Constant::HEADER_INDEX_CSRF);
		return Security::check_token($csrf_token);
	}

	public function after($response)
	{
		$response = parent::after($response);
		$response->status = $this->status_code;
		return $response;
	}
}