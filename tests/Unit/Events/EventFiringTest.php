<?php


namespace Tests\Unit;


use App\Common\OperationAdministrator;
use App\Events\AdditionalShippingAddressModifiedByCustomer;
use App\Events\AdditionalShippingAddressRegisteredByCustomer;
use App\Events\CustomerAddressModifiedByCustomer;
use App\Mail\TemplateMail;
use App\Models\AdditionalShippingAddress;
use App\Models\Customer;
use App\Notifications\CustomerAddressModifiedNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * イベント発火テスト
 * Class EventFiringTest
 * @package Tests\Unit
 * @author k.yamamoto@balocco.info
 */
class EventFiringTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * TODO:テストの実装
     * @author k.yamamoto@balocco.info
     */
    public function testAdditionalShippingAddressModifiedByCustomer()
    {
//        //1.
//        $models = factory(AdditionalShippingAddress::class, 'for_dev', 1)->create();
//        /** @var AdditionalShippingAddress $model */
//        $model = $models->get(0);
//        $model->name01 = '苗字を変更しました';
//        event(new AdditionalShippingAddressModifiedByCustomer($model->getOriginal(), $model->getAttributes()));
//
//        //2.
//        $models = factory(AdditionalShippingAddress::class, 'for_dev', 1)->create();
//        /** @var AdditionalShippingAddress $model */
//        $model = $models->get(0);
//        $model->address01 = '住所をを変更しました。メールが飛ぶはず';
//        event(new AdditionalShippingAddressModifiedByCustomer($model->getOriginal(), $model->getAttributes()));

    }

    /**
     * TODO:テストの実装
     * @author k.yamamoto@balocco.info
     */
    public function testAdditionalShippingAddressRegisteredByCustomer()
    {
//        $models = factory(AdditionalShippingAddress::class, 'for_dev', 1)->create();
//        event(new AdditionalShippingAddressRegisteredByCustomer($models->get(0)));
    }


}