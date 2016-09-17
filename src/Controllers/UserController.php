<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiFebriansyah\LaravelAPIManager\Controllers;

/**
 * Main class for User Controller.
 *
 * You need to do dependency injection,
 * from your model class into your REST API controller class,
 * before calling any of these function.
 *
 * @author Muhammad Febriansyah <hifebriansyah@gmail.com>
 *
 * @since Class available since Release 1.0.0
 */
class UserController extends MainController
{
    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Execute postLogIn function from model class.
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function postLogIn()
    {
        $response['status'] = INVALID_CREDENTIAL;
        $response['messages'] = 'Invalid credentials!';

        $model = request()->has('fb_id')
            ? $this->model->postFbLogIn()
            : $model = $this->model->postLogIn();

        if ($model) {
            $response = $this->wrapper($model);
        }

        return $response;
    }

    /**
     * Execute postLogOut function from model class.
     *
     * @return array
     *
     * @since Method available since Release 1.0.0
     */
    public function postLogOut()
    {
        return $this->wrapper(
            $this->model->postLogOut()
        );
    }

    /**
     * Execute postNew function from model class.
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
}
