<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiFebriansyah\LaravelAPIManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Abstraction for REST API Model.
 *
 * @author Muhammad Febriansyah <hifebriansyah@gmail.com>
 *
 * @since Abstract Class available since Release 1.0.0
 */
abstract class MainModel extends Model
{
    /*
    |--------------------------------------------------------------------------
    | PROPERTIES
    |--------------------------------------------------------------------------
    */

    /* PUBLIC */

    /**
     * Path of images folder.
     *
     * @var string
     *
     * @since Property available since Release 1.0.0
     */
    public $imagesFolder = 'embed';

    /**
     * Images resolutions.
     *
     * @var array
     *
     * @since Property available since Release 1.0.0
     */
    public $imageResolutions = [
        'on_demand' => '{$1}/{$2}',
    ];

    /**
     * All atrributes that will be hidden.
     *
     * @var array
     *
     * @since Property available since Release 1.0.0
     */
    public $hide = [];

    /**
     * All atrributes that will be added.
     *
     * @var array
     *
     * @since Property available since Release 1.0.0
     */
    public $add = [];

    /**
     * Validations rules for model attributes.
     *
     * @var array
     *
     * @since Property available since Release 1.0.0
     */
    public $rules = [];

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    /* GET */

    /**
     * Get all records.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * @since Method available since Release 1.0.0
     */
    public function getAll()
    {
        return $this->setAppends($this->add)
            ->setHidden($this->hide)
            ->transform($this->filter());
    }

    /**
     * Get record by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     *
     * @since Method available since Release 1.0.0
     */
    public function getOne($id)
    {
        return $this->setHidden($this->hide)
            ->setAppends($this->add)
            ->one($id);
    }

    /* DELETE */

    /**
     * Remove record.
     *
     * @return $this
     *
     * @since Method available since Release 1.0.0
     */
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

    /**
     * Set filter and pagination for the query.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @since Method available since Release 1.0.0
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

    /**
     * Transform the results by append an hide model attributes.
     *
     * @param \Illuminate\Database\Eloquent\Collection $model
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * @since Method available since Release 1.0.0
     */
    public function transform($model)
    {
        if (!(new User())->getLogOnData()) {
            $this->appends = array_diff($this->appends, ['logged_on_user']);
        }

        foreach ($model as $key => $value) {
            $value->append($this->appends)->setHidden($this->hidden);
        }

        return $model;
    }

    /**
     * Transform the result by append an hide model attributes.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     *
     * @since Method available since Release 1.0.0
     */
    public function one($id = null)
    {
        if (!(new User())->getLogOnData()) {
            $this->appends = array_diff($this->appends, ['logged_on_user']);
        }

        if ($id) {
            $model = $this->find($id);
        } else {
            $model = $this->first();
        }

        $model = ($model) ? $model->append($this->appends)->setHidden($this->hidden) : $model;

        return $model;
    }

    /**
     * Append formatted image attribute.
     *
     * @param string $value
     * @param string $fieldName
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function getImageAttribute($value, $fieldName = 'image_url')
    {
        if ($this->$fieldName) {
            $images['original'] = url($this->imagesFolder.'/'.$this->$fieldName.'?index='.INDEX);

            foreach ($this->imageResolutions as $name => $value) {
                $images[$name] = url($this->imagesFolder.'/'.$value.'/'.$this->$fieldName.'?index='.INDEX);
            }
        } else {
            $images['original'] = url($this->imagesFolder.'/not-found?index='.INDEX);

            foreach ($this->imageResolutions as $name => $value) {
                $images[$name] = url($this->imagesFolder.'/'.$value.'/not-found?index='.INDEX);
            }
        }

        return $images;
    }

    /**
     * Insert new record only after success validation.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     *
     * @since Method available since Release 1.0.0
     */
    public function validSave()
    {
        $validator = validator()->make(request()->all(), $this->rules);

        if ($validator->fails()) {
            $model['errors'] = $validator->errors();
        } else {
            $this->save();

            $model = $this->setHidden($this->hide)
                ->setAppends($this->add)
                ->one($this->id);
        }

        return $model;
    }
}
