<?php
class Model_Games
{
	public static function create($game_id, $owner, $data)
	{
		$values = [
			'game_id' => $game_id,
			'players_number' => $data['players_number'],
			'regulation_type' => $data['regulation_type'],
			'cards_number' => $data['cards_number'],
			'status' => 0,
			'owner' => $owner,
			'created_at' => time(),
		];
		DB::insert('games')->set($values)->execute();
	}

	public static function get_in_progress($name)
	{
		$query = DB::select()
					->from('games_players')
					->where('name', '=', $name)
					->join('games', 'inner')
					->on('games_players.game_id', '=', 'games.game_id')
					->where('games.status', '=', 0);
		return $query->execute()->as_array();
	}
}