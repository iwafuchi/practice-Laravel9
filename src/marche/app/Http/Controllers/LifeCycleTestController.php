<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Sample;
use App\Http\Controllers\Message;

class LifeCycleTestController extends Controller {
    //
    public function showServiceContainerTest() {
        app()->bind('lifeCycleTest', function () {
            return 'ライフサイクルテスト';
        });

        /**
         * サービスコンテナなしのパターン
         * SampleClassを
         */
        $sample1 = new Sample(new Message());
        $sample1->run('サービスコンテナを使用していません。');

        /**
         * サービスコンテナapp()ありのパターン
         * SampleClassはMessageClassに依存していたがサービスコンテナapp()を使用することで自動的に依存関係を解消してくれている。
         */
        app()->bind('sample', Sample::class);
        $sample2 = app()->make('sample');
        $sample2->run("サービスコンテナを使用しています。");

        $make = app()->make('lifeCycleTest');

        dd($make, $sample2, app());
    }
}
