<?php

namespace App\Http\Controllers\Admin\Order;

use App\Services\DeliverySlipService;
use App\Services\FlashToastrMessageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class DeliverySlipController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(DeliverySlipService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index()
    {
        return view("admin.pages.order.delivery_slip");
    }

    public function import(Request $request)
    {
        $params = $request->all();

        if(isset($params["file1"]))
            $params["file1_extension"] = strtolower($params['file1']->getClientOriginalExtension());

        if(isset($params["file2"]))
            $params["file2_extension"] = strtolower($params['file2']->getClientOriginalExtension());

        $messages = [
            'file1_extension.in'=>'csvファイルを入力してください。',
            'file2_extension.in'=>'csvファイルを入力してください。',
        ];

        $validator = Validator::make($params, [
            'file1'=>['required'],
            'file1_extension' => ['required','in:csv'],
            'file2'=>['nullable'],
            'file2_extension' => ['nullable', 'required_with:file2', 'in:csv'],
        ], $messages);


        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back()->withErrors($validator)->withInput();
        }

        $error = "";
        $import_data = [];
        $file_path = $params["file1"]->path();
        $line = 0;
        $handle = fopen($file_path, "r");

        $order_ids=[];
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $line++;
            if(count($data)!=3){
                $error.="line-{$line}: 1行につき3列であることを検証 \n";
                break;
            }
            if($line==1){
                if("受注番号" != mb_convert_encoding($data[0], "UTF-8", "SJIS")){
                    $error.="{$line}行: 第一列は'受注番号' <br/>";
                }
                if("伝票番号" != mb_convert_encoding($data[1], "UTF-8", "SJIS")){
                    $error.="{$line}行: 第二列は'伝票番号' <br/>";
                }
                if("出荷日" != mb_convert_encoding($data[2], "UTF-8", "SJIS")){
                    $error.="{$line}行: 第三列は'出荷日' <br/>";
                }
                if($error)
                    break;
                continue;
            }

            $order_id = $data[0];
            $delivery_slip_num = $data[1];
            $datetime = $data[2];

            $messages = [
                'order_id' => __('validation.hint_text.not_exist_deleted'),
            ];

            $validator = Validator::make([
                'order_id'=>$order_id
            ], [
                'order_id'=>['required', 'exists:orders,id,deleted_at,NULL'],
            ], $messages);

            if ($validator->fails()) {
                $error.="{$line}行: ".implode(" ",$validator->messages()->all())."<br/>";
                break;
            }

            if(isset($order_ids[$order_id])){
                $error .= "{$line}行: 受注番号{$order_id}が{$order_ids[$order_id]}行きと重複されました。<br/>";
                break;
            }

            $order_ids[$order_id] = $line;
            $import_data[]=[
                "order_id"=>$order_id,
                "delivery_slip_num"=>$delivery_slip_num
            ];
        }

        fclose($handle);

        if($error){
            $this->toastr->add('error', "失敗しました。");
            return Redirect::back()->withErrors(["error_msg1"=> $error]);
        }


        if(isset($params["file2"])){
            $file_path = $params["file2"]->path();
            $line = 0;
            $handle = fopen($file_path, "r");

            $order_ids=[];
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $line++;
                if(count($data)!=3){
                    $error.="line-{$line}: 1行につき3列であることを検証 <br/>";
                    break;
                }
                if($line==1){
                    if("受注番号" != mb_convert_encoding($data[0], "UTF-8", "SJIS")){
                        $error.="{$line}行: 第一列は'受注番号' <br/>";
                    }
                    if("伝票番号" != mb_convert_encoding($data[1], "UTF-8", "SJIS")){
                        $error.="{$line}行: 第二列は'伝票番号' <br/>";
                    }
                    if("出荷日" != mb_convert_encoding($data[2], "UTF-8", "SJIS")){
                        $error.="{$line}行: 第三列は'出荷日' <br/>";
                    }
                    if($error)
                        break;
                    continue;
                }

                $order_id = $data[0];
                $delivery_slip_num = $data[1];
                $datetime = $data[2];

                $messages = [
                    'order_id' => __('validation.hint_text.not_exist_deleted'),
                ];

                $validator = Validator::make([
                    'order_id'=>$order_id
                ], [
                    'order_id'=>['required', 'exists:orders,id,deleted_at,NULL'],
                ], $messages);

                if ($validator->fails()) {
                    $error.="{$line}行: ".implode(" ",$validator->messages()->all())."<br/>";
                    break;
                }

                if(isset($order_ids[$order_id])){
                    $error .= "{$line}行: 受注番号{$order_id}が{$order_ids[$order_id]}行きと重複されました。<br/>";
                    break;
                }

                $order_ids[$order_id] = $line;
                $import_data[]=[
                    "order_id"=>$order_id,
                    "delivery_slip_num"=>$delivery_slip_num
                ];
            }

            fclose($handle);
        }

        if($error){
            $this->toastr->add('error', "失敗しました。");
            return Redirect::back()->withErrors(["error_msg2"=> $error]);
        }

        try {
            $this->service->import($import_data);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }
}
