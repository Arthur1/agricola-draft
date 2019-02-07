<?php
class Model_GamesPlayers
{
	public static function create($game_id, $players)
	{
		$query = DB::insert('games_players')->columns(['game_id', 'player_order', 'name']);
		$num = count($players);
		for ($i = 0; $i < $num; $i++) {
			$query->values([$game_id, $i + 1, $players[$i]]);
		}
		$query->execute();
	}

	public static function get_by_game_id($game_id)
	{
		$query = DB::select()
					->from('games_players')
					->where('game_id', '=', $game_id)
					->order_by('player_order', 'asc');
		return $query->execute()->as_array();
	}
}