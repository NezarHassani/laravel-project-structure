<?php

namespace App\Models\admin\system_management\system_initialization\system_entries;

use DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Traits\SecureDelete;

class sysCity extends Model
{
    use SoftDeletes, SecureDelete, Auditable, HasFactory, Translatable;

    public $table = 'sys_cities';
    public static $translation_crud  = 'admin.system_management.system_initialization.system_entries.sys_city.translation';




    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $translatedAttributes = [
        'name',

    ];

    protected $fillable = [
        'uuid',
        'api_id',
        'sys_state_id',
        'sys_country_id',
        'latitude',
        'longitude',
        'isActive',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function sysCountry_r()
    {
        return $this->belongsTo(sysCountry::class, 'sys_country_id');
    }

    public function sysState_r()
    {
        return $this->belongsTo(sysState::class, 'sys_state_id');
    }

    public function sys_district_r()
    {
        return $this->hasMany(sys_district::class, "sys_city_id");
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getAllRelation()
    {
        return ['sys_district_r'];
    }


    public function getSysCityActions()
    {
        $actions = [];

        if (Auth::user()->can('sys_city_edit')) {
            $actions['edit'] = ' onclick="OpenSysCityEdit(\'' . $this->uuid . '\')" ';
        }
        if (Auth::user()->can('sys_city_delete')) {
            $actions['delete'] = ' onclick="deleteSysCity(\'' . $this->uuid . '\')" ';
        }
        if (Auth::user()->can('sys_city_translate_v')) {
            $actions['translate'] = ' onclick="openTranslationModal(\'' . $this->uuid . '\',\'' . sysCity::$translation_crud . '\')" ';
        }
        if (Auth::user()->can('sys_city_create')) {
            if (isset($this->isActive) && $this->isActive == 0) {
                $actions['enable'] = ' onclick="OpenSysCityEnable(\'' . $this->uuid . '\')" ';
            }
        }
        if (Auth::user()->can('sys_city_edit')) {
            if (isset($this->isActive) && $this->isActive == 1) {
                $actions['disable'] = ' onclick="OpenSysCityDisable(\'' . $this->uuid . '\')" ';
            }
        }

        return $actions;
    }

    public static function _isActiveStats()
    {
        return [
            '1' => trans('global.enabled'),
            '0' => trans('global.disabled'),
        ];
    }

    public const _MODEL_DATA = [
        '_MODEL_NAME_SPACE' => 'App\Models\admin\system_management\system_initialization\system_entries\sysCity',
        '_REQUEST_NAME_SPACE' => 'App\Http\Requests\admin\system_management\system_initialization\system_entries',
        "_RESOURCES_NAME_SPACE" => "App\Http\Resources\Admin\system_management\system_initialization\system_entries",
        "_SERVICES_NAME_SPACE" => "App\Services\admin\system_management\system_initialization\system_entries",
        '_CONTROLLER_NAME_SPACE' => "App\Http\Controllers\Admin\system_management\system_initialization\system_entries",
        '_VIEW_PATH' => "admin/pages/system_management/system_initialization/system_entries",
        '_JS_PATH' => "app-js/pages/system_management/system_initialization/system_entries",
        '_WEB_ROUT' => "admin.system_management.system_initialization.system_entries.sys_city.",
        '_ROUT_PREFIX' => "admin/system_management/system_initialization/system_entries/sys_city",
        '_PERMISSIONS_SETTINGS' => ['sys_city', 'مدينة', 'المدن', 'V'],
        '_LANGUAGE_PATH' => 'cruds.system_management.sys_city.',
        '_MODEL' => "sysCity",
        '_FOREIGN' => "sys_city_id",
        '_MODEL_WITH_UNDER_SCORE' => "sys_city",
        '_ACTIVATION_COLUMN' => 'isActive',
        '_ACTIVE_VALUE' => 1,
        '_INACTIVE_VALUE' => 0,
        '_SELECT_COLUMN' => ['name'],
        '_DB_TABLE' => 'sys_cities',
        'UI_MODEL_SIZE' => 'modal-lg',
    ];


    public const _COLUMNS = [
        [
            'column' => 'responsive_id',
            'hasTranslation' => false,
            'isFillable' => false,
            'showInDataTable' => true,
            'hasRelation' => false,
            'dataTableType' => 'cell',
            'isEmpty' => true,
            'dataType' => 'cell'
        ],
        [
            'column' => 'id',
            'isFillable' => false,
            'hasTranslation' => false,
            'showInDataTable' => true,
            'hasRelation' => false,
            'dataTableType' => 'cell',
            'isEmpty' => true,
            'dataType' => 'cell'

        ],
        [
            'column' => 'Actions',
            'isFillable' => false,
            'hasTranslation' => false,
            'showInDataTable' => true,
            'hasRelation' => false,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'cell'

        ],
        [
            'column' => 'id',
            'hasTranslation' => false,
            'isFillable' => false,
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "number",
                'hasTranslation' => false,
                'ui_properties' => [
                    'type' => 'number',
                ],
            ],
            'hasRelation' => false,
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'integer'

        ],
        [
            'column' => 'name',
            'hasTranslation' => true,
            'isFillable' => true,
            'input' => [
                'request_rules' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    '_U',
                ],
                'ui_validation' => [
                    'data-type' => 'text_number_special',
                    'data-validation' => 'r/mi_2/mx_255',
                    'type' => 'T',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'text',
                    'isOptional' => false,
                ],
            ],
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "string",
                'hasTranslation' => true,
                'ui_properties' => [
                    'type' => 'text',
                ],
            ],
            'hasRelation' => false,
            'relation' => [],
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'string'

        ],

