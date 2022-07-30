# 学習中のメモ

## middlewareの一覧
/app/Http/Kernel.php

## controllerの作成
php artisan make:controller yourContoroller

## コンポーネントについて
### コンポーネントは2つある
***コンポーネントクラス***と***Bladeコンポーネント***
### 配置場所とタグ
```
フォルダを作成しない場合
resouces/views/yourComponents

<x-yourComponents></x-yourComponents>
```
```
フォルダを作成する場合
resouces/views/components/yourFolder/yourComponents

<x-yourFolder.yourComponents></x-yourFolder.yourComponents>
```

### 名前付きスロット
```
<x-youorFolder.yourComponents>
    <x-slot name="variable">value</x-slot>
</x-youorFolder.yourComponents>
```

### 変数
``` php
    //controller
    public function showComponent1() {
        $name = "Jhon doe";
        return view('tests.component-test1', compact('name'));
    }

    //component
    <x-tests.card :name="$name">value</x-tests.card>

    //blade
    <div>{{ $name }}</div>
```