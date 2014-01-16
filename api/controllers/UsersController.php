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

	public function actionFindOne()
	{
		$data = $_GET;
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
		$id = F3::get('PARAMS.id');
		$user = User::findOne($id,$db);
		Api::response(200,array('data'=>$user));
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
		if($return != 0)
			Api::response(200,array('data'=>'the updating request have been done successfuly'));
		else
			Api::response(400,array('error'=>'the updating request didn\'t success, please try again with other parameters'));
	}

	public function actionDelete()
	{
		$data = array_map('mysql_real_escape_string', $_GET);
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
		$idUser = (int) F3::get('PARAMS.id');
		$user = new User();
		$user->setId($idUser);
		$return = $user->delete($user,$db);
		if($return)
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
			$user = User::login($email,$password,$db);
			if(gettype($user) != 'integer')
				Api::response(200, array('data'=>'Login success, access_token = '.$user->getToken()));
			else
				Api::response(400, array('error'=>'Missing datas'));
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
			$datas = $_POST;
			foreach($datas as $key => $value)
			{
				$data[$key] = mysql_real_escape_string($value);
			}
			$user = User::subscribe($datas,$db);
			$data = 'Subscribed with this user token : '.$user->getToken();
			Api::response(200, array('data'=>$data));
		}
		else
		{
			Api::response(400, array('error'=>'Missing datas'));
		}
	}
}