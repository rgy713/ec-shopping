<nav aria-label="Page navigation example">
    @if (isset($results) && method_exists($results, 'links')) {{ $results->links('admin.components.common.pagination_view')}} @endif
</nav>

