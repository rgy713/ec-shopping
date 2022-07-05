<div class="row">

</div>

<pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="search_results()"></pagination>
<table class="table table-sm table-bordered">
    <tr>
        <th class="table-secondary">{{__('admin.item_name.product.code')}}</th>
        <th class="table-secondary">{{__('admin.item_name.product.name')}}</th>

        <th class="table-secondary"></th>
    </tr>

    <tr class="" v-for="product in results">
        <td>@{{ product.code }}</td>
        <td>@{{ product.name }}</td>
        <td>
            <button class="btn btn-sm btn-primary btn-block" data-dismiss="modal" @click="select_product(product.id)">
                {{__('admin.operation.select')}}
            </button>
        </td>
    </tr>

</table>
<pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="search_results()"></pagination>