<?php
class Model_GamesOccupations extends Model_GamesCards
{
	// override
	protected static $table_name = 'games_occupations';
	protected static $master_table_name = 'occupations_master';

	public static function is_posted($game_id, $player_order, $picked_order)
	{
		$query = DB::select(DB::expr('COUNT(`picked_order`) AS `count`'))
					->from('games_occupations')
					->where('game_id', '=', $game_id)
					->and_where('picked_player_order', '=', $player_order)
					->and_where('picked_order', '=', $picked_order);
		$records = $query->execute()->as_array();
		$count = (int) $records[0]['count'];
		return $count === 1;
	}

	public static function is_finished($game_id, $players_number)
	{
		$query = DB::select(DB::expr('COUNT(*) AS `count`'))
					->from('games_occupations')
					->where('game_id', '=', $game_id)
					->and_where('picked_order', '=', 7);
		$records = $query->execute()->as_array();
		$count = (int) $records[0]['count'];
		return $count === $players_number;
	}
}