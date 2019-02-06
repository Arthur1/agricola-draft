<?php
class Model_GamesImprovements
{
	public static function create($game_id, $improvements_chunk)
	{
		$columns = [
			'game_id',
			'hands_order',
			'card_id',
			'picked_player_order',
			'picked_order',
		];
		$query = DB::insert('games_improvements')->columns($columns);
		$num = count($improvements_chunk);
		for ($i = 0; $i < $num; $i++) {
			foreach ($improvements_chunk[$i] as $improvement) {
				$query->values([$game_id, $i + 1, $improvement, null, null]);
			}
		}
		$query->execute();
	}
}