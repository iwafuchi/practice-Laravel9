# 学習中のメモ

## middleware の一覧

/app/Http/Kernel.php

## controller の作成

``` php
php artisan make:controller yourContoroller
```

## コンポーネントについて

### コンポーネントは 2 つある

**_コンポーネントクラス_**と**_Blade コンポーネント_**

### componentsの作成場所とタグの記述方法

```php
//コンポーネントを作成する コンポーネント名はアッパーキャメルケースで指定する
php artisan make:component YourComponent

```

これらのファイルが作成される

- resources/views/components/select-box.blade.php … コンポーネントのビューを記述するBlade
- app/Views/Components/SelectBox.php … コンポーネントのロジックを処理するビハインドコード。

``` php
フォルダを作成しない場合
resouces/views/yourComponents

<x-yourComponents></x-yourComponents>
```

``` php
フォルダを作成する場合
resouces/views/components/yourFolder/yourComponents

<x-yourFolder.yourComponents></x-yourFolder.yourComponents>
```

### 名前付きスロット

```php
//layout
<x-youorFolder.yourComponents>
    <x-slot name="variable">value</x-slot>
</x-youorFolder.yourComponents>
```

### 変数

```php
//controller
class ComponentTestController extends Controller {
    public function showComponent1() {
        $name = "Jhon doe";
        return view('tests.component-test1', compact('name'));
    }
}
//layout
<div>{{ $name }}</div>

//component
<x-tests.card :name="$name">value</x-tests.card>
```

### 初期値(@props)

```php
//layout
<x-tests.card title="タイトル"></x-tests.card>

//component
@props(['title' => 'タイトルを設定して下さい', 'content' => '本文を設定して下さい', 'message' => 'メッセージを設定して下さい'])

```

blade で設定しなかった変数は@props の値が使用される。  
@props を設定する際は component 内の全ての変数の初期値を持つ必要がある

### componentのcssを変更する

``` php
//layout
<x-tests.card title="CSSを変更" class="bg-red-300"></x-tests.card>

//components
<div {{ $attributes->merge(['class' => 'border-2 shadow-md w-1/4 p-2']) }}</div>
```

### クラスベースのコンポーネント

``` php
php artisan make:component TestClassBase
//components
public function render() {
    return view('components.test-class-base-component');
}
//layout
<x-test-class-base></x-test-class-base>
```

### クラスベースコンポーネントで属性を設定する

``` php
//app/views/componenta
class TestClassBase extends Component {
    public $classBaseMessage;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($classBaseMessage) {
        //
        $this->classBaseMessage = $classBaseMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.tests.test-class-base-component');
    }
}

//resoucrces//views/layout
<x-test-class-base classBaseMessage="メッセージ"></x-test-class-base>

//componentsの引数とlayoutの属性の変数名が同一である必要がある
//cacheが残って画面が更新されない場合はphp artisan view:clear
```

## サービスコンテナについて

簡単に説明するとClassをインスタンス化してくれる機能  
依存関係を自動的に解決してくれるのでコードが簡潔に書ける  

``` php
// MessageClass
Class Message(){
    public function send($text){
        echo $text;
    }
}
// UserClass
Class User(){
    public $userName = "user";
    public $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }
    public function send($text){
        $this->message->send($this->userName,$text);
    }
}
//UserClassはMessageClassに依存している状態
//サービスコンテナを使用しない書き方
$message = new Message();
$user = new User($message);
$user->send("サービスコンテナを使用していません。");

//サービスコンテナを使用した書き方
app()->bind('user', User::class);
$user = app()->make('user');
$user->run("サービスコンテナを使用しています。");
```
