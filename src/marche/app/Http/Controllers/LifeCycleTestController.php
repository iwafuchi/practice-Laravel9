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
        $make = app()->make('lifeCycleTest');

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

        dd($make, $sample1, $sample2, app());
    }

    public function showServiceProviderTest() {
        $encrypt = app()->make('encrypter');
        $password = $encrypt->encrypt('password');

        $sample = app()->make('serviceProviderTest');
        dd($password, $encrypt->decrypt($password), $sample);
    }
}
