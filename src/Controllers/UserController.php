<?php

namespace MFebriansyah\LaravelAPIManager\Controllers;

use MFebriansyah\LaravelAPIManager\Controllers\MainController;

class UserController extends MainController
{
	public function postLogIn()
	{
		$response['status'] = INVALID_CREDENTIAL;
		$response['messages'] = "Invalid credentials!";

		$model = request()->has('fb_id')
			? $this->model->postFbLogIn()
			: $model = $this->model->postLogIn();		

		if($model){
			$response = $this->wrapper($model);
		};

		return $response;
	}

	public function postLogOut()
	{
		return $this->wrapper(
			$this->model->postLogOut()
		);
	}

	public function postNew()
	{
		return $this->wrapper(
			$this->model->postNew()
		);
	}
}