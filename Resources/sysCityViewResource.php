<?php

/* created using Dev Tools V3 */

namespace App\Http\Resources\Admin\system_management\system_initialization\system_entries;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use App\Models\admin\system_management\system_initialization\system_entries\sysCity;

class sysCityViewResource
extends JsonResource
{
    public function toArray($request)
    {
        return [
            'responsive_id' => '',
            'id' => $this->id,
            'responsive_id' => $this->responsive_id,
            'id' => $this->id,
            'Actions' => $this->getSysCityActions(),
            'id' => $this->id,
            'name' => $this->id . ' - ' .  ($this->translations->where('locale', app()->getLocale())->first()->name ?? $this->translations->first()->name),
            'sys_country_id' => $this->sysCountry_r->translations->where('locale', app()->getLocale())->first()->name ?? $this->sysCountry_r->translations->first()->name,
            'sys_state_id' => $this->sysState_r->translations->where('locale', app()->getLocale())->first()->name ?? $this->sysState_r->translations->first()->name,
            'country_code' => $this->sysCountry_r == null ? '' : ($this->sysCountry_r->iso2 ?? ''),
            'state_code' => $this->sysState_r == null ? '' : ($this->sysState_r->iso2 ?? ''),
            'api_id' => $this->api_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'isActive' => sysCity::_isActiveStats()[$this->isActive],
            'created_at' => $this->created_at ? $this->created_at->toDateString() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toDateString() : '',
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateString() : '',
        ];
    }
}
