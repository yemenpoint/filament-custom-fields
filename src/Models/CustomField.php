<?php

namespace Yemenpoint\FilamentCustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $table = "filament_custom_fields";

    protected $fillable = [
        'model_type',
        'type',
        'title',
        'hint',
        'options',
        'required',
        'default_value',
        'rules',
        'show_in_columns',
        'order',
        'column_span',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function responses()
    {
        return $this->hasMany(CustomFieldResponse::class, 'field_id');
    }
}
