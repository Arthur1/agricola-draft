<?php
use \Firebase\JWT\JWT;

class Controller_Api_Games extends Controller_Rest
{
	protected $format = 'json';
	private $status_code = 200;
	const REGULATION_LIST = Service_Games::REGULATION_LIST;
	const CARDS_NUMBER_LIST = Service_Games::CARDS_NUMBER_LIST;

	public function post_create()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return Service_Api::error('お手数ですが、再度送信してください');
		}

		// Auth check
		$auth = new Service_Auth();
		if (! $auth->check()) {
			$this->status_code = 401;
			return Service_Api::error('お手数ですが、再度ログインしてください');
		}

		$data = Input::json();

		// Validation
		$val = Service_Games::validation_create();
		if (! $val->run($data)) {
			$this->status_code = 400;
			$messages = [];
			foreach ($val->error() as $error) {
				$messages[] = $error->get_message();
			}
			return Service_Api::error($messages);
		}

		// ゲームデータ作成
		$game_id = uniqid(rand());
		$owner = $auth->get_name();
		Model_Games::create($game_id, $owner, $data);

		// プレイヤーをシャッフルし番手を振る
		$players = $data['players'];
		shuffle($players);
		Model_GamesPlayers::create($game_id, $players);

		// 手札データを作成
		Service_Games::create_occupations($game_id, $data['cards_number'], $data['players_number'], $data['regulation_type']);
		Service_Games::create_improvements($game_id, $data['cards_number'], $data['players_number'], $data['regulation_type']);

		return [
			'result' => true,
		];
	}

	public function get_in_progress()
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return Service_Api::error('お手数ですが、再度送信してください');
		}

		// Auth check
		$auth = new Service_Auth();
		if (! $auth->check()) {
			$this->status_code = 401;
			return Service_Api::error('お手数ですが、再度ログインしてください');
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

	public function get_drafts($game_id)
	{
		// CSRF token check
		if (! self::check_token()) {
			$this->status_code = 403;
			return Service_Api::error('お手数ですが、再度送信してください');
		}

		// Auth check
		$auth = new Service_Auth();
		if (! $auth->check()) {
			$this->status_code = 401;
			return Service_Api::error('お手数ですが、再度ログインしてください');
		}

		$players_data = Model_GamesPlayers::get_by_game_id($game_id);
		$my_player_data_key = array_search($auth->get_name(), array_column($players_data, 'name'));
		if ($my_player_data_key === false) {
			$this->status_code = 401;
			return Service_Api::error('お手数ですが、再度ログインしてください');
		}
		$my_player_data = $players_data[$my_player_data_key];
		$player_order = (int) $my_player_data['player_order'];

		$game_data = Model_Games::get($game_id);
		if ($game_data === false) {
			$this->status_code = 400;
			return Service_Api::error('このゲームは存在しません');
		}
		$game_data['regulation'] = self::REGULATION_LIST[$game_data['regulation_type']];
		$game_data['cards_number_description'] = self::CARDS_NUMBER_LIST[$game_data['cards_number']];
		$game_data['created_at'] = date('Y/m/d H:i', $game_data['created_at']);
		$players_number = $game_data['players_number'];

		$picked_order = Model_GamesOccupations::get_current_picked_order($game_id, $player_order);
		if ($picked_order > 7) {
			return [
				'game_data' => $game_data,
				'players_data' => $players_data,
				'is_done' => true,
				'is_ready' => false,
			];
		}

		$before_player_order = Service_Games::before_player_order($player_order, $players_number);
		$before_picked_order = Service_Games::before_picked_order($picked_order);
		$is_ready = $before_picked_order === 0 or Model_GamesOccupations::is_posted($game_id, $before_player_order, $before_picked_order);
		if (! $is_ready) {
			return [
				'game_data' => $game_data,
				'players_data' => $players_data,
				'is_done' => false,
				'is_ready' => false,
			];
		}

		$hands_order = Service_Games::hands_order($player_order, $picked_order, $players_number);
		$picking_occupations = Service_Games::get_picking_occupations($game_id, $hands_order);
		$picking_improvements = Service_Games::get_picking_improvements($game_id, $hands_order);

		return [
			'game_data' => $game_data,
			'players_data' => $players_data,
			'picking_occupations' => $picking_occupations,
			'picking_improvements' => $picking_improvements,
			'picked_order' => $picked_order,
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