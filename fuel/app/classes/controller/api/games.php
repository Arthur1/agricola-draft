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

		$data = Input::json();

		// Validation
		$val = Validation::forge();
		$val->add_callable('MyValidation');
		$val->add('players_number', 'プレイヤー人数')
			->add_rule('required')
			->add_rule('match_collection', [2, 3, 4, 5, 6]);
		$val->add('regulation_type', 'レギュレーション')
			->add_rule('required')
			->add_rule('match_collection', [1, 2, 3, 4, 5]);
		$val->add('cards_number', 'カード枚数')
			->add_rule('required')
			->add_rule('match_collection', [7, 8, 10]);
		for ($i = 0; $i < Input::json('players_number'); $i++) {
			$val->add('players.' . $i, 'ユーザー名(' . ($i + 1) . '人目)')
				->add_rule('required')
				->add_rule('exists_user');
		}
		$val->add('players', 'ユーザー名')
			->add_rule('array_unique');

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

		$game_id = uniqid(rand());
		$owner = $auth->get_name();
		Model_Games::create($game_id, $owner, $data);

		$players = $data['players'];
		shuffle($players);
		Model_GamesPlayers::create($game_id, $players);

		return [
			'result' => true,
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