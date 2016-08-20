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

    /**
     * The model dependency injection container.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
	protected $model;

	/*
    |--------------------------------------------------------------------------
    | REST METHODS
    |--------------------------------------------------------------------------
    */

    #GET

    /**
     * Execute getAll function from $model.
     *
     * @return array
     */
	public function getAll()
	{
		return $this->wrapper(
			$this->model->getAll()
		);
	}

    /**
     * Execute getOne function from $model.
     *
     * @param  int  $id
     * @return array
     */
	public function getOne($id)
	{
		return $this->wrapper(
			$this->model->getOne($id)
		);
	}

	#POST

    /**
     * Execute postNew function from $model.
     *
     * @return array
     */
	public function postNew()
	{
		return $this->wrapper(
			$this->model->postNew()
		);
	}

	#PUT

    /**
     * Execute putUpdate function from $model.
     *
     * @param  int  $id
     * @return array
     */
	public function putUpdate($id)
	{
		$model = $this->model->find($id);

		if($model){
			$model = $model->putUpdate();
		}

		return $this->wrapper($model);
	}

	#DELETE

    /**
     * Execute deleteRecord function from $model.
     *
     * @param  int  $id
     * @return array
     */
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

    /**
     * Wrap and format the $model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return array
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