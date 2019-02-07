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
				$query->values([$game_id, $i + 1, $occupation['card_id'], null, null]);
			}
		}
		$query->execute();
	}

	public static function get_current_picked_order($game_id, $player_order)
	{
		$query = DB::select(DB::expr('MAX(`picked_order`) AS `last_picked_order`'))
					->from('games_occupations')
					->where('game_id', '=', $game_id)
					->and_where('picked_player_order', '=', $player_order);
		$records = $query->execute()->as_array();
		$last_picked_order = (int) $records[0]['last_picked_order'];
		return $last_picked_order + 1;
	}

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

	public static function get_picking_hands($game_id, $hands_order)
	{
		$query = DB::select()
					->from('games_occupations')
					->join('occupations_master', 'inner')
					->on('games_occupations.card_id', '=', 'occupations_master.card_id')
					->where('game_id', '=', $game_id)
					->and_where('hands_order', '=', $hands_order)
					->and_where('picked_order', '=', null);
		return $query->execute()->as_array();
	}
}