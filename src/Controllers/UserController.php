<?php

namespace MFebriansyah\LaravelAPIManager\Controllers;

use MFebriansyah\LaravelAPIManager\Controllers\MainController;

class UserController extends MainController
{
	public function postLogIn()
	{
		return $this->wrapper(
			$this->model->postLogIn()
		);
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