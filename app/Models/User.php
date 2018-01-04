<?php

namespace App\Models;

use Carbon\Carbon;
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
        'name', 'email', 'password', 'mobile', 'gender', 'job_date', 'birth_date', 'advantage'
    ];

    protected $dates = ['created_at', 'updated_at', 'disabled_at', 'job_date', 'birth_date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function educationInfos()
    {
        return $this->hasMany(EducationInfo::class);
    }

    public function setJobDateAttribute($value)
    {
        if ($value == '应届生') {
            $value = null;
        } else if($value == '1990年以前') {
            $knownDate = Carbon::create(1990, 0, 0, 0, 0, 0);
            Carbon::setTestNow($knownDate);
            $value = Carbon::now();
        } else {
            $value = Carbon::createFromTimestamp(strtotime($value));
        }
        $this->attributes['job_date'] = $value;
    }

    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = Carbon::createFromTimestamp(strtotime($value));
    }

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
