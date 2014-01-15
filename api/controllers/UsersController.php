<?php

class UsersController{

	//get all users
	public function actionFindAll()
	{
		if(isset($_GET['access_token']))//test if admin
		{	
			$db = DbController::connect();
			if(!User::testAdmin($_GET['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you don't have permission to do this."));
				exit;
			}
		}
		else
			{Api::response(403, array('error'=>"You are not an administrator, you don't have permission to do this."));exit;}

		$data = User::findAll($db);
		if(!empty($data))
			Api::response(200,array('data',$data));
		else
			Api::response(400,array('error'=>'your request has failed'));
	}

	public function actionUpdate()
	{
		$data = PUT::get('access_token');
		
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
		$idUser = F3::get('PARAMS.id');
		$data['id'] = (int) $idUser;
		if(!isset($data['token']))
			$data['token'] = 'none';
		if(!isset($data['admin']))
			$data['admin'] = -1;
		if(!isset($data['password']))
			$data['password'] = 'none';
		if(!isset($data['login']))
			$data['login'] = 'none';
		if(!isset($data['email']))
			$data['email'] = 'none';
		$user = new User($data);
		$return = $user->update($user,$db);
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
		$idUser = F3::get('PARAMS.id');
		$query = "DELETE FROM `users` WHERE `id` = $idUser";
		if($db->exec($query))
			Api::response(200,array('data'=>'the deleting request have been done successfuly'));
		else
			Api::response(400,array('error'=>'the deleting request didn\'t success, please try again with other parameters'));
	}

	// sign in / sign up methods
	public function actionLogin(){
		if(isset($_GET['email']) && isset($_GET['password']))
		{
			$email = mysql_real_escape_string($_GET['email']);
			$password = md5(mysql_real_escape_string($_GET['password']));
			$db = DbController::connect();
			$result = $db->query("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password';")->fetch(PDO::FETCH_ASSOC);
			Api::response(200, array('data'=>'Login success, access_token = '.$result['token']));
		}
		else
		{
			Api::response(400, array('error'=>'Missing datas'));
		}
	}

	public function actionSubscribe(){
		if(isset($_POST['access_token']))
		{
			$db = DbController::connect();
			if(!User::testAdmin($_POST['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));
				exit;
			}
		}
		else{Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));exit;}

		if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['login']))
		{
			$login = mysql_real_escape_string($_POST['login']);
			$email = mysql_real_escape_string($_POST['email']);
			$password = md5(mysql_real_escape_string($_POST['password']));
			$result = $db->query('SELECT MAX(id) as i FROM `users`;')->fetch(PDO::FETCH_ASSOC);
			$token = md5(1 + (int) $result['i']);
			if(!isset($data['admin']))
				$db->exec("INSERT INTO `users` (`id`,`login`,`email`,`password`,`token`) VALUES ('','$login','$email','$password','$token')");
			else
				$db->exec("INSERT INTO `users` (`id`,`login`,`email`,`password`,`token`,`admin`) VALUES ('','$login','$email','$password','$token','1')");
			$data = 'Subscribed with this token : '.$token;
			Api::response(200, array('data'=>$data));
		}
		else
		{
			Api::response(400, array('error'=>'Missing datas'));
		}
	}
}