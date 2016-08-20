<?php

namespace MFebriansyah\LaravelAPIManager\Models;

use MFebriansyah\LaravelAPIManager\Traits\Hash;

class User extends MainModel
{
    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    #public

    protected $table = 'users';
    protected $hidden = ['password'];

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    public function getUniqueId($uniqueId = 0, $field = 'unique_id')
    {
        if(!$uniqueId){
            $count = $this->count();
            $uniqueId = rand(0, 100).$count.($uniqueId+1).NOW;
        }

        $model = $this->where($field, $uniqueId)->count();

        if($model > 0){
            $this->getUniqueId($uniqueId);
        }

        return $uniqueId;
    }

    #POST

    public function postLogIn()
    {
        $username = request()->input('username', request()->input('email'));
        $password = request()->input('password');

        $hide = array_diff($this->hide, ['auth_token']);

        $model = $this->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        $model = ($model) ? $model->setHidden($hide) : $model;

        $compare = ($model) ? Hash::compareHash($password, $model->password) : false;

        if($compare){
            request()->session()->put('user', $model->toArray());
            $model->last_login_at = TODAY;
            $model->auth_token = $this->getUniqueId(md5(NOW), 'auth_token');
            $model->save();
        }else{
            $model = null;
        }

        return $model;
    }

    public function postFbLogin()
    {
        $fb_id = request()->input('fb_id');
        $email = request()->input('email');

        $model = $this->where('email', $email)->whereNull('fb_id')->first();

        if ($model) {
            $model->fb_id = $fb_id;
            $model->is_email_validated = 1;
            $model->last_login_at = TODAY;
            $response = $model->save();
        } else {
            if(!$this->where('email', $email)->first() && $email){
                $this->postNew();
            }
        }

        $model = $this->where('email', $email)->where('fb_id', $fb_id)->first();

        if ($model) {
            request()->session()->put('user', $model->toArray());
            $model->last_login_at = TODAY;
            $model->auth_token = $this->getUniqueId(md5(NOW), 'auth_token');
            $model->save();
        }

        return $model;

    }

    public function postLogOut()
    {
        $model = null;
        $auth_token = request()->header('auth-token', request()->header('username'));

        // for client (android and ios)
        if ($auth_token) {

            $member = User::where('auth_token', $auth_token)->first();

            if ($member) {
                $member->auth_token = null;
                $member->save();
            }
        }

        // for web version
        if(request()->session()->has('user')){
            $model = $this->find(request()->session()->get('user')['id']);
            $model->auth_token = null;
            $model->save();
        }

        request()->session()->forget('user');

        return $model;
    }

    #LOG

    public function getLogOnData()
    {
        $model = $this->getAPILogOnData();

        if(!$model) {
            $model = $this->getHTTPLogOnData();
        }

        return $model;
    }

    private function getHTTPLogOnData()
    {
        $user = null;

        if (request()->session()->has('user')){
            $user = request()->session()->get('user');
            $user = User::find($user['id']);
        }

        return $user;
    }

    private function getAPILogOnData()
    {
        $auth_token = request()->header('auth-token');

        $user = $this->where(\DB::raw('(
                (username = "'.$auth_token.'" or email = "'.$auth_token.'")
                or (auth_token = "'.$auth_token.'" and auth_token is not null)
            )'), true)
            ->first();

        return $user;
    }
}
