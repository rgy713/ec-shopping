<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/25/2019
 * Time: 6:55 PM
 */

namespace App\Services\Batch;


use App\Events\Batch\CreateAspMediaCodeWarning;
use App\Models\AdvertisingMedia;
use App\Models\AdvertisingMediaSummaryGroupAdvertisingMedia;
use App\Models\AspMedia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateAspMediaCodeService
{
    public function run($year_month = null, $asp_media_id = null ) : int
    {
        $exception_count = 0;

        if(!isset($year_month)){
            $start_time = Carbon::now()->addMonth()->startOfMonth();
            $end_time = Carbon::now()->addMonths(2)->startOfMonth();
        }
        else{
            $start_time = Carbon::createFromFormat("Ym", $year_month)->startOfMonth();
            $end_time = Carbon::createFromFormat("Ym", $year_month)->addMonth()->startOfMonth();
        }

        $asp_media_ids = [];
        if(isset($asp_media_id))
            $asp_media_ids[] = $asp_media_id;
        else{
            $asp_media_ids = DB::table("asp_media")
                ->select("id")
                ->where("enabled", true)
                ->get()->pluck("id")->toArray();
        }

        if(count($asp_media_ids) == 0)
            return -1;

        $asp_media_list = app(AspMedia::class)->whereIn("id", array_map("intval", $asp_media_ids))->get();

        $min_advertising_media_code = DB::table(DB::raw('generate_series(70000, 79999) as codes'))
            ->whereRaw(sprintf('codes not in (%s)', DB::table('advertising_media')->select('code')->toSql()))
            ->min('codes');

        //バッチ処理を中止
        if(!isset($min_advertising_media_code))
            return -1;

        $advertising_media_code_count = DB::table(DB::raw('generate_series(70000, 79999) as codes'))
            ->select('codes')
            ->whereRaw(sprintf('codes not in (%s)', DB::table('advertising_media')->select('code')->toSql()))
            ->count();

        if($advertising_media_code_count < count($asp_media_ids) * 12)
            event(new CreateAspMediaCodeWarning());

        foreach($asp_media_list as $asp_media){
            $advertising_media = app(AdvertisingMedia::class)
                ->where("asp_flag", true)
                ->where("asp_name", $asp_media->name)
                ->where("asp_from", $start_time)
                ->where("asp_to", $end_time)
                ->first();

            if(isset($advertising_media))
                continue;

            $advertising_media = new AdvertisingMedia();

            $min_advertising_media_code = DB::table(DB::raw('generate_series(70000, 79999) as codes'))
                ->whereRaw(sprintf('codes not in (%s)', DB::table('advertising_media')->select('code')->toSql()))
                ->min('codes');

            //バッチ処理を中止
            if(!isset($min_advertising_media_code))
                return -1;

            try {
                $advertising_media->code = $min_advertising_media_code;
                $name = "{$start_time->format("Y.m")} アフィリエイト:{$asp_media->name}（{$asp_media->remark1}）";
                $advertising_media->name = $name;
                $advertising_media->media_type_id = 4;
                $advertising_media->date = date($start_time);
                $advertising_media->start_time = "00:00:00";
                $advertising_media->cost = $asp_media->default_cost;
                $advertising_media->item_lineup_id = $asp_media->default_item_lineup_id;
                $advertising_media->remark = "バッチ処理により自動作成（対象月：{$start_time->format("Y.m")}）";
                $advertising_media->creator_admin_id = 0;
                $advertising_media->asp_flag = true;
                $advertising_media->asp_name = $asp_media->name;
                $advertising_media->asp_from = $start_time;
                $advertising_media->asp_to = $end_time;

                $advertising_media->save();
            }
            catch (\Exception $e){
                $exception_count += 1;
                continue;
            }

            if(strpos($name, "ギャプライズ") || strpos($name, "ヴァンクリーフ") || strpos($name, "エーワークス")){
                try{
                    $advertising_media_summary_group_advertising_media = new AdvertisingMediaSummaryGroupAdvertisingMedia();
                    $advertising_media_summary_group_advertising_media->advertising_medium_id = $advertising_media->id;

                    if(strpos($name, "ギャプライズ"))
                        $advertising_media_summary_group_id = 2;
                    elseif(strpos($name, "ヴァンクリーフ"))
                        $advertising_media_summary_group_id = 3;
                    elseif(strpos($name, "エーワークス"))
                        $advertising_media_summary_group_id = 4;

                    $advertising_media_summary_group_advertising_media->advertising_media_summary_group_id = $advertising_media_summary_group_id;
                    $advertising_media_summary_group_advertising_media->save();
                }catch (\Exception $e){
                    $exception_count += 1;
                }
            }
        }

        return $exception_count;
    }
}