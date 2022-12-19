<?php

/* created using Dev Tools V3 */

namespace App\Http\Controllers\Admin\system_management\system_initialization\system_entries;

use App\Models\admin\system_management\system_initialization\system_entries\sysCity;
use App\Services\admin\system_management\system_initialization\system_entries\sysCityService;
use App\Http\Requests\admin\system_management\system_initialization\system_entries\sysCityStoreRequest;
use App\Http\Requests\admin\system_management\system_initialization\system_entries\sysCityUpdateRequest;
use App\Http\Requests\admin\system_management\system_initialization\system_entries\sysCityTranslationStoreRequest;
use App\Http\Resources\Admin\system_management\system_initialization\system_entries\sysCityViewResource;
use App\Http\Resources\Admin\system_management\system_initialization\system_entries\sysCityDataResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\ExceptionHandlingHelper;

class sysCityController extends Controller
{
    public function index(Request $request)
    {
        return view('admin/pages/system_management/system_initialization/system_entries/sysCity/index');
    }

    public function sysCityGet(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $searchColumn_arr = json_decode($request->column_search, true);
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr == null ? 1 : $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr == null ? 'id' : $columnName_arr[$columnIndex]['data']; // Column name

        $columnSortOrder = $order_arr == null ? 'asc' : $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr == null  ? '' : $search_arr['value'];
        // Total records
        $totalRecords = sysCity::select('count(*) as allcount')->count();

        $_DATA_TABLES_SEARCH = collect(sysCity::_COLUMNS)->where('isSearchable', true);
        $_DATA_TABLES_SEARCH_trans = $_DATA_TABLES_SEARCH->where('hasTranslation', true);
        $_DATA_TABLES_SEARCH = $_DATA_TABLES_SEARCH->where('hasTranslation', false);

        $query = sysCity::with([
                'sysCountry_r.translations',
                'sysState_r.translations'
            ])
            ->when(count($searchColumn_arr) > 0, function ($q) use ($searchColumn_arr, $_DATA_TABLES_SEARCH, $_DATA_TABLES_SEARCH_trans) {

                if (count($_DATA_TABLES_SEARCH_trans)) {
                    $q->whereHas('translations', function ($localization) use ($searchColumn_arr, $_DATA_TABLES_SEARCH_trans) {
                        foreach ($_DATA_TABLES_SEARCH_trans as $column) {
                            if (isset($searchColumn_arr[$column['column']])) {
                                $localization->where($column['column'], 'like', '%' . $searchColumn_arr[$column['column']] . '%');
                            }
                        }
                    });
                }
                if (count($_DATA_TABLES_SEARCH)) {
                    foreach ($_DATA_TABLES_SEARCH as $column) {
                        if (isset($searchColumn_arr[$column['column']])) {
                            if ($column['search']['search_data_type'] == "string") {
                                if ($column['hasRelation'] && $column['relation']['type'] == 'relation') {
                                    $q->whereHas($column['relation']['name'], function ($qr) use ($searchColumn_arr, $column) {
                                        $qr->where($column['relation']['relation_column'], $searchColumn_arr[$column['column']]);
                                    });
                                } else {
                                    $q->Where($column['column'], 'like', '%' . $searchColumn_arr[$column['column']] . '%');
                                }
                            } else if ($column['search']['search_data_type'] == "number") {
                                if ($column['hasRelation'] && $column['relation']['type'] == 'relation') {
                                    $q->whereHas($column['relation']['name'], function ($qr) use ($searchColumn_arr, $column) {
                                        $qr->where('uuid', $searchColumn_arr[$column['column']]);
                                    });
                                } else {
                                    $q->Where($column['column'],  $searchColumn_arr[$column['column']]);
                                }
                            } else if ($column['search']['search_data_type'] == "date") {
                                $date_array = explode(" ", $searchColumn_arr[$column['column']]);
                                if (count($date_array) == 1) {
                                    $d1 = new \DateTime($date_array[0]);
                                    $d1 = $d1->format('Y-m-d');
                                    $q->Where(DB::raw("DATE(" . $column['column'] . ")"), '=', $d1);
                                } else {
                                    $d1 = new \DateTime($date_array[0]);
                                    $d1 = $d1->format('Y-m-d');
                                    $d2 = new \DateTime($date_array[2]);
                                    $d2 = $d2->format('Y-m-d');
                                    $q->Where(DB::raw("DATE(" . $column['column'] . ")"), '>=', $d1)
                                        ->where(DB::raw("DATE(" . $column['column'] . ")"), '<=', $d2);
                                }
                            }
                        }
                    }
                }
            })
            ->when(!in_array($columnName, $_DATA_TABLES_SEARCH_trans->pluck('column', 'column')->toArray()), function ($q) use ($columnName, $columnSortOrder) {
                $q->orderBy($columnName, $columnSortOrder);
            });

        $totalRecordsWithFilter = $query->count();

        $records = $query->skip($start ?? 0)
            ->take($rowperpage ?? 10)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                'responsive_id' =>  '',
                'id' =>  '',
                'Actions' => $record->getSysCityActions(),
                'id' =>  $record->id,
                'name' => ($record->translations->where('locale', app()->getLocale())->first()->name ?? $record->translations->first()->name),
                'sys_country_id' => $record->sys_country_id == null ? '' : ($record->sysCountry_r->translations->where('locale', app()->getLocale())->first()->name ?? $record->sysCountry_r->translations->first()->name),
                'sys_state_id' => $record->sys_state_id == null ? '' : ($record->sysState_r->translations->where('locale', app()->getLocale())->first()->name ?? $record->sysState_r->translations->first()->name),
                'country_code' => $record->sysCountry_r == null ? '' : ($record->sysCountry_r->iso2 ?? ''),
                'state_code' => $record->sysState_r == null ? '' : ($record->sysState_r->iso2 ?? ''),
                'latitude' =>  $record->latitude,
                'longitude' =>  $record->longitude,
                'created_at' => $record->created_at ? $record->created_at->toDateString() : '',
                'updated_at' => $record->updated_at ? $record->updated_at->toDateString() : '',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData" => $data_arr,
        );

