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
		'RA' => 'Aデッキ(リバイズド基本)',
		'RB' => 'Bデッキ(リバイズド基本)',
		'LR' => 'Lデッキ(リバイズド基本)',
		'5A' => 'Aデッキ(リバイズド5-6人拡張)',
		'5B' => 'Bデッキ(リバイズド5-6人拡張)',
		'5C' => 'Cデッキ(リバイズド5-6人拡張)',
		'5D' => 'Dデッキ(リバイズド5-6人拡張)',
		'L5' => 'Lデッキ(リバイズド5-6人拡張)',
		'A' => 'Artifexデッキ',
		'B' => 'Bubulcusデッキ',
		'WCR' => 'Cデッキ(Wizkids赤拡張)',
		'WCB' => 'Cデッキ(Wizkids青拡張)',
		'WCW' => 'Cデッキ(Wizkids白拡張)',
		'WCP' => 'Cデッキ(Wizkids紫拡張)',
		'WCG' => 'Cデッキ(Wizkids緑拡張)',
		'WCY' => 'Cデッキ(Wizkids黄拡張)',
		'WDR' => 'Dデッキ(Wizkids赤拡張)',
		'WDB' => 'Dデッキ(Wizkids青拡張)',
		'WDW' => 'Dデッキ(Wizkids白拡張)',
		'WDP' => 'Dデッキ(Wizkids紫拡張)',
		'WDG' => 'Dデッキ(Wizkids緑拡張)',
		'WDY' => 'Dデッキ(Wizkids黄拡張)',
		'L17' => 'Lデッキ(Spiel 17\')',
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
			case 3:
				$decks = ['RA', 'RB', 'LR', '5A', '5B', '5C', '5D', 'L5'];
				break;
			case 4:
				$decks = ['RA', 'RB', 'LR', '5A', '5B', '5C', '5D', 'L5', 'A', 'B', 'WCR', 'WCB', 'WCW', 'WCP', 'WCG', 'WCY', 'WDR', 'WDB', 'WDW', 'WDP', 'WDG', 'WDY'];
				break;
		}
		$query = DB::select('card_id')
					->from('improvements_master')
					->where('deck', 'IN', $decks)
					->cached(60 * 60 * 12);
		return $query->execute()->as_array();
	}
}