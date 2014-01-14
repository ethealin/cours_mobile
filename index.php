<?php
$f3=require('framework/base.php');
$f3->set('DEBUG',3);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

$f3->config('api/configs/config.ini');
$f3->config('api/configs/routes.ini');


/*
//test route POST /v1/subscribe
$f3->route('GET /v1',
	function($f3) {
		$f3->mock('POST /v1/subscribe',array('access_token'=>'e4da3b7fbbce2345d7772b0674a318d5','email'=>'test@b.fr','password'=>'b','login'=>'p'));  // set the route that f3 will run
	}
);*/

$f3->run();
