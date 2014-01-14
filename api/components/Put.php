<?php

class Put{

	public static function get(){

		$data = array();

		if(F3::get('VERB') == 'PUT'){

			$put = explode('&', F3::get('BODY'));
			if(empty($put[0])){
				return $data;
			}

			foreach($put as $item){
				$item = explode('=', $item, 2);
				$data[$item[0]] = urldecode($item[1]);
			}
		}

		return $data;
	}
}