        [
            'column' => 'sys_country_id',
            'hasTranslation' => false,
            'isFillable' => true,
            'input' => [
                'request_rules' => [
                    'required',
                    'string',
                    // 'min:1',
                    // 'regex:/[1-9]+$/',
                ],
                'ui_validation' => [
                    'data-type' => 'number',
                    'data-validation' => 'r/miv_1',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'select',
                    'isOptional' => false,
                ],
            ],
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "number",
                'hasTranslation' => true,
                'ui_properties' => [
                    'type' => 'select',
                ],
            ],
            'hasRelation' => true,
            'relation' => [

                'type' => 'relation',
                'name' => 'sysCountry_r',
                'model_name' => "sysCountry",
                'model_path' => "App\Models\admin\system_management\system_initialization\system_entries\sysCountry",
                'hasTranslation' => true,
                'relation_column' => "name",

            ],
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'integer'
        ],
        [
            'column' => 'sys_state_id',
            'hasTranslation' => false,
            'isFillable' => true,
            'input' => [
                'request_rules' => [
                    'nullable',
                    'string',
                    // 'min:1',
                    // 'regex:/[1-9]+$/',
                ],
                'ui_validation' => [
                    'data-type' => 'number',
                    'data-validation' => 'o/miv_1',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'select',
                    'isOptional' => true,
                ],
            ],
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "number",
                'hasTranslation' => true,
                'ui_properties' => [
                    'type' => 'select',
                ],
            ],
            'hasRelation' => true,
            'relation' => [

                'type' => 'relation',
                'name' => 'sysState_r',
                'model_name' => "sysState",
                'model_path' => "App\Models\admin\system_management\system_initialization\system_entries\sysState",
                'hasTranslation' => true,
                'relation_column' => "name",
                'hasDependence' => true,
                'dependence' => [
                    'column_name' => 'sys_country_id',
                    'name' => 'sysCountry_r'
                ]


            ],
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'integer'

        ],
        [
            'column' => 'country_code',
            'hasTranslation' => false,
            'isFillable' => false,
            'input' => [
                'request_rules' => [
                    'nullable',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'ui_validation' => [
                    'data-type' => 'text_number_special',
                    'data-validation' => 'o/mi_1/mx_255',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'text',
                    'isOptional' => true,
                ],
            ],
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "string",
                'hasTranslation' => false,
                'ui_properties' => [
                    'type' => 'text',
                ],
            ],
            'hasRelation' => true,
            'relation' => [
                'type' => 'relation',
                'name' => 'sysCountry_r',
                'model_name' => "sysCountry",
                'model_path' => "App\Models\admin\system_management\system_initialization\system_entries\sysCountry",
                'hasTranslation' => false,
                'relation_column' => "iso2",
                'view_only' => true,
            ],
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'string'
        ],
        [
            'column' => 'state_code',
            'hasTranslation' => false,
            'isFillable' => false,
            'input' => [
                'request_rules' => [
                    'nullable',
                    'string',
                    'min:1',
                    'max:255',
                ],
                'ui_validation' => [
                    'data-type' => 'text_number_special',
                    'data-validation' => 'o/mi_1/mx_255',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'text',
                    'isOptional' => true,
                ],
            ],
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "string",
                'hasTranslation' => false,
                'ui_properties' => [
                    'type' => 'text',
                ],
            ],
            'hasRelation' => true,
            'relation' => [
                'type' => 'relation',
                'name' => 'sysState_r',
                'model_name' => "sysState",
                'model_path' => "App\Models\admin\system_management\system_initialization\system_entries\sysState",
                'hasTranslation' => false,
                'relation_column' => "iso2",
                'view_only' => true,

            ],
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'string'
        ],
        [
            'column' => 'api_id',
            'hasTranslation' => false,
            'isFillable' => false,
            'isSearchable' => false,
            'hasRelation' => false,
            'showInDataTable' => false,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'integer'
        ],

        [
            'column' => 'latitude',
            'hasTranslation' => false,
            'isFillable' => true,
            'input' => [
                'request_rules' => [
                    'nullable',

                    'numeric',
                    'min:1',
                ],
                'ui_validation' => [
                    'data-type' => 'number_or_decimal',
                    'data-validation' => 'o/miv_1',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'text',
                    'isOptional' => true,
                ],
            ],
            'isSearchable' => false,
            'hasRelation' => false,
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'string'
        ],
        [
            'column' => 'longitude',
            'hasTranslation' => false,
            'isFillable' => true,
            'input' => [
                'request_rules' => [
                    'nullable',

                    'numeric',
                    'min:1',
                ],
                'ui_validation' => [
                    'data-type' => 'number_or_decimal',
                    'data-validation' => 'o/miv_1',
                ],
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'text',
                    'isOptional' => true,
                ],
            ],
            'isSearchable' => false,
            'hasRelation' => false,
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'string'
        ],
        [
            'column' => 'isActive',
            'hasTranslation' => false,
            'isFillable' => false,
            'input' => [
                'ui_properties' => [
                    'size' => 'col-md-4',
                    'type' => 'select',
                    'isOptional' => true,
                ],
            ],
            'hasRelation' => true,
            'relation' => [

                'type' => 'static',
                'name' => '_isActiveStats',
                'hasAllOption' => true

            ],
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "number",
                'hasTranslation' => true,
                'ui_properties' => [
                    'type' => 'select',
                ],
            ],

            'showInDataTable' => false,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'integer'
        ],
        [
            'column' => 'created_at',
            'hasTranslation' => false,
            'isFillable' => false,
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "date",
                'hasTranslation' => false,
                'ui_properties' => [
                    'type' => 'date',
                ],
            ],
            'hasRelation' => false,
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'date'
        ],
        [
            'column' => 'updated_at',
            'hasTranslation' => false,
            'isFillable' => false,
            'isSearchable' => true,
            'search' => [
                'search_data_type' => "date",
                'hasTranslation' => false,
                'ui_properties' => [
                    'type' => 'date',
                ],
            ],
            'hasRelation' => false,
            'showInDataTable' => true,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'date'
        ],
        [
            'column' => 'deleted_at',
            'hasTranslation' => false,
            'isFillable' => false,
            'isSearchable' => false,
            'search' => [
                'search_data_type' => "date",
                'hasTranslation' => false,
            ],
            'hasRelation' => false,
            'showInDataTable' => false,
            'dataTableType' => 'cell',
            'isEmpty' => false,
            'dataType' => 'date'
        ],
    ];
}
