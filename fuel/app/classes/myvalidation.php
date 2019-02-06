<?php
class MyValidation
{
	public static function _validation_exists_user($val)
	{
		$query = DB::select('username')
					->from('users')
					->where('username', '=', $val);
		$result = $query->execute()->as_array();
		return count($result) === 1;
	}

	public static function _validation_array_unique($val)
	{
		$val = (array) $val;
		$array_unique = array_unique($val);
		return count($val) === count ($array_unique);
	}
}