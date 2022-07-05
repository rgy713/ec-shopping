<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/19/2019
 * Time: 11:26 PM
 */

namespace App\Services;

use App\Models\MailLayout;

class MailLayoutService
{
    /**
     * @return MailLayout[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLayouts()
    {
        $layouts = MailLayout::all();
        return $layouts;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getLayout($id)
    {
        $layout = MailLayout::find($id);
        return $layout;
    }

    /**
     * @param $params
     */
    public function update($params)
    {
        $layout = MailLayout::find($params['id']);
        $layout->name = $params["mail_layout_name"];
        $layout->remark = $params["mail_layout_remark"];
        $layout_header_path = storage_path('app/views/' . str_replace('.', '/', $layout->header_file_path)) . '.blade.php';
        file_put_contents($layout_header_path, $params["mail_layout_header"]);
        $layout_footer_path = storage_path('app/views/' . str_replace('.', '/', $layout->footer_file_path)) . '.blade.php';
        file_put_contents($layout_footer_path, $params["mail_layout_footer"]);
        $layout->save();
    }

    /**
     * @param $params
     */
    public function create($params)
    {
        $max_id = MailLayout::select("id")->orderBy("id", "DESC")->first();
        $layout = new MailLayout();
        $layout->id = $max_id->id + 1;
        $layout->name = $params["mail_layout_name"];
        $layout->remark = $params["mail_layout_remark"];
        $layout->header_file_path = "mail.layouts." . $layout->id . "_header";
        $layout->footer_file_path = "mail.layouts." . $layout->id . "_footer";
        $layout_header_path = storage_path('app/views/' . str_replace('.', '/', $layout->header_file_path)) . '.blade.php';
        file_put_contents($layout_header_path, $params["mail_layout_header"]);
        $layout_footer_path = storage_path('app/views/' . str_replace('.', '/', $layout->footer_file_path)) . '.blade.php';
        file_put_contents($layout_footer_path, $params["mail_layout_footer"]);
        $layout->save();
    }
}