<?php

use Illuminate\Database\Seeder;

class InitTagPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model=new \App\Models\Masters\TagPosition();
        $model->id=1;
        $model->name='</body>タグの直前';
        $model->rank=1;
        $model->save();

        $model=new \App\Models\Masters\TagPosition();
        $model->id=2;
        $model->name='</head>タグの直前';
        $model->rank=2;
        $model->save();

    }
}
