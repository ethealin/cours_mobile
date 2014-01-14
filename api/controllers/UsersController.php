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

	public function actionUpdate()//à faire
	{
		if(isset($_GET['access_token']))//test if admin
		{	
			$db = DbController::connect();
			if(!User::testAdmin($_GET['access_token'],$db))
			{
				Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));
				exit;
			}
		}
		else{Api::response(403, array('error'=>"You are not an administrator, you can't create users !"));exit;}
		$idUser = F3::get('PARAMS.id');
		$rq = 'UPDATE `users` SET (';
		if(isset($_POST['login']))
		{
			$login = mysql_real_escape_string($_POST['login']);
			$rq.="`login` = '$login', ";
		}
		if(isset($_POST['email']))
		{
			$email = mysql_real_escape_string($_POST['email']);
			$rq.="`email` = '$email' ,";
		}
		if(isset($_POST['password']))
		{
			$password = mysql_real_escape_string($_POST['password']);
			$rq.="`password` = '$password' ,";
		}
		if(isset($_POST['admin']))
		{
			$admin = mysql_real_escape_string($_POST['admin']);
			$rq.="`admin` = '$admin' ,";
		}
		if(substr($rq, -1,1) == ',')//à finir mercredu matin
			$rq = substr($rq,-1);
		$rq.=');';echo $rq;
		//$db->exec($rq);
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
			if(!isset($_POST['admin']))
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