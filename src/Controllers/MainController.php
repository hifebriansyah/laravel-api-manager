<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiFebriansyah\LaravelAPIManager\Controllers;

use App\Http\Controllers\Controller;

/**
 * Abstraction for REST API Controller.
 *
 * You need to do dependency injection,
 * from your model class into your REST API controller class,
 * before calling any of these function.
 *
 * @author Muhammad Febriansyah <hifebriansyah@gmail.com>
 *
 * @since Abstract Class available since Release 1.0.0
 */
abstract class MainController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * The model dependency injection container.
     *
     * @var \Illuminate\Database\Eloquent\Model
     *
     * @since Property available since Release 1.0.0
     */
    protected $model;

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    /* GET */

    /**
     * Execute getAll method from model class.
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function getAll()
    {
        return $this->wrapper(
            $this->model->getAll()
        );
    }

    /**
     * Execute getOne method from model class.
     *
     * @param int $id
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function getOne($id)
    {
        return $this->wrapper(
            $this->model->getOne($id)
        );
    }

    /* POST */

    /**
     * Execute postNew method from model class.
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function postNew()
    {
        return $this->wrapper(
            $this->model->postNew()
        );
    }

    /* PUT */

    /**
     * Execute putUpdate method from model class.
     *
     * @param int $id
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function putUpdate($id)
    {
        $model = $this->model->find($id);

        if ($model) {
            $model = $model->putUpdate();
        }

        return $this->wrapper($model);
    }

    /* DELETE */

    /**
     * Execute deleteRecord method from model class.
     *
     * @param int $id
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function deleteRecord($id)
    {
        $model = $this->model->select('status_id')->find($id);

        if ($model) {
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
     * Wrap and format the model result.
     *
     * Set status to blank if no result found.
     * Set status to succsess if result found.
     * Set status to validation error if errors found.
     * wrap results with "data" as index if model return is collection.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function wrapper($model)
    {
        $wrapper['status'] = BLANK;

        if ($model) {
            $model = is_array($model) ? $model : $model->toArray();
            $model = !isset($model[0]) ? $model : ['data' => $model];
            $wrapper = array_merge($wrapper, $model);
            $wrapper['status'] = !isset($model['errors']) ? SUCCESS : VALIDATION_ERROR;
        }

        return $wrapper;
    }
}
