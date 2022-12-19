<?php

namespace App\Models\admin\system_management\system_initialization\system_entries;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class sysCityTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'sys_city_id',
        'name',
        'locale',
    ];

    public function getTranslationActions()
    {
        $actions = [];


        $actions = [];
        if (Auth::user()->can('sys_city_translate_e')) {
            $actions['edit'] = ' onclick="editTranslation(\'' . $this->locale . '\')" ';
        }
        if (Auth::user()->can('sys_city_translate_d')) {
            $actions['delete'] = ' onclick="deleteTranslation(\'' . $this->locale . '\')" ';
        }
        return $actions;
    }
}
