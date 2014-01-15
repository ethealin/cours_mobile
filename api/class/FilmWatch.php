<?php

class FilmWatch{

	//need to be in protected but i ve a probleme with the protected
	public $id;
	public $id_users;
	public $id_films;

	public function __construct($contenu){
		extract($contenu);
		$this->id = $id;
		$this->id_users = $id_users;
		$this->id_films = $id_films;
	}

	//get all films
	public static function findAll($db)
	{
		$contents = array();
		$q = $db->query('SELECT * FROM `films_watch` ORDER BY `id` DESC;');
		while ($datas = $q->fetch(PDO::FETCH_ASSOC))
		{
			$datas['id'] = (int) $datas['id'];
			$contents[] = new FilmWatch($datas);
		}
		return $contents;
	}

	public static function findOne($id_films,$id_users,$db)//return a FilmWatch object
	{

		$data = $db->query("SELECT * FROM `films_watch` WHERE `id_films` = $id_films AND `id_users` = $id_users;")->fetch(PDO::FETCH_ASSOC);
		$data['id'] = (int) $data['id'];
		$data['id_films'] = (int) $data['id_films'];
		$data['id_users'] = (int) $data['id_users'];
		return $data = new FilmWatch($data);
	}	
	
	public static function create($data,$db)
	{
		extract($data);
		return $db->exec("INSERT INTO `films_watch` (`id`,`id_users`,`id_films`) VALUES ('','$id_users','$id_films');");
	}

	public function delete($film_watch,$db)
	{
		$id = $film_watch->getId();
		$query = "DELETE FROM `films_watch` WHERE `id` = $id";
		return $db->exec($query);
	}
	//getters & setters
	public function getId(){return $this->id;}
	public function getId_users(){return $this->id_users;}
	public function getId_films(){return $this->id_films;}

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
	public function setId_users($id_users)
	{
		if (!is_string ($id_users))
		{
			trigger_error("Id_users must be a string \n", E_USER_WARNING);
			return;
		}
		$this->id_users = $id_users;
	}
	public function setId_films($id_films)
	{
		if (!is_string ($id_films))
		{
			trigger_error("Id_films must be a string \n", E_USER_WARNING);
			return;
		}
		$this->id_films = $id_films;
	}
}