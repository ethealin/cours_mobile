<?php

class DbController{

	protected $host;
	protected $port;
	protected $database;
	protected $user;
	protected $password;

	public function hydrate(array $donnees)
	{
		foreach ($donnees as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}
	
	public function __construct($contenu){
		if(is_array($contenu))
		{
			$this->hydrate($contenu);
		}
	}

	public static function connect()
	{
		$db = new DB\SQL(
		    "mysql:host=localhost;port=3306;dbname=coursmobile",
		    'root',
		    ''
		);
		return $db;
	}
	public function getHost(){return $this->host;}
	public function getPort(){return $this->port;}
	public function getDatabase(){return $this->database;}
	public function getUser(){return $this->user;}
	public function getPassword(){return $this->password;}

	public function setHost($host){$this->id = $password;}
	public function setPort($port){$this->port = $port;}
	public function setDatabase($database){$this->database = $database;}
	public function setUser($user){$this->id = $user;}
	public function setPassword($password){$this->password = $password;}
}



