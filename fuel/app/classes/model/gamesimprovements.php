<?php
class Model_GamesImprovements extends Model_GamesCards
{
	// override
	protected static $table_name = 'games_improvements';
	protected static $master_table_name = 'improvements_master';
}