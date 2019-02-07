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
				$query->values([$game_id, $i + 1, $improvement['card_id'], null, null]);
			}
		}
		$query->execute();
	}

	public static function get_picking_hands($game_id, $hands_order)
	{
		$query = DB::select()
					->from('games_improvements')
					->join('improvements_master', 'inner')
					->on('games_improvements.card_id', '=', 'improvements_master.card_id')
					->where('game_id', '=', $game_id)
					->and_where('hands_order', '=', $hands_order)
					->and_where('picked_order', '=', null);
		return $query->execute()->as_array();
	}
}