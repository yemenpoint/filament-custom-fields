<?php

namespace Yemenpoint\FilamentCustomFields\Traits;

use Illuminate\Http\Request;
use Yemenpoint\FilamentCustomFields\Models\CustomField;
use Yemenpoint\FilamentCustomFields\Models\CustomFieldResponse;

trait HasCustomFields
{

    public function customFieldResponses()
    {
        return $this->morphMany(CustomFieldResponse::class, 'model');
    }

}
