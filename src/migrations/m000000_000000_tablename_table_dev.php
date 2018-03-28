<?php

use yozh\base\components\Migration;
use yozh\base\components\db\Schema;

/**
 * Class m180305_040759_tablename_table_dev
 */
class m000000_000000_tablename_table_dev extends Migration
{
	protected static $_table = 'tablename';
	protected static $_columns = [];
	
	/**
	 * {@inheritdoc}
	 */
	
	public function safeUp( $params = [] )
	{
				
		static::$_columns = [
			'id' => $this->primaryKey(),
		];
		
		parent::safeUp([
			'mode' => self::ALTER_MODE_IGNORE,
		]);
		
	}
	
}
