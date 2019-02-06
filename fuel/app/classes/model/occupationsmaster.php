<?php
class Model_OccupationsMaster
{
	public static function get_for_create_game($regulation_type, $players_number)
	{
		switch ($regulation_type) {
			case 1:
				$decks = ['E', 'I', 'K'];
				break;
			case 2:
				$decks = ['E', 'I', 'K', 'G', 'Z', 'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'CZ', 'O', 'P', 'WA', 'FL', 'FR', 'NL'];
				break;
		}
		$query = DB::select('card_id')
					->from('occupations_master')
					->where('deck', 'IN', $decks)
					->where('category', '<=', $players_number)
					->cached(60 * 60 * 12);
		return $query->execute()->as_array();
	}
}