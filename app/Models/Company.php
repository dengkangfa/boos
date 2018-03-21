<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'pic', 'abbreviation', 'industry_str', 'industry_code',
        'stock', 'people', 'address_str', 'address_code', 'detial', 'statue'];

    public function boss()
    {
        return $this->hasMany(User::class);
    }
}
