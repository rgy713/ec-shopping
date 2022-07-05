{{-- 検索フォームのボタン --}}
@inject('searchResultNumList', 'App\Common\KeyValueLists\SearchResultNumList')

<div class="row">
    {{-- 表示件数 --}}
    <div class="col-4 col-lg-4">
        <div class="row form-group">
            <div class="col-12">
                <select id="number_per_page" class="form-control form-control-sm" name="number_per_page">
                    @foreach($searchResultNumList as $id => $name)
                        <option value="{{$id}}" @if(isset($search_params) && ($search_params['number_per_page'] == $id)) selected @endif>{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-4 col-lg-4"><button class="btn btn-sm btn-primary btn-block" @click.prevent="search_result_new"><i class="fa fa-search"></i>&nbsp;{{__("admin.operation.search")}}</button></div>
    <div class="col-4 col-lg-4"><button class="btn btn-sm btn-secondary btn-block" tabindex="-1" @click.prevent="reset_form"><i class="fa fa-remove"></i>&nbsp;{{__("admin.operation.reset")}}</button></div>
</div>
