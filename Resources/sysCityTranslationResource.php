<?php

/* created using Dev Tools V3 */

namespace App\Http\Resources\Admin\system_management\system_initialization\system_entries;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class sysCityTranslationResource
extends JsonResource
{
    public function toArray($request)
    {
        return [
            'locale' => $this->locale,
            'name' => $this->name,
            'Actions' => $this->getTranslationActions(),

        ];
    }
}
