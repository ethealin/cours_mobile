<?php

class User{

	//need to be in protected but i ve a probleme with the protected
	public $id;
	public $login;
	public $email;
	public $password;
	public $token;
	public $admin;

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

	//test if you are an admin based on access_token
	public static function testAdmin($access_token,$db)
	{
		$access_token = mysql_real_escape_string($access_token);
		$result = $db->query("SELECT `admin` FROM `users` WHERE `token` = '$access_token';")->fetch(PDO::FETCH_ASSOC);
		if($result['admin'] == 0)
			return false;
		else
			return true;
	}

	//test if your token is in the database
	public static function testUser($access_token,$db)//return user's id
	{
		$access_token = mysql_real_escape_string($access_token);
		$result = $db->query("SELECT `id` FROM `users` WHERE `token` = '$access_token';")->fetch(PDO::FETCH_ASSOC);
		return $result['id'];
	}

	//get all users
	public static function findAll($db)
	{
		$contents = array();
		$q = $db->query('SELECT * FROM `users` ORDER BY `id` DESC;');
		while ($datas = $q->fetch(PDO::FETCH_ASSOC))
		{
			$datas['id'] = (int) $datas['id'];
			$datas['admin'] = (int) $datas['admin'];
			$contents[] = new User($datas);
		}
		return $contents;
	}
	
	public function update(User $data,$db)
	{
		$rq = 'UPDATE `users` SET ';
		if($data->login != 'none')
		{
			$login = mysql_real_escape_string($data->login);
			$rq.="login = '$login',";
		}
		if($data->email != 'none')
		{
			$email = mysql_real_escape_string($data->email);
			$rq.="email = '$email',";
		}
		if($data->password != 'none')
		{
			$password = mysql_real_escape_string($data->password);
			$rq.="password = '$password',";
		}
		if($data->admin != -1)
		{
			$admin = mysql_real_escape_string($data->admin);
			$rq.="admin = $admin,";
		}
		if(substr($rq, -1) == ',')
			$rq = substr($rq,0,strlen($rq)-1);
		$rq.=" WHERE id = ".$data->id.";";
		return $db->exec($rq);
	}
	//getters & setters
	public function getId(){return $this->id;}
	public function getLogin(){return $this->login;}
	public function getEmail(){return $this->email;}
	public function getPassword(){return $this->password;}
	public function getToken(){return $this->token;}
	public function getAdmin(){return $this->admin;}

	public function setId($id)
	{
		if (!is_int ($id))
		{
			trigger_error("ids must be int \n", E_USER_WARNING);
			if($id < 0)
			{
				trigger_error("id must be > 0 \n", E_USER_WARNING);
				return;
			}
		}
		$this->id = $id;
	}
	public function setLogin($login)
	{
		if (!is_string ($login))
		{
			trigger_error("Login must be a string \n", E_USER_WARNING);
			return;
		}
		$this->login = $login;
	}
	public function setEmail($email)
	{
		if (!is_string ($email))
		{
			trigger_error("Email must be a string \n", E_USER_WARNING);
			return;
		}
		$this->email = $email;
	}
	public function setPassword($password)
	{
		if (!is_string ($password))
		{
			trigger_error("Password must be a string \n", E_USER_WARNING);
			return;
		}
		$this->password = $password;
	}
	public function setToken($token)
	{
		if (!is_string ($token))
		{
			trigger_error("Token must be a string \n", E_USER_WARNING);
			return;
		}
		$this->token = $token;
	}
	public function setAdmin($admin)
	{
		if (!is_int ($admin))
		{
			trigger_error("Admin must be an int \n", E_USER_WARNING);
			if($admin < 0 || $admin > 1)
			{
				trigger_error("id must be 0 or 1 \n", E_USER_WARNING);
				return;
			}
		}
		$this->admin = $admin;
	}
}