<?php

namespace App\Services;

use App\Models\AspMedia;

class AspService
{
    public function createSave($params)
    {
        $asp_media = new AspMedia();
        $asp_media->name = $params["asp_name"];
        $asp_media->default_item_lineup_id = $params["asp_default_item_lineup_id"];
        $asp_media->default_cost = $params["asp_default_cost"];
        $asp_media->remark1 = $params["asp_remark1"];
        $asp_media->enabled = $params["asp_enabled"];
        $asp_media->save();
    }

    public function editSave($params)
    {
        $asp_ids = $params["asp_id"];
        $asp_default_item_lineup_id = $params["asp_default_item_lineup_id"];
        $asp_default_cost = $params["asp_default_cost"];
        $asp_remark1 = $params["asp_remark1"];
        $asp_enabled = $params["asp_enabled"];

        foreach ($asp_ids as $id) {
            $asp_media = AspMedia::find($id);
            if (isset($asp_default_item_lineup_id[$id]))
                $asp_media->default_item_lineup_id = $asp_default_item_lineup_id[$id];

            if (isset($asp_default_cost[$id]))
                $asp_media->default_cost = $asp_default_cost[$id];

            if (isset($asp_remark1[$id]))
                $asp_media->remark1 = $asp_remark1[$id];

            if (isset($asp_enabled[$id]))
                $asp_media->enabled = $asp_enabled[$id];

            $asp_media->save();
        }

    }
}