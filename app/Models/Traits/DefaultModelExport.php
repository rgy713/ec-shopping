<?php

namespace App\Models\Traits;

use App\Models\Masters\CsvType;
use App\Models\Masters\CsvOutputSetting;
use Illuminate\Support\Facades\DB;

trait DefaultModelExport
{
    protected $csv_type_id;
    protected $query;

    public function collection()
    {
        $return_fields = CsvOutputSetting::select('col')->where([['csv_type_id', $this->csv_type_id], ['enabled', true]])->orderby('rank')->get();
        foreach ($return_fields as $field) {
            $this->query->addSelect(DB::raw($field['col']));
        }

        return $this->query->get()->toArray();
    }

    public function headings(): array
    {
        $headings = CsvOutputSetting::select('item_name')->where([['csv_type_id', $this->csv_type_id], ['enabled', true]])->orderby('rank')->get()->pluck('item_name')->all();

        return $headings;
    }

    public function filename()
    {
        return CsvType::select('name')->where('id', $this->csv_type_id)->first();
    }

    public function download()
    {
        $file_name = $this->filename();

        if (isset($file_name["name"])) {
            $callback = function () {

                $createCsvFile = fopen('php://output', 'w'); //ファイル作成

                $csv_header = $this->headings();

                mb_convert_variables('SJIS-win', 'UTF-8', $csv_header); //文字化け対策

                fputcsv($createCsvFile, $csv_header); //Header情報を追記

                foreach ($this->collection() as $row) {
                    $line = [];

                    foreach ($row as $key => $value) {
                        $line[] = $value;
                    }

                    mb_convert_variables('SJIS-win', 'UTF-8', $line);

                    fputcsv($createCsvFile, $line);
                }

                fclose($createCsvFile); //ファイル閉じる
            };

            $headers = [ //ヘッダー情報
                'Content-type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename=' . $file_name["name"] . '.csv',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return response()->stream($callback, 200, $headers); //ここで実行
        }
    }
}