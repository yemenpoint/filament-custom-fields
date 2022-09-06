<?php

namespace Yemenpoint\FilamentCustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'model_type',
        'type',
        'title',
        'hint',
        'answers',
        'required',
        'default_value',
        'rules',
        'show_in_columns',
        'order',
        'column_span',
    ];

    public function setAnswersAttribute($value)
    {
        $this->attributes['answers'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getAnswersAttribute($value)
    {
        return json_decode($value) ?: [];
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function responses()
    {
        return $this->hasMany(CustomFieldResponse::class, 'field_id');
    }

}
