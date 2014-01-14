<?php

class FilmsController{

	public function __construct(){
	}

	public function actionFind(){
		Api::response(200, array('Get all films'));
	}

	public function actionCreate(){
		if(isset($_POST['name'])){
			$data = array('Create film with name ' . $_POST['name']);
			Api::response(200, $data);
		}
		else{
			Api::response(400, array('error'=>'Name is missing'));
		}
	}

	public function actionFindOne(){
		$data = array('Find one film with name: ' . F3::get('PARAMS.id'));
		Api::response(200, $data);
	}

	public function actionUpdate(){
		$data = array('Update film with name: ' . F3::get('PARAMS.id'));
		Api::response(200, $data);
	}

	public function actionDelete(){
		$data = array('Delete dilm with name: ' . F3::get('PARAMS.id'));
		Api::response(200, $data);
	}
}