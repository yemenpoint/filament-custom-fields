<?php

namespace Yemenpoint\FilamentCustomFields\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class CustomField extends Model
{
    use SoftDeletes, HasFactory;

    protected $appends = ["options"];

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

    public function getOptionsAttribute()
    {
        $options = [];
        $value = $this->answers;

        if ($value) {
            $options = explode("|", $value);
        }

        return array_combine($options, $options);
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
