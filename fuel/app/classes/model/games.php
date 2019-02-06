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
			'owner' => $owner,
			'created_at' => time(),
		];
		DB::insert('games')->set($values)->execute();
	}
}