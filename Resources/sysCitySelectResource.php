<?php

/* created using Dev Tools V3 */

namespace App\Http\Resources\Admin\system_management\system_initialization\system_entries;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class sysCitySelectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name' => $this->id . ' - ' .  ($this->translations->where('locale', app()->getLocale())->first()->name ?? $this->translations->first()->name),
        ];
    }
}
