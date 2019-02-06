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
}