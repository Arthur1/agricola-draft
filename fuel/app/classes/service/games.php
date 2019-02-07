<?php
class Service_games
{
	const PLAYERS_NUMBER_LIST = [2, 3, 4, 5, 6];
	const REGULATION_LIST = [
		1 => '旧版基本(EIK)',
		2 => '旧版拡張',
	];
	const CARDS_NUMBER_LIST = [
		7 => '7枚ドラフト',
		8 => '8-7枚ドラフト',
		10 => '10-7枚ドラフト',
	];

	public static function validation_create()
	{
		$val = Validation::forge();
		$val->add_callable('MyValidation');
		$val->add('players_number', 'プレイヤー人数')
			->add_rule('required')
			->add_rule('match_collection', self::PLAYERS_NUMBER_LIST);
		$val->add('regulation_type', 'レギュレーション')
			->add_rule('required')
			->add_rule('match_collection', array_keys(self::REGULATION_LIST));
		$val->add('cards_number', 'カード枚数')
			->add_rule('required')
			->add_rule('match_collection', array_keys(self::CARDS_NUMBER_LIST));
		for ($i = 0; $i < Input::json('players_number'); $i++) {
			$val->add('players.' . $i, 'ユーザー名(' . ($i + 1) . '人目)')
				->add_rule('required')
				->add_rule('exists_user');
		}
		$val->add('players', 'ユーザー名')
			->add_rule('array_unique');
		return $val;
	}

	public static function validation_draft($game_id, $hands_order)
	{
		$occupations = Model_GamesOccupations::get_picking_hands($game_id, $hands_order);
		$improvements = Model_GamesImprovements::get_picking_hands($game_id, $hands_order);
		$val = Validation::forge();
		$val->add('picked_occupation', '職業')
			->add_rule('required')
			->add_rule('match_collection', array_column($occupations, 'card_id'));
		$val->add('picked_improvement', '小さい進歩')
			->add_rule('required')
			->add_rule('match_collection', array_column($improvements, 'card_id'));
		return $val;
	}

	public static function before_player_order(int $my_player_order, int $players_number)
	{
		$before_player_order = $my_player_order - 1;
		if ($before_player_order <= 0) {
			$before_player_order = $players_number;
		}
		return $before_player_order;
	}

	public static function before_picked_order(int $my_picked_order)
	{
		return $my_picked_order - 1;
	}

	public static function hands_order(int $player_order, int $picked_order, int $players_number)
	{
		Log::error($player_order);
		Log::error($picked_order);
		Log::error($players_number);
		$hands_order = ($player_order - $picked_order + 1 + $players_number * 10) % $players_number;
		if ($hands_order === 0) {
			$hands_order = $players_number;
		}
		Log::error($hands_order);
		return $hands_order;
	}

	public static function get_picking_occupations(string $game_id, int $hands_order)
	{
		$occupations = Model_GamesOccupations::get_picking_hands($game_id, $hands_order);
		foreach ($occupations as &$occupation) {
			$occupation['deck_display'] = Model_OccupationsMaster::DECK_LIST[$occupation['deck']];
		}
		return $occupations;
	}

	public static function get_picking_improvements(string $game_id, int $hands_order)
	{
		$improvements = Model_GamesImprovements::get_picking_hands($game_id, $hands_order);
		foreach ($improvements as &$improvement) {
			$improvement['deck_display'] = Model_ImprovementsMaster::DECK_LIST[$improvement['deck']];
		}
		return $improvements;
	}

	public static function get_current_picked_order(array $picked_occupations)
	{
		if (count($picked_occupations) === 0) {
			return 1;
		}
		$picked_orders = array_column($picked_occupations, 'picked_order');
		return max($picked_orders) + 1;
	}

	public static function create_occupations(string $game_id, int $cards_number, int $players_number, int $regulation_type)
	{
		$whole_cards_number = $cards_number * $players_number;
		$occupations_all = Model_OccupationsMaster::get_for_create_game($regulation_type, $players_number);
		$occupations_selected_keys = array_rand($occupations_all, $whole_cards_number);
		$occupations_selected = [];
		foreach ($occupations_selected_keys as $key) {
			$occupations_selected[] = $occupations_all[$key];
		}
		shuffle($occupations_selected);
		$occupations_chunk = array_chunk($occupations_selected, $cards_number);
		Model_GamesOccupations::create($game_id, $occupations_chunk);
	}

	public static function create_improvements(string $game_id, int $cards_number, int $players_number, int $regulation_type)
	{
		$whole_cards_number = $cards_number * $players_number;
		$improvements_all = Model_ImprovementsMaster::get_for_create_game($regulation_type);
		$improvements_selected_keys = array_rand($improvements_all, $whole_cards_number);
		$improvements_selected = [];
		foreach ($improvements_selected_keys as $key) {
			$improvements_selected[] = $improvements_all[$key];
		}
		shuffle($improvements_selected);
		$improvements_chunk = array_chunk($improvements_selected, $cards_number);
		Model_GamesImprovements::create($game_id, $improvements_chunk);
	}
}