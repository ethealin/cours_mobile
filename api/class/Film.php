<?php

class Film{

	//need to be in protected but i ve a probleme with the protected
	public $id;
	public $title;
	public $created_at;
	public $abstract;

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

	public function __construct($contenu  = false){
		if(is_array($contenu))
		{
			$this->hydrate($contenu);
		}
	}

	//get all films
	public static function findAll($db)
	{
		$contents = array();
		$q = $db->query('SELECT * FROM `films` ORDER BY `id` DESC;');
		while ($datas = $q->fetch(PDO::FETCH_ASSOC))
		{
			$datas['id'] = (int) $datas['id'];
			$contents[] = new Film($datas);
		}
		return $contents;
	}

	public static function findOne($id,$db)
	{
		$data = $db->query("SELECT * FROM `films` WHERE `id` = $id;")->fetch(PDO::FETCH_ASSOC);
		if(empty($data))
		{
			Api::response(400,array('error'=>'this id doesn\'t exist.'));exit;
		}
		$data['id'] = (int) $data['id'];
		return $data = new Film($data);
	}	
	
	public static function create($data,$db)
	{
		extract($data);
		return $db->exec("INSERT INTO `films` (`id`,`title`,`created_at`,`abstract`) VALUES ('','$title',now(),'$abstract');");
	}

	public function update(Film $data,$db)
	{
		$rq = 'UPDATE `films` SET ';
		if($data->getTitle() != 'none')
		{
			$title = mysql_real_escape_string($data->getTitle());
			$rq.="title = '$title',";
		}
		if($data->getAbstract() != 'none')
		{
			$abstract = mysql_real_escape_string($data->getAbstract());
			$rq.="abstract = '$abstract',";
		}
		if(substr($rq, -1) == ',')
			$rq = substr($rq,0,strlen($rq)-1);
		$rq.=" WHERE id = ".$data->getId().";";
		return $db->exec($rq);
	}

	public function delete($film,$db)
	{
		$id = $film->getId();
		$query = "DELETE FROM `films` WHERE `id` = $id";
		return $db->exec($query);
	}
	//getters & setters
	public function getId(){return $this->id;}
	public function getTitle(){return $this->title;}
	public function getCreated_at(){return $this->created_at;}
	public function getAbstract(){return $this->abstract;}

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
	public function setTitle($title)
	{
		if (!is_string ($title))
		{
			trigger_error("Title must be a string \n", E_USER_WARNING);
			return;
		}
		$this->title = $title;
	}
	public function setCreated_at($created_at)
	{
		if (!is_string ($created_at))
		{
			trigger_error("Created_at must be a string \n", E_USER_WARNING);
			return;
		}
		$this->created_at = $created_at;
	}
	public function setAbstract($abstract)
	{
		if (!is_string ($abstract))
		{
			trigger_error("Abstract must be a string \n", E_USER_WARNING);
			return;
		}
		$this->abstract = $abstract;
	}
}