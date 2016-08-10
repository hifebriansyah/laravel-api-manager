<?php

namespace MFebriansyah\LaravelAPIManager\Models;

use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    public $imagesFolder = SERVER.'/embed';

    public $coverResolutions = [
        'on_demand' => '/{$1}/{$2}',
    ];

	/*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    #GET

    public function getAll()
    {
        $model = $this->setAppends($this->add)
            ->setHidden($this->hide)
            ->transform($this->filter());

        return $model;
    }

    public function getOne($id)
    {
        return $this->setHidden($this->hide)
            ->setAppends($this->add)
            ->one($id);
    }

    #DELETE

    public function deleteRecord()
    {
        $this->status_id = 0;
        $this->save();

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SORT & FILTERS
    |--------------------------------------------------------------------------
    */

    public function filter()
    {
        $limit = request()->input('limit', 15);
        $model = $this->paginate($limit);

        return $model;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

	public function transform($model)
	{
        if(!(new User)->getLogOnData()){
        	$this->appends = array_diff($this->appends, ['logged_on_user']);
        }

		foreach ($model as $key => $value) {
			$value->append($this->appends)->setHidden($this->hidden);
		}

		return $model;
	}

	public function one($id = null)
	{
        if(!(new User)->getLogOnData()){
        	$this->appends = array_diff($this->appends, ['logged_on_user']);
        }

		if($id){
			$model = $this->find($id);
		}else{
			$model = $this->first();
		}

		$model = ($model) ? $model->append($this->appends)->setHidden($this->hidden) : $model;

		return $model;
	}

    public function getImageAttribute($value, $fieldName = 'image_url')
    {
        if($this->$fieldName){
            $images['original'] = $this->imagesFolder.'/'.$this->$fieldName.'?index='.INDEX;

            foreach($this->coverResolutions as $name => $value){
                $images[$name] = $this->imagesFolder.$value.'/'.$this->$fieldName.'?index='.INDEX;
            }
        }else{
            $images['original'] = $this->imagesFolder.'/not-found?index='. INDEX;;

            foreach($this->coverResolutions as $name => $value){
                $images[$name] = $this->imagesFolder.$value.'/not-found?index='.INDEX;;
            }
        }

        return $images;
    }

    public function validSave(){
        $validator = validator()->make(request()->all(), $this->rules);

        if($validator->fails()){
            $model['errors'] = $validator->errors();
        }else{
            $this->save();

            $model = $this->setHidden($this->hide)
                ->setAppends($this->add)
                ->one($this->id);
        }

        return $model;
    }
}
