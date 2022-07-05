<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\MailTemplateType;

class InitMailTemplateTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new MailTemplateType();
        $model->id=1;
        $model->name="システムメール用テンプレート";
        $model->save();

        $model = new MailTemplateType();
        $model->id=2;
        $model->name="ステップメール用テンプレート";
        $model->save();

    }
}
