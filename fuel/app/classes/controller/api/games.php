<?php
use \Firebase\JWT\JWT;

class Controller_Api_Games extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;
	const REGULATION_LIST = [
		1 => '旧版基本(EIK)',
		2 => '旧版拡張',
	];
	const CARDS_NUMBER_LIST = [
		7 => '7枚ドラフト',
		8 => '8-7枚ドラフト',
		10 => '10-7枚ドラフト',
	];

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

		$whole_cards_number = $data['cards_number'] * $data['players_number'];
		$occupations_all = Model_OccupationsMaster::get_for_create_game($data['regulation_type'], $data['players_number']);
		$occupations_selected = array_rand($occupations_all, $whole_cards_number);
		shuffle($occupations_selected);
		$occupations_chunk = array_chunk($occupations_selected, $data['cards_number']);
		Model_GamesOccupations::create($game_id, $occupations_chunk);

		$improvements_all = Model_ImprovementsMaster::get_for_create_game($data['regulation_type']);
		$improvements_selected = array_rand($improvements_all, $whole_cards_number);
		shuffle($improvements_selected);
		$improvements_chunk = array_chunk($improvements_selected, $data['cards_number']);
		Model_GamesImprovements::create($game_id, $improvements_chunk);

		return [
			'result' => true,
		];
	}

	public function post_in_progress()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return [
				'result' => false,
				'error' => [
					'message' => 'お手数ですが、再度読み込みしてください'
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

		$games = Model_Games::get_in_progress($auth->get_name());
		foreach ($games as &$game) {
			$game['regulation'] = self::REGULATION_LIST[$game['regulation_type']];
			$game['cards_number_description'] = self::CARDS_NUMBER_LIST[$game['cards_number']];
			$game['created_at'] = date('Y/m/d H:i ', $game['created_at']);
		}
		unset($game);
		return $games;
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