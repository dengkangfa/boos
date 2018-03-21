<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use phpseclib\Crypt\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'gender', 'advantage', 'company_id',
        'pos_name', 'job_date', 'birth_date', 'homepages'
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

    protected $guard_name = 'api';

    public function educationInfos()
    {
        return $this->hasMany(EducationInfo::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function projectExperiences()
    {
        return $this->hasMany(projectExperience::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isAuthorOf($model)
    {
        var_dump($model->user_id);
        return $model->user_id == $this->id;
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

    public function getAvatarByMobile($mobile)
    {
        return $this->where('mobile', $mobile)->select('avatar')->first();
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
