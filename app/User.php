<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $primaryKey = 'id_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id_users', 'password', 'remember_token'];

    /**
     * The code to run in order to verify the user's identity
     *
     * @param $username
     * @param $password
     */
    public function verify($username, $password){
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id_users;
        } 
        return false;
    }

    /*
    * Validate user data
    *
    * @param array $data Data of user
    * @param String $type Type of validation Create, Update or Invite
    *
    * @return Illuminate\Validation\Validator
    */
    public static function validateUser($data, $type = 'C'){
        $maxDate = date('Y-m-d');
        $minDate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') - 100));

        $rules = array(
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|regex:/^([a-zà-úÀ-Ú\x20])+$/i',
        );

        if ($type == 'U') {
            $rules['id_users'] = 'required|integer|exists:users,id_users';
            $rules['email'] = 'email|unique:users,email,' . $data['id_users'] . ',id_users';
            $rules['password'] = 'min:8|confirmed';
        } elseif ($type == 'I') {
            unset($rules['password']);
        }

        return \Validator::make($data, $rules);
    }

    /*
    * Create new user
    *
    * @param array $data Data of user
    * @param string $type Type of validation
    *
    * @return User
    */
    public static function newUser($data, $type = 'C'){
        $validate = self::validateUser($data, $type);
 
        if ($validate->fails()){
            $response['messages'] = $validate->messages()->toArray();
            $response['return_code'] = 406;
            return $response;
        }

        $user = new self;
     
        if (!empty($data['password'])):
          $data['password'] = bcrypt($data['password']);
        endif;
        
        $user->fill($data);
        $user->save();
        $user->token = \Crypt::encrypt(['id_users' => $user->id_users]);
        
        $response = ['user' => $user, 'return_code' => 201];
        return $response;
    }
}
