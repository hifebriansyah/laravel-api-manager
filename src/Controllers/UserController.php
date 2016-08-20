<?php

namespace MFebriansyah\LaravelAPIManager\Controllers;

class UserController extends MainController
{
    /**
     * Execute postLogIn function from $model.
     *
     * @return array
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
     * Execute postLogOut function from $model.
     *
     * @return array
     */
    public function postLogOut()
    {
        return $this->wrapper(
            $this->model->postLogOut()
        );
    }

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
}
