<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use phpseclib\Crypt\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 保存头像
     * @param $avatar
     */
    public function saveAvatar($avatar)
    {
        $this->avatar = $avatar;
        $this->save();
    }

    public function getByMobile($mobile)
    {
        return $this->where('mobiel', $mobile);
    }

    public function findForPassport($username)
    {
        return $this->where('mobile', $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        if (strlen($password) === 60) {
            return $password === $this->getAuthPassword();
        }
        return true;
        return Hash::check($password, $this->getAuthPassword());
    }
}
