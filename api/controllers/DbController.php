<?php

class DbController{

	public static function connect()
	{
		$db = new DB\SQL(
		    "mysql:host=localhost;port=3306;dbname=coursmobile",
		    'root',
		    '',
		    array(PDO::MYSQL_ATTR_INIT_COMMAND =>  "SET NAMES 'UTF8'")
		);
		return $db;
	}
}



