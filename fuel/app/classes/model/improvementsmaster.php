<?php
class Model_ImprovementsMaster
{
	const DECK_LIST = [
		'E' => 'Eデッキ(旧版基本)',
		'I' => 'Iデッキ(旧版基本)',
		'K' => 'Kデッキ(旧版基本)',
		'G' => 'Gデッキ',
		'Z' => 'Zデッキ',
		'alpha' => 'αデッキ(WMデッキ)',
		'beta' => 'βデッキ(WMデッキ)',
		'gamma' => 'γデッキ(WMデッキ)',
		'delta' => 'δデッキ(WMデッキ)',
		'epsilon' => 'εデッキ(WMデッキ)',
		'CZ' => 'Čデッキ',
		'O' => 'Öデッキ',
		'P' => 'πデッキ',
		'WA' => 'WAデッキ',
		'FL' => 'FLデッキ',
		'FR' => 'FRデッキ',
		'NL' => 'NLデッキ',
	];

	public static function get_for_create_game($regulation_type)
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
					->from('improvements_master')
					->where('deck', 'IN', $decks)
					->cached(60 * 60 * 12);
		return $query->execute()->as_array();
	}
}