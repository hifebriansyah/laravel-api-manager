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

    // PROTECTED

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * All atrributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Generate unique id.
     *
     * @param int    $uniqueId
     * @param string $field
     *
     * @return int
     */
    public function getUniqueId($uniqueId = 0, $field = 'unique_id')
    {
        if (!$uniqueId) {
            $count = $this->count();
            $uniqueId = rand(0, 100).$count.($uniqueId + 1).NOW;
        }

        $model = $this->where($field, $uniqueId)->count();

        if ($model > 0) {
            $this->getUniqueId($uniqueId);
        }

        return $uniqueId;
    }

    // POST

    /**
     * Authenticate user data.
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
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

        if ($compare) {
            request()->session()->put('user', $model->toArray());
            $model->last_login_at = TODAY;
            $model->auth_token = $this->getUniqueId(md5(NOW), 'auth_token');
            $model->save();
        } else {
            $model = null;
        }

        return $model;
    }

    /**
     * Authenticate user data by fb id.
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
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
            if (!$this->where('email', $email)->first() && $email) {
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

    /**
     * Remove user session and auth_token.
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function postLogOut()
    {
        $model = null;
        $auth_token = request()->header('auth-token', request()->header('username'));

        // for client (android and ios)
        if ($auth_token) {
            $member = self::where('auth_token', $auth_token)->first();

            if ($member) {
                $member->auth_token = null;
                $member->save();
            }
        }

        // for web version
        if (request()->session()->has('user')) {
            $model = $this->find(request()->session()->get('user')['id']);
            $model->auth_token = null;
            $model->save();
        }

        request()->session()->forget('user');

        return $model;
    }

    // LOG

    /**
     * Get user session data.
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function getLogOnData()
    {
        $model = $this->getAPILogOnData();

        if (!$model) {
            $model = $this->getHTTPLogOnData();
        }

        return $model;
    }

    /**
     * Get user session data.
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    private function getHTTPLogOnData()
    {
        $model = null;

        if (request()->session()->has('user')) {
            $model = request()->session()->get('user');
            $model = self::find($model['id']);
        }

        return $model;
    }

    /**
     * Get user data by matching auth-token.
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    private function getAPILogOnData()
    {
        $auth_token = request()->header('auth-token');

        $model = $this->where(\DB::raw('(
                (username = "'.$auth_token.'" or email = "'.$auth_token.'")
                or (auth_token = "'.$auth_token.'" and auth_token is not null)
            )'), true)
            ->first();

        return $model;
    }
}
