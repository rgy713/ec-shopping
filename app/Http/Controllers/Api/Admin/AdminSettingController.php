<?php


namespace App\Http\Controllers\Api\Admin;

use App\Common\Api\ApiResponseData;
use App\Services\AdminSettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminSettingController extends BaseController
{
    /** @var AdminSettingService $service */
    protected $service;

    /**
     * @param AdminSettingService $service
     * @return \Illuminate\Http\JsonResponse
     * @author k.yamamoto@balocco.info
     */
    public function getOptions(AdminSettingService $service)
    {
        $item = collect($service->getSettings());
        return response()->json($item);
    }

    /**
     * @param AdminSettingService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author k.yamamoto@balocco.info
     */
    public function saveOptionLeftMenu(AdminSettingService $service, Request $request)
    {
        $responseData = new ApiResponseData($request);
        $value = $request->post('value');

        try {
            //データ更新
            $result = $service->saveLeftMenuOption($value);

            $responseData->message = "左メニューオプション設定を" . ($value ? "on" : "off") . "に変更しました。";
            $responseData->saved = $result->toArray();
            $responseData->status = "success";
            return response()->json($responseData);
        } catch (\Exception $e) {
            throw $e;
        }


    }

    /**
     * @param AdminSettingService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author k.yamamoto@balocco.info
     */
    public function saveOptionRightMenu(AdminSettingService $service, Request $request)
    {
        $responseData = new ApiResponseData($request);
        //TODO:validation
        $value = $request->post('value');

        try {
            $result = $service->saveRightMenuOption($value);
            $responseData->message = "右メニューオプション設定を" . ($value ? "on" : "off") . "に変更しました。";
            $responseData->saved = $result->toArray();
            $responseData->status = "success";
            return response()->json($responseData);
        } catch (\Exception $e) {
            throw $e;
        }


    }

}