        return json_encode($response);


        //return   sysCityViewResource::collection(sysCity::withTranslation()->get());
    }

    public function getListOfSysCity(Request $request, sysCityService $sysCityService)
    {
        try {
            $sysCity = $sysCityService->getList($request->search, $request->column, $request->hasDependence, $request->dependence);
        } catch (\Exception $exception) {
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }

    public function store(sysCityStoreRequest $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity = $sysCityService->prepareSysCityForCreate($request->all());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }

    public function edit(Request $request, sysCityService $sysCityService)
    {
        try {
            $sysCity  = new sysCityDataResource($sysCityService->getSysCityByUuidOrID($request->id, true));
        } catch (\Exception $exception) {
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' =>  $sysCity], 200);
    }

    public function update(sysCityUpdateRequest $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity  = $sysCityService->prepareSysCityForUpdate($request->all());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }

    public function delete(Request $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity = $sysCityService->prepareSysCityForDelete(['sys_city_id' => $request->id]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }

    public function translations(Request $request, sysCityService $sysCityService)
    {
        try {
            $data = $sysCityService->getTranslationSettings($request->id);
            return response()->json(['data' => $data], 200);
        } catch (\Exception $exception) {
            return ExceptionHandlingHelper::getException($exception);
        }
    }
    public function storeTranslations(sysCityTranslationStoreRequest $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity = $sysCityService->prepareTranslationForExternalUpdate($request->all());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }

    public function deleteTranslations(Request $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity = $sysCityService->prepareTranslationForExternalDelete(['translatable' => $request->id, 'locale' => $request->locale]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }
    public function enable(Request $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity = $sysCityService->propertyUpdate(['sys_city_id' => $request->id, "property" => $request->property, "value" => $request->value]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }
    public function disable(Request $request, sysCityService $sysCityService)
    {
        try {
            DB::beginTransaction();
            $sysCity = $sysCityService->propertyUpdate(['sys_city_id' => $request->id, "property" => $request->property, "value" => $request->value]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return ExceptionHandlingHelper::getException($exception);
        }
        return response()->json(['data' => $sysCity], 200);
    }
}
