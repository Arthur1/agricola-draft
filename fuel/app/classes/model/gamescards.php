<?php
class Model_GamesCards
{
	protected static $table_name = '';
	protected static $master_table_name = '';

	public static function create($game_id, $cards_chunk)
	{
		$columns = [
			'game_id',
			'hands_order',
			'card_id',
			'picked_player_order',
			'picked_order',
		];
		$query = DB::insert(static::$table_name)->columns($columns);
		$num = count($cards_chunk);
		for ($i = 0; $i < $num; $i++) {
			foreach ($cards_chunk[$i] as $card) {
				$query->values([$game_id, $i + 1, $card['card_id'], null, null]);
			}
		}
		$query->execute();
	}

	public static function get_picked_hands($game_id, $player_order)
	{
		$query = DB::select()
					->from(static::$table_name)
					->join(static::$master_table_name, 'inner')
					->on(static::$table_name.'.card_id', '=', static::$master_table_name.'.card_id')
					->where('game_id', '=', $game_id)
					->and_where('picked_player_order', '=', $player_order);
		return $query->execute()->as_array();
	}

	public static function get_picking_hands($game_id, $hands_order)
	{
		$query = DB::select()
					->from(static::$table_name)
					->join(static::$master_table_name, 'inner')
					->on(static::$table_name.'.card_id', '=', static::$master_table_name.'.card_id')
					->where('game_id', '=', $game_id)
					->and_where('hands_order', '=', $hands_order)
					->and_where('picked_order', '=', null);
		return $query->execute()->as_array();
	}

	public static function update_pick($game_id, $card_id, $picked_player_order, $picked_order)
	{
		$query = DB::update(static::$table_name)
					->set([
						'picked_player_order' => $picked_player_order,
						'picked_order' => $picked_order,
					])
					->where('game_id', '=', $game_id)
					->and_where('card_id', '=', $card_id);
		$query->execute();
	}

	public static function get_all_by_game_id($game_id)
	{
		$query = DB::select()
					->from(static::$table_name)
					->join(static::$master_table_name, 'inner')
					->on(static::$table_name.'.card_id', '=', static::$master_table_name.'.card_id')
					->where('game_id', '=', $game_id)
					->and_where('picked_player_order', '!=', null)
					->order_by('picked_player_order', 'asc')
					->order_by('picked_order', 'asc');
		return $query->execute()->as_array();
	}
}