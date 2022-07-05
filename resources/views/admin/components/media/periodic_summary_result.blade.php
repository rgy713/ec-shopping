{{-- 定期分析 --}}
<div class="card">
    <div class="card-header">
        定期分析結果
        <div class="card-header-actions">
            <button class="btn btn-sm btn-primary"><i class="fa fa-print"></i>&nbsp;印刷/PDF</button>
            <button class="btn btn-sm btn-primary">総合分析</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            @each('admin.components.media.periodic_summary_result_item', $data, 'summary')
        </div>
    </div>
</div>
