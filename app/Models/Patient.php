<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'national_number', 'gender', 'medical_number', 'address', 'phone_number'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    protected $genderMap = [
        'male' => 'ذكر',
        'female' => 'أنثى'
    ];

    public function getGenderAttribute($value) {
        return $this->genderMap[$value] ?? $value;
    }
}
