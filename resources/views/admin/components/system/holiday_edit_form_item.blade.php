@inject('DayOfWeekList', 'App\Common\KeyValueLists\DayOfWeekList')
<tr>
    <input type="hidden" name="ids[]" value="{{$holiday['id']}}">
    <td></td>
    <td>{{$holiday['date']}} {{$DayOfWeekList[\Carbon\Carbon::parse($holiday['date'])->dayOfWeek]}}</td>

    <td>
        <input class="form-control form-control-sm @if($errors->has('date.'.$holiday['id']))is-invalid @endif" type="date" name="date[{{$holiday['id']}}]" value="@if(old('date.'.$holiday['id'])){{old('date.'.$holiday['id'])}}@else{{$holiday['date']}}@endif" required/>
        <div class="invalid-feedback">{{$errors->first('date.'.$holiday['id'])}}</div>
    </td>
    <td>
        <input class="form-control form-control-sm @if($errors->has('name.'.$holiday['id']))is-invalid @endif" type="text" name="name[{{$holiday['id']}}]" value="@if(old('name.'.$holiday['id'])){{old('name.'.$holiday['id'])}}@else{{$holiday['name']}}@endif" onchange="return app.functions.trim(this);" required/>
        <div class="invalid-feedback">{{$errors->first('name.'.$holiday['id'])}}</div>
    </td>
    <td>
        <div class="col-sm-8 col-form-label col-form-label-sm">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="delete_ids[]" value="{{$holiday['id']}}">
            </div>
        </div>
    </td>
</tr>
