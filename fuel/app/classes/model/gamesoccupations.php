<?php
class Model_GamesOccupations
{
	public static function create($game_id, $occupations_chunk)
	{
		$columns = [
			'game_id',
			'hands_order',
			'card_id',
			'picked_player_order',
			'picked_order',
		];
		$query = DB::insert('games_occupations')->columns($columns);
		$num = count($occupations_chunk);
		for ($i = 0; $i < $num; $i++) {
			foreach ($occupations_chunk[$i] as $occupation) {
				$query->values([$game_id, $i + 1, $occupation, null, null]);
			}
		}
		$query->execute();
	}
}