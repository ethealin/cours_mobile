<?php

class FilmsController{

	//get all films
	public function actionFindAll()
	{
		$db = DbController::connect();
		$data = Film::findAll($db);
		if(!empty($data))
			Api::response(200,array('data',$data));
		else
			Api::response(400,array('error'=>'your request has failed'));
	}

	public function actionFindOne()
	{
		$db = DbController::connect();
		$data = Film::findOne(F3::get('PARAMS.id'),$db);
		Api::response(200,array('data',$data));
	}


	public function actionUpdate()
	{
		$data = PUT::get();
		$data['access_token'] = $_GET['access_token'];
		if(isset($data['access_token']))
		{	
			$db = DbController::connect();
			if(!User::testAdmin($data['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't update users !"));
				exit;
			}
		}
		else
			Api::response(403, array('error'=>"You are not an administrator, you can't update users !"));
		$idFilm = F3::get('PARAMS.id');
		$data['id'] = (int) $idFilm;
		if(!isset($data['title']))
			$data['title'] = 'none';
		if(!isset($data['abstract']))
			$data['abstract'] = 'none';
		$data['created_at'] = 'none';
		$film = new Film($data);
		$return = $film->update($film,$db);
		if($return == 1)
			Api::response(200,array('data'=>'the updating request have been done successfuly'));
		else
			Api::response(400,array('error'=>'the updating request didn\'t success, please try again with other parameters'));
	}

	public function actionDelete()
	{
		$data = array_map('mysql_real_escape_string', $_GET);
		if(isset($data['access_token']))//test if admin
		{	
			$db = DbController::connect();
			if(!User::testAdmin($data['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't update users !"));
				exit;
			}
		}
		else
			Api::response(403, array('error'=>"You are not an administrator, you can't update users !"));
		$idFilm = F3::get('PARAMS.id');
		
		$film = Film::findOne($idFilm,$db);
		$return = $film->delete($film,$db);
		if($return == 1)
			Api::response(200,array('data'=>'the deleting request have been done successfuly'));
		else
			Api::response(400,array('error'=>'the deleting request didn\'t success, please try again with other parameters'));
	}

	public function actionCreate(){
		if(isset($_GET['access_token']))
		{
			$db = DbController::connect();
			if(!User::testAdmin($_GET['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't create films !"));
				exit;
			}
		}
		else{Api::response(403, array('error'=>"You are not an administrator, you can't create films !"));exit;}
		if(isset($_POST['title']) && isset($_POST['abstract']))
		{
			$data = array('title'=>mysql_real_escape_string($_POST['title']),'abstract'=>mysql_real_escape_string($_POST['abstract']));
			$film = Film::create($data,$db);
			if($film == 1)
				Api::response(200,array('data'=>'the creating request have been done successfuly'));
			else
				Api::response(400,array('error'=>'the creating request didn\'t success, please try again with other parameters'));
		}
		else
		{
			Api::response(400, array('error'=>'Missing datas'));
		}
	}
}