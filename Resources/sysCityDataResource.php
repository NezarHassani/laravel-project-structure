<?php

/* created using Dev Tools V3 */

namespace App\Http\Resources\Admin\system_management\system_initialization\system_entries;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class sysCityDataResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => ($this->translations->where('locale', app()->getLocale())->first()->name ?? $this->translations->first()->name),

            'sys_country_id' => $this->sys_country_id == null ? '' : $this->sysCountry_r->uuid,
            'sys_country_id_name' => $this->sys_country_id == null ? '' : $this->sysCountry_r->translations->where('locale', app()->getLocale())->first()->name ?? $this->sysCountry_r->translations->first()->name,

            'sys_state_id' => $this->sys_state_id == null ? '' : $this->sysState_r->uuid,
            'sys_state_id_name' => $this->sys_state_id == null ? '' : $this->sysState_r->translations->where('locale', app()->getLocale())->first()->name ?? $this->sysState_r->translations->first()->name,

            'latitude' => $this->latitude,

            'longitude' => $this->longitude,

        ];
    }
}
