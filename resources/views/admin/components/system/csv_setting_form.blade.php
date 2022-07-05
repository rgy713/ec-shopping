{{-- CSV出力項目設定 --}}
@inject('csvTypeList', 'App\Common\KeyValueLists\CsvTypeList')

<div class="card">
    <div class="card-header">
        {{$csvTypeList[$csvType->id]}} 出力項目

        <div class="card-header-actions">
            <a class="card-header-action btn-close" href="#">
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-2 text-center" style="padding-top:50px;">
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="double_up()"><i class="fa fa-angle-double-up"></i></button></div>
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="up()"><i class="fa fa-angle-up"></i></button></div>
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="down()"><i class="fa fa-angle-down"></i></button></div>
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="double_down()"><i class="fa fa-angle-double-down"></i></button></div>
            </div>
            <div class="col-4">
                <p>出力する項目</p>
                <select id="output_enabled" class="form-control form-control-sm" size="20" multiple>
                    @foreach($csvType->csvOutputSettings()->where("enabled", true)->orderBy('rank')->get() as $one)
                        <option value="{{$one->id}}">{{$one->item_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-2 text-center" style="padding-top:50px;">
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="right()"><i class="fa fa-angle-right"></i></button></div>
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="double_right()"><i class="fa fa-angle-double-right"></i></button></div>
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="left()"><i class="fa fa-angle-left"></i></button></div>
                <div class="mb-3"><button class="btn btn-sm btn-block" type="button" v-on:click="double_left()"><i class="fa fa-angle-double-left"></i></button></div>
            </div>

            <div class="col-4">
                <p>出力しない項目</p>
                <select id="output_disabled" class="form-control form-control-sm" size="20" multiple >
                    @foreach($csvType->csvOutputSettings()->where("enabled", false)->orderBy('rank')->get() as $one)
                        <option value="{{$one->id}}">{{$one->item_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>