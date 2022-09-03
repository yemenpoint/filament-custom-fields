<?php

namespace Yemenpoint\FilamentCustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldResponse extends Model
{
    protected $fillable = [
        'value',
        'model_id',
        'model_type',
        'field_id',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function field()
    {
        return $this->belongsTo(CustomField::class, 'field_id');
    }
}
