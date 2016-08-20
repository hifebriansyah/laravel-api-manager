<?php

namespace MFebriansyah\LaravelAPIManager\Controllers;

use App\Http\Controllers\Controller;

abstract Class MainController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

	protected $model;

	/*
    |--------------------------------------------------------------------------
    | REST METHODS
    |--------------------------------------------------------------------------
    */

    #GET

	public function getAll()
	{
		return $this->wrapper(
			$this->model->getAll()
		);
	}

	public function getOne($id)
	{
		return $this->wrapper(
			$this->model->getOne($id)
		);
	}

	#POST

	public function postNew()
	{
		return $this->wrapper(
			$this->model->postNew()
		);
	}

	#PUT

	public function putUpdate($id)
	{
		$model = $this->model->find($id);

		if($model){
			$model = $model->putUpdate();
		}

		return $this->wrapper($model);
	}

	#DELETE

	public function deleteRecord($id)
	{
		$model = $this->model->select('status_id')->find($id);

		if($model){
			$model = $model->deleteRecord();
		}

		return $this->wrapper($model);
	}
	
	/*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

	public function wrapper($model)
	{
		$wrapper['status'] = BLANK;

		if($model){
			$model = is_array($model) ? $model : $model->toArray();
			$model = !isset($model[0]) ? $model : ['data' => $model];
			$wrapper = array_merge($wrapper, $model);

			$wrapper['status'] = !isset($model['errors']) ? SUCCESS : VALIDATION_ERRORS;
		}

		return $wrapper;
	}
}