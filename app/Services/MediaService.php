<?php

namespace App\Services;

use App\Models\AdvertisingMedia;
use App\Models\AdvertisingMediaSummaryGroupAdvertisingMedia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MediaService
{
    public function createSave($params)
    {
        $media = new AdvertisingMedia();
        $media->creator_admin_id = request()->user()->id;
        $media->code = $params["media_code"];
        $media->media_type_id = $params["media_type_id"];
        if (isset($params["media_area"])) {
            $media->area = $params["media_area"];
        }
        if (isset($params["media_broadcaster"])) {
            $media->broadcaster = $params["media_broadcaster"];
        }
        $media->name = $params["media_name"];
        if (isset($params["media_detail"])) {
            $media->detail = $params["media_detail"];
        }
        $media->cost = $params["media_cost"];
        $media->date = $params["media_date"];
        $media->start_time = $params["media_start_time"];
        if (isset($params["media_broadcast_minutes"])) {
            $media->broadcast_minutes = $params["media_broadcast_minutes"];
        }
        if (isset($params["media_broadcast_duration_from"])) {
            $media->broadcast_duration_from = $params["media_broadcast_duration_from"];
        }
        if (isset($params["media_broadcast_duration_to"])) {
            $media->broadcast_duration_to = $params["media_broadcast_duration_to"];
        }
        $media->item_lineup_id = $params["item_lineup_id"];
        if (isset($params["media_circulation"])) {
            $media->circulation = $params["media_circulation"];
        }
        if (isset($params["media_call_expected"])) {
            $media->call_expected = $params["media_call_expected"];
        }
        if (isset($params["media_remark"])) {
            $media->remark = $params["media_remark"];
        }
        $media->asp_flag = false;

        $media->save();

        if (isset($params["media_summary_group_id"])) {
            $media_summary_group_id = $params["media_summary_group_id"];

            $advertisingMediaSummaryGroupAdvertisingMedia = new AdvertisingMediaSummaryGroupAdvertisingMedia();

            $advertisingMediaSummaryGroupAdvertisingMedia->advertising_media_summary_group_id = $media_summary_group_id;
            $advertisingMediaSummaryGroupAdvertisingMedia->advertising_medium_id = $media->id;
            $advertisingMediaSummaryGroupAdvertisingMedia->save();
        }

        return $media;
    }

    public function editSave($params)
    {
        $id = $params["id"];

        $media = AdvertisingMedia::find($id);

        $media->code = $params["media_code"];
        $media->media_type_id = $params["media_type_id"];
        if (isset($params["media_area"])) {
            $media->area = $params["media_area"];
        }
        if (isset($params["media_broadcaster"])) {
            $media->broadcaster = $params["media_broadcaster"];
        }
        $media->name = $params["media_name"];
        if (isset($params["media_detail"])) {
            $media->detail = $params["media_detail"];
        }
        $media->cost = $params["media_cost"];
        $media->date = $params["media_date"];
        $media->start_time = $params["media_start_time"];
        if (isset($params["media_broadcast_minutes"])) {
            $media->broadcast_minutes = $params["media_broadcast_minutes"];
        }
        if (isset($params["media_broadcast_duration_from"])) {
            $media->broadcast_duration_from = $params["media_broadcast_duration_from"];
        }
        if (isset($params["media_broadcast_duration_to"])) {
            $media->broadcast_duration_to = $params["media_broadcast_duration_to"];
        }
        $media->item_lineup_id = $params["item_lineup_id"];
        if (isset($params["media_circulation"])) {
            $media->circulation = $params["media_circulation"];
        }
        if (isset($params["media_call_expected"])) {
            $media->call_expected = $params["media_call_expected"];
        }
        if (isset($params["media_remark"])) {
            $media->remark = $params["media_remark"];
        }

        $media->save();

        if (isset($params["media_summary_group_id"])) {
            $media_summary_group_id = $params["media_summary_group_id"];

            $media->summaryGroup()->delete();

            $advertisingMediaSummaryGroupAdvertisingMedia = new AdvertisingMediaSummaryGroupAdvertisingMedia();

            $advertisingMediaSummaryGroupAdvertisingMedia->advertising_media_summary_group_id = $media_summary_group_id;
            $advertisingMediaSummaryGroupAdvertisingMedia->advertising_medium_id = $media->id;
            $advertisingMediaSummaryGroupAdvertisingMedia->save();
        }
    }

    public function delete($params)
    {
        $id = $params["id"];

        AdvertisingMedia::destroy($id);
    }

    public function search_result($medias, $params)
    {
        mb_language('ja');
        mb_internal_encoding('utf-8');

        $search_params=[];
        if (isset($params['media_code'])) {
            $code = $params['media_code'];
            $search_params['media_code'] = mb_convert_kana($code, 'a');
            $medias->where('advertising_media.code', $code);
        }
        if (isset($params['media_name'])) {
            $name = $params['media_name'];
            $search_params['media_name'] = $name;
            $medias->where('advertising_media.name', 'like', '%' . $name . '%');
        }
        if (isset($params['media_date_from'])) {
            $date_from = $params['media_date_from'];
            $search_params['media_date_from'] = $date_from;
            $medias->where('advertising_media.date', '>=', $date_from);
        }
        if (isset($params['media_date_to'])) {
            $date_to = $params['media_date_to'];
            $search_params['media_date_to'] = $date_to;
            $date_to = Carbon::createFromFormat('Y-m-d', $date_to)->endOfDay();
            $medias->where('advertising_media.date', '<=', $date_to);
        }
        if (isset($params['media_code_group'])) {
            $media_code_groups = $params['media_code_group'];
            $media_code_groups = array_map('intval', $media_code_groups);
            $search_params['media_code_group'] = $media_code_groups;

            $medias->where(function($query) use ($media_code_groups) {
                $index = 0;
                foreach($media_code_groups as $media_code_group) {
                    if ($media_code_group < 10000) {
                        if ($index == 0) {
                            $query->where([['advertising_media.code', '>=', $media_code_group], ['advertising_media.code', '<=', $media_code_group + 999]]);
                        }
                        else {
                            $query->orWhere([['advertising_media.code', '>=', $media_code_group], ['advertising_media.code', '<=', $media_code_group + 999]]);
                        }
                    }
                    else {
                        if ($index == 0) {
                            $query->where([['advertising_media.code', '>=', $media_code_group], ['advertising_media.code', '<=', $media_code_group + 9999]]);
                        }
                        else {
                            $query->orWhere([['advertising_media.code', '>=', $media_code_group], ['advertising_media.code', '<=', $media_code_group + 9999]]);
                        }
                    }

                    $index = $index + 1;
                }
            });
        }
        if (isset($params['item_lineup_id'])) {
            $item_lineup_id = $params['item_lineup_id'];
            $item_lineup_id = array_map('intval', $item_lineup_id);
            $search_params['item_lineup_id'] = $item_lineup_id;
            $medias->whereIn('advertising_media.item_lineup_id', $item_lineup_id);
        }
        if (isset($params['media_summary_group'])) {
            $media_summary_group = $params['media_summary_group'];
            $media_summary_group = array_map('intval', $media_summary_group);
            $search_params['media_summary_group'] = $media_summary_group;
            $medias->whereIn('advertising_media_summary_group_advertising_media.advertising_media_summary_group_id', $media_summary_group);
        }

        if (isset($params['sort'])) {
            $sort = $params['sort'];
            $search_params['sort'] = $sort;
            if (!strcmp($sort, 'code_asc')) {
                $medias->orderBy('advertising_media.code', 'ASC');
            }
            else if (!strcmp($sort, 'code_desc')) {
                $medias->orderBy('advertising_media.code', 'DESC');
            }
            else if (!strcmp($sort, 'date_asc')) {
                $medias->orderBy('advertising_media.date', 'ASC');
            }
            else if (!strcmp($sort, 'date_desc')) {
                $medias->orderBy('advertising_media.date', 'DESC');
            }
            else if (!strcmp($sort, 'customer_count_asc')) {
                $medias->orderBy(DB::Raw('coalesce(media_customer_counts.customer_count, 0)'), 'ASC');
            }
            else if (!strcmp($sort, 'customer_count_desc')) {
                $medias->orderBy(DB::Raw('coalesce(media_customer_counts.customer_count, 0)'), 'DESC');
            }
        }

        if (isset($params['page'])) {
            $search_params['page'] = $params['page'];
        }

        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $search_params['number_per_page'] = $number_per_page;

        return $search_params;
    }
}