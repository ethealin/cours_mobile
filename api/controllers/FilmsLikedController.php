<?php

class FilmsLikedController{

	//get all films liked
	public function actionFindAll()
	{
		$db = DbController::connect();
		$data = FilmLiked::findAll($db);
		if(!empty($data))
			Api::response(200,array('data',$data));
		else
			Api::response(400,array('error'=>'your request has failed'));
	}

	public function actionDelete()
	{
		$data = array_map('mysql_real_escape_string', $_GET);
		if(isset($data['access_token']))
		{	
			$db = DbController::connect();
			$idUser = User::testUser($data['access_token'],$db);
			$film = FilmLiked::findOne(F3::get('PARAMS.id_film'),$idUser,$db);
			if($film->getId_users() != $idUser)
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't update users !"));
				exit;
			}
		}
		else
			Api::response(403, array('error'=>"You are not an administrator, you can't update users !"));
		$return = $film->delete($film,$db);
		if($return == 1)
			Api::response(200,array('data'=>'the deleting request have been done successfuly'));
		else
			Api::response(400,array('error'=>'the deleting request didn\'t success, please try again with other parameters'));
	}

	public function actionCreate(){//to use it, u have to fill the user's access token who is linked with id_user
		if(isset($_GET['access_token']))
		{
			$db = DbController::connect();
			if($_POST['id_users'] != User::testUser($_GET['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not the right user, please log in."));
				exit;
			}
			else if()
		}
		else
			{Api::response(403, array('error'=>"You have to be logged by an acces token"));exit;}
		if(isset($_POST['id_users']) && isset($_POST['id_films']))
		{
			if(empty(FilmWatched::findOne($_POST['id_users']),$_POST['id_films'])),$db)
			{
				Api::response(400,array('error'=>'this user did\t watch this movie, he can\' t like it !!'));
				exit:
			}
			$data = array('id_users'=>mysql_real_escape_string($_POST['id_users']),'id_films'=>mysql_real_escape_string($_POST['id_films']));
			$filmLiked = FilmLiked::create($data,$db);
			if($filmLiked == 1)
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