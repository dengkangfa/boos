<?php

namespace App\Models;

use App\Models\Traits\LastActivedAtHelper;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use phpseclib\Crypt\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles, LastActivedAtHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'gender', 'advantage', 'company_id',
        'pos_name', 'job_date', 'birth_date', 'homepages', 'last_actived_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'last_actived_at', 'disabled_at', 'job_date', 'birth_date'];

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

    public function expectPosition()
    {
        return $this->hasOne(ExpectPosition::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    // 联系人
    public function contacts()
    {
        return $this->belongsToMany($this, 'contacts', 'user_id', 'contact_id')->withPivot('chat_uuid')->withTimestamps();
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
}
