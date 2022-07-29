## middlewareの一覧
/app/Http/Kernel.php

## controllerの作成
php artisan make:controller yourContoroller

## コンポーネントについて
### コンポーネントは2つある
コンポーネントクラスとBladeコンポーネント
sec02_componentPreparation
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