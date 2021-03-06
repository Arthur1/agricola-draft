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

	public static function get_all_in_progress($name)
	{
		$query = DB::select()
					->from('games_players')
					->where('name', '=', $name)
					->join('games', 'inner')
					->on('games_players.game_id', '=', 'games.game_id')
					->where('games.status', '=', 0)
					->order_by('created_at', 'desc');
		return $query->execute()->as_array();
	}

	public static function get_all_is_finished($name)
	{
		$query = DB::select()
					->from('games_players')
					->where('name', '=', $name)
					->join('games', 'inner')
					->on('games_players.game_id', '=', 'games.game_id')
					->where('games.status', '=', 1)
					->order_by('created_at', 'desc');
		return $query->execute()->as_array();
	}


	public static function get($game_id)
	{
		$query = DB::select()
					->from('games')
					->where('game_id', '=', $game_id);
		$records = $query->execute()->as_array();
		if (count($records) === 0) {
			return false;
		}
		return $records[0];
	}

	public static function update_status($game_id)
	{
		$query = DB::update('games')
					->set(['status' => 1])
					->where('game_id', '=', $game_id);
		$query->execute();
	}
}