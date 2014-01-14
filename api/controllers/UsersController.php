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
		else{Api::response(403, array('error'=>"You are not an administrator, you don't have permission to do this."));exit;}

		$db = DbController::connect();
		$data = User::findAll($db);
		Api::response(200,array('data',$data));
	}

	public function actionUpdate()
	{
		$data = PUT::get('access_token');
		
		if(isset($data['access_token']))//test if admin
		{	
			$db = DbController::connect();
			if(!User::testAdmin($data['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));
				exit;
			}
		}
		else
			Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));
		$idUser = F3::get('PARAMS.id');
		$rq = 'UPDATE `users` SET ';
		if(isset($data['login']))
		{
			$login = mysql_real_escape_string($data['login']);
			$rq.="login = '$login',";
		}
		if(isset($data['email']))
		{
			$email = mysql_real_escape_string($data['email']);
			$rq.="email = '$email',";
		}
		if(isset($data['password']))
		{
			$password = mysql_real_escape_string($data['password']);
			$rq.="password = '$password',";
		}
		if(isset($data['admin']))
		{
			$admin = mysql_real_escape_string($data['admin']);
			$rq.="admin = $admin,";
		}
		if(substr($rq, -1) == ',')
			$rq = substr($rq,0,strlen($rq)-1);
		$rq.=" WHERE id = $idUser;";
		if(!$db->exec($rq))
			Api::response(200,array('data'=>'the updating request have been done successfuly'));
		else
			Api::response(400,array('error'=>'the updating request didn\' success, please try again with other parameters'));
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
		if(isset($data['access_token']))
		{
			$db = DbController::connect();
			if(!User::testAdmin($data['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));
				exit;
			}
		}
		else{Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));exit;}

		if(isset($data['email']) && isset($data['password']) && isset($data['login']))
		{
			$login = mysql_real_escape_string($data['login']);
			$email = mysql_real_escape_string($data['email']);
			$password = md5(mysql_real_escape_string($data['password']));
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