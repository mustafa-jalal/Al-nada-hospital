<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;


class Visit extends Model
{
    protected $guarded = [];
    protected $fillable = ['visit_type', 'patient_id', 'checked_at', 'checked_out_at'];

    protected $visitTypeMap = [
        'checkup' => 'عيادة',
        'surgery' => 'عملية جراحية', 
        'emergency' => 'طوارئ'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function getVisitTypeAttribute($value) {
        return $this->visitTypeMap[$value] ?? $value;
    }


}
