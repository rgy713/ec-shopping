<?php

namespace App\Http\Controllers\Admin\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin;
use App\Models\AdvertisingMedia;
use App\Models\AdvertisingMediaSummaryGroupAdvertisingMedia;
use App\Models\Exports\SearchResultExport;
use App\Services\FlashToastrMessageService;
use App\Services\MediaService;
use App\Common\Api\ApiResponseData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MediaController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(MediaService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function search(Request $request)
    {
        $medias = [];

        $account = Admin::find($request->user()->id);
        $editable = $account !== null && ($account->admin_role_id == 1 || $account->admin_role_id == 2);

        $view_params = [
            "medias"=>$medias,
            "editable"=>$editable,
        ];

        $params = $request->all();
        if(isset($params['back'])) {
            $search_params = Cache::get('medias_search_params');

            $search_params['account'] = $account;
            return $this->getSearchResult($search_params);
        }

        return view("admin.pages.media.search", $view_params);
    }

    public function searchResult(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'media_code' => ['nullable', 'integer', 'min:1'],
            'media_name' => ['nullable', 'string', 'max:255'],
            'media_date_from' => ['nullable', 'date'],
            'media_date_to' => 'nullable|date'. (isset($params['media_date_from']) ? '|after_or_equal:media_date_from' : ''),
            'media_code_group' => ['nullable', 'array', 'min:1', 'max:99999'],
            'item_lineup_id' => ['nullable', 'array', 'exists:item_lineups,id'],
            'media_summary_group' => ['nullable', 'array', 'exists:advertising_media_summary_groups,id']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $params['account'] = Admin::find($request->user()->id);
        return $this->getSearchResult($params);
    }

    public function getSearchResult($params)
    {
        $media_customer_counts = DB::table('customers')
            ->select('advertising_media_code', DB::raw('count(advertising_media_code) as customer_count'))
            ->groupBy('advertising_media_code');

        $medias = DB::table('advertising_media')
            ->select(
                'advertising_media.id as id',
                'advertising_media.code as code',
                'advertising_media.broadcaster as broadcaster',
                'advertising_media.name as name',
                'advertising_media.cost as cost',
                'advertising_media.date as date',
                'advertising_media.start_time as start_time',
                'advertising_media.broadcast_minutes as broadcast_minutes',
                'advertising_media.broadcast_duration_from as broadcast_duration_from',
                'advertising_media.item_lineup_id as item_lineup_id',
                'advertising_media_summary_group_advertising_media.advertising_media_summary_group_id as advertising_media_summary_group_id',
                DB::Raw('coalesce(media_customer_counts.customer_count, 0) as customer_count'))
            ->leftJoin('advertising_media_summary_group_advertising_media', 'advertising_media.id', 'advertising_media_summary_group_advertising_media.advertising_medium_id')
            ->leftJoin(DB::raw(sprintf('(%s) as media_customer_counts', $media_customer_counts->toSql())),'advertising_media.code','media_customer_counts.advertising_media_code');

        $search_params = $this->service->search_result($medias, $params);

        $number_per_page = $search_params['number_per_page'];
        $medias = $medias->paginate($number_per_page);

        $account = $params['account'];
        $editable = $account !== null && ($account->admin_role_id == 1 || $account->admin_role_id == 2);

        Cache::forever('medias_search_params', $search_params);

        $view_params = [
            "search_params"=>$search_params,
            "medias"=>$medias,
            "editable"=>$editable,
        ];

        return view("admin.pages.media.search", $view_params);
    }

    public function downloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'media_code' => ['nullable', 'integer', 'min:1'],
            'media_name' => ['nullable', 'string', 'max:255'],
            'media_date_from' => ['nullable', 'date'],
            'media_date_to' => 'nullable|date'. (isset($params['media_date_from']) ? '|after_or_equal:media_date_from' : ''),
            'media_code_group' => ['nullable', 'array', 'min:1', 'max:99999'],
            'item_lineup_id' => ['nullable', 'array', 'exists:item_lineups,id'],
            'media_summary_group' => ['nullable', 'array', 'exists:advertising_media_summary_groups,id']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $media_customer_counts = DB::table('customers')
            ->select('advertising_media_code', DB::raw('count(advertising_media_code) as customer_count'))
            ->groupBy('advertising_media_code');

        $medias = DB::table('advertising_media')
            ->leftJoin('advertising_media_summary_group_advertising_media', 'advertising_media.id', 'advertising_media_summary_group_advertising_media.advertising_medium_id')
            ->join('item_lineups', 'advertising_media.item_lineup_id', 'item_lineups.id')
            ->leftJoin('medium_types', 'advertising_media.media_type_id', 'medium_types.id')
            ->leftJoin(DB::raw(sprintf('(%s) as media_customer_counts', $media_customer_counts->toSql())),'advertising_media.code','media_customer_counts.advertising_media_code');

        $this->service->search_result($medias, $params);

        $searchResultExport = new SearchResultExport(13, $medias);
        return $searchResultExport->download();
    }

    public function create()
    {
        return view("admin.pages.media.detail");
    }

    public function createCopy(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:advertising_media,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        $media = AdvertisingMedia::find($id);

        $media_summary_group_id = null;
        $advertisingMediaSummaryGroupAdvertisingMedia = $media->summaryGroup()->get()->first();
        if ($advertisingMediaSummaryGroupAdvertisingMedia != null) {
            $media_summary_group_id = $advertisingMediaSummaryGroupAdvertisingMedia->advertising_media_summary_group_id;
        }

        unset($media->id);

        return view("admin.pages.media.detail", compact('media', 'media_summary_group_id'));
    }

    public function createSave(Request $request)
    {
        $params = $request->all();

        $rules = [
            'media_code' => ['required', 'integer', 'unique:advertising_media,code'],
            'media_type_id' => ['required', 'exists:medium_types,id'],
            'media_area' => ['nullable', 'string', 'max:255'],
            'media_broadcaster' => ['nullable', 'string', 'max:255'],
            'media_name' => ['required', 'string', 'max:255'],
            'media_detail' => ['nullable', 'string', 'max:255'],
            'media_cost' => ['required', 'integer', 'min:0'],
            'media_date' => ['required', 'date'],
            'media_broadcast_minutes' => ['nullable', 'integer', 'min:1', 'max:32767'],
            'item_lineup_id' => ['required', 'exists:item_lineups,id'],
            'media_summary_group_id' => ['nullable', 'exists:advertising_media_summary_groups,id'],
            'media_circulation' => ['nullable', 'integer', 'min:1'],
            'media_call_expected' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'media_remark' => ['nullable', 'string']
        ];

        $validator = Validator::make($params, [
            'media_start_time' => ['nullable', "regex:/[0-9][0-9]:[0-9][0-9]/"],
        ]);

        if ($validator->fails()) {
            $rules['media_start_time'] = ['nullable', 'string', 'max:255'];
        }
        else {
            $rules['media_start_time'] = ['nullable', 'date_format:"H:i"'];
        }

        $validator = Validator::make($params, [
            'media_broadcast_duration_from' => ['nullable', 'date_format:"H:i"'],
        ]);

        if ($validator->fails()) {
            $rules['media_broadcast_duration_from'] = ['nullable', 'date_format:"H:i:s"'];
        }

        $validator = Validator::make($params, [
            'media_broadcast_duration_to' => 'nullable|date_format:"H:i"',
        ]);

        if ($validator->fails()) {
            $rules['media_broadcast_duration_to'] = 'nullable|date_format:"H:i:s"'. (isset($params['media_broadcast_duration_from']) ? '|after_or_equal:media_broadcast_duration_from' : '');
        }
        else {
            $rules['media_broadcast_duration_to'] = 'nullable'. (isset($params['media_broadcast_duration_from']) ? '|after_or_equal:media_broadcast_duration_from' : '');
        }

        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $media = $this->service->createSave($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
            return Redirect::back();
        }

        $media_summary_group_id = null;
        $advertisingMediaSummaryGroupAdvertisingMedia = $media->summaryGroup()->get()->first();
        if ($advertisingMediaSummaryGroupAdvertisingMedia != null) {
            $media_summary_group_id = $advertisingMediaSummaryGroupAdvertisingMedia->advertising_media_summary_group_id;
        }

        return view("admin.pages.media.info", compact('media', 'media_summary_group_id'));
    }

    public function edit(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:advertising_media,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        $media = AdvertisingMedia::find($id);

        $media_summary_group_id = null;
        $advertisingMediaSummaryGroupAdvertisingMedia = $media->summaryGroup()->get()->first();
        if ($advertisingMediaSummaryGroupAdvertisingMedia != null) {
            $media_summary_group_id = $advertisingMediaSummaryGroupAdvertisingMedia->advertising_media_summary_group_id;
        }

        return view("admin.pages.media.detail", compact('media', 'media_summary_group_id'));
    }

    public function editSave(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;

        $rules = [
            'id' => ['required', 'exists:advertising_media,id'],
            'media_code' => ['required', 'integer', 'unique:advertising_media,code,'.$id],
            'media_type_id' => ['required', 'exists:medium_types,id'],
            'media_area' => ['nullable', 'string', 'max:255'],
            'media_broadcaster' => ['nullable', 'string', 'max:255'],
            'media_name' => ['required', 'string', 'max:255'],
            'media_detail' => ['nullable', 'string', 'max:255'],
            'media_cost' => ['required', 'integer', 'min:0'],
            'media_date' => ['required', 'date'],
            'media_broadcast_minutes' => ['nullable', 'integer', 'min:1', 'max:32767'],
            'item_lineup_id' => ['required', 'exists:item_lineups,id'],
            'media_summary_group_id' => ['nullable', 'exists:advertising_media_summary_groups,id'],
            'media_circulation' => ['nullable', 'integer', 'min:1'],
            'media_call_expected' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'media_remark' => ['nullable', 'string']
        ];

        $validator = Validator::make($params, [
            'media_start_time' => ['nullable', "regex:/[0-9][0-9]:[0-9][0-9]/"],
        ]);

        if ($validator->fails()) {
            $rules['media_start_time'] = ['nullable', 'string', 'max:255'];
        }
        else {
            $rules['media_start_time'] = ['nullable', 'date_format:"H:i"'];
        }

        $validator = Validator::make($params, [
            'media_broadcast_duration_from' => ['nullable', 'date_format:"H:i"'],
        ]);

        if ($validator->fails()) {
            $rules['media_broadcast_duration_from'] = ['nullable', 'date_format:"H:i:s"'];
        }

        $validator = Validator::make($params, [
            'media_broadcast_duration_to' => 'nullable|date_format:"H:i"',
        ]);

        if ($validator->fails()) {
            $rules['media_broadcast_duration_to'] = 'nullable|date_format:"H:i:s"'. (isset($params['media_broadcast_duration_from']) ? '|after_or_equal:media_broadcast_duration_from' : '');
        }
        else {
            $rules['media_broadcast_duration_to'] = 'nullable'. (isset($params['media_broadcast_duration_from']) ? '|after_or_equal:media_broadcast_duration_from' : '');
        }

        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->editSave($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        $media = AdvertisingMedia::find($id);

        $advertisingMediaSummaryGroupAdvertisingMedia = $media->summaryGroup()->get()->first();
        if ($advertisingMediaSummaryGroupAdvertisingMedia != null) {
            $media_summary_group_id = $advertisingMediaSummaryGroupAdvertisingMedia->advertising_media_summary_group_id;
        }

        return view("admin.pages.media.info", compact('media', 'media_summary_group_id'));
    }

    public function delete(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:advertising_media,id'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->delete($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }
}
