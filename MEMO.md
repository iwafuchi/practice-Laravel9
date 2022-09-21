# 学習中のメモ

後でまとめる

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
        //view側に変数を渡すにはcompactメソッドを使用する
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

## サービスプロバイダーについて

config/app.php内のproviders配列で読み込んでいる

``` php
//providerを作成するコマンド
php artisan make:provider YourServiceProvider

class SampleServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //registerメソッドはサービスコンテナにサービスを登録するコードを記述する
        app()->bind('serviceProviderTest', function () {
            return 'サービスプロバイダーのテスト';
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        //bootメソッドは全てのサービスプロバイダーが読み込まれた後に実行したいコードを記述する
    }
}

//config/app.php
   'providers' => [
        //etc...
        //使用したいプロバイダーを登録する
        App\Providers\SampleServiceProvider::class,
    }

//Http/Controller/SampleController.php
    public function showServiceProviderTest() {
        $sample = app()->make('serviceProviderTest');
        dd($sample);
    }
```

## php artisan

``` php
//modelを作成する
//-mでmigrationも生成される
php artisan make:model Sample -m

//migrationを作成する
php artisan make:migration sample_migration_file
```

## Route

``` php
    //prefix
    //prefixを付けることでRouteの設定の際にグループ化できる
    //admin/users admin/owner admin/etc...
    //adminから始まるRouteが複数ある際に一纏めに出来る
    Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        // /admin/usersのURLに一致
        });
    });

    //middleware
    //middlewareとはクライアントからのリクエストがコントローラーのアクションに届く前後に配置されるプログラム
    //前実行 client → route → middleware → controller → view → client
    //後実行 client → route → controller → view → middleware → client
    Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // １番目と２番目のミドルウェアを使用
    });

    Route::get('/user/profile', function () {
        // １番目と２番目のミドルウェアを使用
    });
});
```

## Guard

Laravel標準の認証機能：リクエストごとにユーザーを認証する方法
config/auth.phpで設定する

``` php
    //config/auth.phpのguards配列で定義する
    'guards' => [
        'users' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    //Routeでmiddlewareを呼び出し、指定したGuardで認証されたユーザーだけにアクセスを許可する
    Route::get('mypage',function(){
    })->middleware('auth:users');
```

## Middleware/Authenticate

ユーザーが未認証の場合のリダイレクト処理

``` php
//app/Http/Middleware//Authenticate.php
//リダイレクト処理を記述するファイル
class Authenticate extends Middleware {
    //route変数はログイン後にリダイレクトされるパスを指定する
    protected $userRoute = 'user.login';
    protected $ownerRoute = 'owner.login';
    protected $adminRoute = 'admin.login';
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            //Route::isで設定するURIはapp/Providers/RouteServiceProvider.phpで設定した値
            //今回はasで別名をしていしているのでその値を使用する
            if (Route::is('owner.*')) {
                return route($this->ownerRoute);
            }
            if (Route::is('admin.*')) {
                return route($this->adminRoute);
            }
            return route($this->userRoute);
        }
    }
}

//app/Providers/RouteServiceProvider.php
public function boot() {
    $this->configureRateLimiting();
    $this->routes(function () {
        Route::prefix('/')
            ->as('user.')
            ->middleware('web')
            ->group(base_path('routes/web.php'));
        Route::prefix('owner')
            ->as('owner.')
            ->middleware('web')
            ->group(base_path('routes/owner.php'));
        Route::prefix('admin')
            ->as('admin.')
            ->middleware('web')
            ->group(base_path('routes/admin.php'));
    });
}
```

## Middleware/RedirectlfAuthenticated

ログイン済みのユーザーがアクセスした場合のリダイレクト処理を記述する

```php
//Authファサードのguardメソッドを介して、ユーザーを認証するときに利用するガードインスタンスを指定できる
Auth::guard('admin')

//現在のユーザーがログイン済みか判定する
Auth::check()

//app/Http/Middleware/RedirectIfAuthenticated.php
//ユーザーがログイン済みかつrouteが合っている場合はRouteServiceProvider
class RedirectIfAuthenticated {
    private const GUARD_USERS = 'users';
    private const GUARD_OWNERS = 'owners';
    private const GUARD_ADMINS = 'admins';    public function handle(Request $request, Closure $next, ...$guards) {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }
        if (Auth::guard((self::GUARD_USERS))->check() && $request->routeIs('users.*')) {
            return redirect(RouteServiceProvider::HOME);
        }

        if (Auth::guard((self::GUARD_OWNERS))->check() && $request->routeIs('owners.*')) {
            return redirect(RouteServiceProvider::OWNERS_HOME);
        }

        if (Auth::guard((self::GUARD_ADMINS))->check() && $request->routeIs('admins.*')) {
            return redirect(RouteServiceProvider::ADMINS_HOME);
        }
        return $next($request);
    }
}

//app/Providers/RouteServiceProvider.php
class RouteServiceProvider extends ServiceProvider {
    public const HOME = '/dashboard';
    public const OWNERS_HOME = '/owner/dashboard';
    public const ADMINS_HOME = '/admin/dashboard';

    public function boot() {
        $this->configureRateLimiting();
        $this->routes(function () {
            Route::middleware('api')
                //prefixでURIの内容に沿って処理を分岐させる
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::prefix('/')
                ->as('user.')
                ->middleware('web')
                ->group(base_path('routes/web.php'));
            Route::prefix('owner')
                ->as('owner.')
                ->middleware('web')
                ->group(base_path('routes/owner.php'));
            Route::prefix('admin')
                ->as('admin.')
                ->middleware('web')
                ->group(base_path('routes/admin.php'));
    });
    }
}
```

## RequestClass

ログインフォームに入力された値からパスワードを比較し、認証する

``` php
//app/Http/Requests/Auth/LoginRequest.php
public function authenticate() {
    $this->ensureIsNotRateLimited();
    
    //マルチログインを行う為にURI毎にテーブルを変更している。
    if ($this->routeIs('owners.*')) {
            $guard = 'owners';
    } elseif ($this->routeIs('admins.*')) {
            $guard = 'admins';
    } else {
            $guard = 'users';
    }

    if (!Auth::guard($guard)->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
        ]);
    }
    
    RateLimiter::clear($this->throttleKey());
}
```

### URIの変更

``` php
//app/Providers/RouteServiceProvider.php
public function boot() {
    $this->configureRateLimiting();
    $this->routes(function () {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
        //prefixはURIで待ち受ける際の値(外部用のroute)この値を変更することでアクセスする際のURIを変更できる。
        Route::prefix('/')
        //asはguradで扱う値(内部用のroute)
            ->as('users.')
            ->middleware('web')
            ->group(base_path('routes/web.php'));
        Route::prefix('owners')
            ->as('owners.')
            ->middleware('web')
            ->group(base_path('routes/owner.php'));
        Route::prefix('admins')
            ->as('admins.')
            ->middleware('web')
            ->group(base_path('routes/admin.php'));
    });
}


//blade内でルーティングが存在するかチェックするguradを使用しているのでconfig/auth.phpを参照する
@if (Route::has('owners.login'))

//config/auth.php
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'users' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'owners' => [
            'driver' => 'session',
            'provider' => 'owners',
        ],
        'admins' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

    ],

//resources/views/tests/welcome.blade.php

<h1>Hello World !!!</h1>

//ここのhrefはrouteのtest
<a href="{{ url('/test/component-test1') }}">component-test1</a>
<a href="{{ url('/test/component-test2') }}">component-test2</a>

```

### Laravel Breeze register by database

```php
//app/Http/Controllers/Test/Auth?registerdUserController.php

- use App\Models\User;
+ use App\Models\Test;


        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tests'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        $user = Test::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

```

### Laravel Breeze Logout redirect

```php

class AuthenticatedSessionController extends Controller {
        public function destroy(Request $request) {
        //guardで設定した値
        Auth::guard('tests')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        //リダイレクト先のuri
        return redirect('/test');
    }
}

```

### publicフォルダにstorageフォルダをリンクさせる

Laravelは外部へ公開する為にデータを保存するとstorage/app/publicへ保存される
通常外部へ公開する為のフォルダはpublicディレクトリを使用する。
しかしユーザーが追加するファイルかつ、外部からアクセスが必要になる際はstorage/app/publicを利用する(ユーザーのプロフィール画像等)

```php
//publicディレクトリにシンボリックリンクを作成する
//docker環境化では参照時にエラーが発生してもリンクに成功していれば大丈夫。
//気になるのであればRemoteContainerで開発を行う。
php artisan storage:link

//blade.php
<img src="{{ asset('storage/user_icon.png') }}">
```

### リソースコントローラー

CRUD処理を簡潔にできる機能

```php
//生成コマンド --resourceオプションを指定する事で生成される
php artisan make:controller YourResourceController --resource

//userでログインした状態でのみリソースコントローラーを扱う例

//Route側
Route::resouce('product',YourResouceController::class)->middleware('auth:user');

//Controller側
class YourResouceController extends Controller {
    public function __construct(){
        $this->middleware('auth:user')
    }
}
```

### シーダー(ダミーデータ)の作成

シーダーを生成する

```php
//database/seeders 直下生成される
php artisan make:seeder YourSeeder
```

ダミーデータを設定する

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class YourSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //ダミーデータの値を設定する
    public function run() {
        DB::table('your_table')->insert([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'created_at' => '2022/01/01 00:00:00'
        ]);
    }
}
```

テーブルの再生成かつシーダーの追加
--seedオプションでシーダーの追加

```php
//down()を実行後にup()を実行する
php artisan migrate:refresh --seed

//全テーブルを削除してup()を実行
php artisan migrate:fresh --seed
```

シーダーのみの追加

```php
php artisan make:seeder YourSeeder
```

## リソースコントローラー CRUD(Store)

```php

//view側でformでmethod="post" action=storeを指定する
<form method="post" action="{{ route('admins.owners.store') }}">
// @csrfは必須
@csrf
    <div class="relative">
        <label for="name">Name</label>
        // inputタグのname="attribute"でparamの名前を設定する oldで画面更新後も値を保持できる
        <input type="text" id="name" name="name" value="{{ old('name') }}">
    </div>
</form>

//リソースコントローラー
//Request $requestインスタンスでformのparamを受け取る $request->nameの形でparamを取得する
class OwnersController extends Controller {
    public function __construct() {
        $this->middleware('auth:admins');
    }
    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admins.owners.index');
    }
}
//Modelで設定した$fillableまたは$guardedにcreateで値を渡す
class Owner extends Authenticatable {
    use HasFactory;

    //fillableは指定したカラムに対してのみcreate()やupdate(),fill()が可能となる定義
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    //guardedは指定したカラムに対してのみcreate()やupdate(),fill()が不可能となる定義
    protected $guarded = [
        'name',
    ];
}

<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnersController extends Controller
{
    public function update(Request $request, Owner $owner)
    {
        // $fillableに指定したもの以外は入らない
        $owner->update($request->all());

        //saveメソッドで更新することも可能だが$fillableを無視するので注意
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = $request->password;
        $owner->save();

        //fillableで設定した値をすべて更新する
        $image->fill($request->all())->save();

        return redirect()->route('owner.edit', $owner);
    }
}
```

## フラッシュメッセージ

英語だとtoaster

```php
// ResouceController
class OwnersController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admins.owners.index')
            //withメソッドで遷移先にメッセージを送付することができる。
            ->with('message', 'オーナー登録を実施しました');
            
            // 複数送付する場合はwithメソッドの引数を配列にして送る
            ->with([
                'message' => 'オーナー登録を実施しました',
                'session' => $request->session()->all()
            ]);
    }
}
// フラッシュメッセージ用のコンポーネントを用意する
// /resources/views/components/flash-message.blade.php
@props(['status' => 'info'])

@php
if ($status === 'info') {
    $bgColor = 'bg-blue-300';
}
if ($status === 'error') {
    $bgColor = 'bg-red-300';
}
@endphp

@if (session('message'))
    <div class="{{ $bgColor }} w-1/2 mx-auto p-2 text-white">
        {{ session('message') }}
f    </div>
@endif
```

```php
//routeのリストを表示する | grep admin でadminのrouteで絞り込む
php artisan route:list | grep admin

```

### 論理削除

論理削除(ソフトデリート)  
　削除フラグをTRUEに設定し、レコードの検索から除外する  
　削除フラグをFALSEに設定することで検索可能にする  
　データが肥大化するが、ストレージ上にレコードが存在するため復旧しやすい

物理削除(デリート)  
　ストレージ上からレコードを削除する
　データが肥大化しない分、ストレージ上にレコードが存在しないので復旧しにくい

```php
//migration
$table->softDeletes();

//model
use Illuminate\Database\Eloquent\SoftDeletes;

use SosftDeletes;

//controller
ControllerClass::findOrFail($id)->delete(); //ソフトデリート
ControllerClass::all(); //ソフトデリートしたものは表示されない
ControllerClass::onlyTranshed()->get(); //ゴミ箱のみ表示
ControllerClass::withTranshed()->get(); //ゴミ箱も含め表示

ControllerClass::onlyTranshed()->restore(); //復元
ControllerClass::onlyTranshed()->forceDelete(); //完全削除
ControllerClass::withTranshed()->get(); //ゴミ箱も含め表示

$recode->trashed() //ソフトデリートされているかの確認
```

### ページネーション

ページネーションについて

### Route::resourceのonlyとexcept

only:指定したメソッドのみリクエストする事が出来る。allowlist方式
except:指定したメソッド以外をリクエストする事が出来る。denylist方式

```php
//showメソッドのみをリクエスト出来る
Route::resouce('admin',AdminController::class)
    ->middleware(['auth:admin'])
    ->only(['show'])


//showメソッド以外をリクエスト出来る
Route::resouce('admin',AdminController::class)
    ->middleware(['auth:admin'])
    ->except(['show'])
```

### bladeファイルでjavascriptファイルを読み込む

JSファイルを作成して読み込むパターン

```javascript
function test() {
    'use strict';
    alert("test");
}

```

```php
//blade
<script src="{{ asset('/js/test.js') }}"></script>
```

モジュールを作成して読み込むパターンも追記する

### migration forginId

外部キー制約を付与する

```php
return new class extends Migration {

    public function up() {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // foreignIdで外部キー制約を付与する
            // Laravelのテーブル名の規則に従いownersテーブルのidカラムを参照するにはowner_idと定義する
            // _(アンダーバー)より前をテーブル名として判定される
            // テーブル名が規則と一致しない場合は、引数としてconstrainedメソッドに渡すことでテーブル名を指定出来る
            $table->foreignId('owner_id')
                ->constrained()
                //更新時と削除時にcascadeを有効にする
                ->cascadeOnUpdate('cascade')
                ->cascadeOnDelete('cascade');
            //etc...
    });
    }
    // etc...
}
```

### Eloquant relation

詳しくは[Laravel-9 eloquent-relationships](https://readouble.com/laravel/9.x/ja/eloquent-relationships.html#one-to-one)を参照する  
外部キーがfoo_idでない場合の定義の方法等が記載されている

```php
//1対1の定義
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Owner extends Authenticatable{
    use HasFactory;
    //Owner側からShopモデルへアクセスするメソッドを定義する
    public function shop() {
        //1対1のリレーションを定義する
        return $this->hasOne(Shop::class);
    }
}

//逆の関係の定義
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Shop extends Model {
    use HasFactory;

    //ShopモデルからOwnerモデルへ逆アクセスするメソッドを定義する
    public function Owner() {
        //Owner_idカラムと一致するidを持つShopモデルのレコードを返す
        return $this->belongsTo(Owner::class);
    }

    //Shopモデルの外部キーがshop_idでない場合、belongsToメソッドの第2引数にカスタムキーを指定する
    public function Owner() {
        //Owner_idカラムと一致するidを持つShopモデルのレコードを返す
        return $this->belongsTo(Owner::class, 'foreign_key');
    }

    //Ownerモデルが主キーとしてidを使用しない場合、または別のカラムを使用して関連モデルを取得する場合は、
    //belongsToメソッドの第3引数にOwnerテーブルのカスタムキーを指定する
    public function Owner() {
        //Owner_idカラムと一致するidを持つShopモデルのレコードを返す
        return $this->belongsTo(Owner::class, 'foreign_key', 'owner_key');
    }
}

```

### inline middleware

クロージャを使用したミドルウェアの登録。
単一のコントローラー用のinline middlewareを定義する

```php
class YouAreController extends Controller {
    public function __construct() {

        //urlのパラメータをチェックして不一致のものは404画面に遷移させる
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('foo');
            if (!is_null($id)) {
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId;
                $ownerId = Auth::id();
                if ($shopId !== $ownerId) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }
}
```

### custom error page

エラーページの編集を行う為に、エラーページのテンプレートを生成する

```php
php artisan vendor:publish --tag=laravel-errors
```

resources/views/errorsに生成されるので編集をする

### Storage::putFile()で画像が保存されなかった

原因はディレクトリ生成時の権限の問題だった。
config/filesystems.phpにディレクト生成時の権限設定を追加し解決した。

```php
    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            // permissionsの設定を追加
            'permissions' => [
                'dir' => [
                    'public'  => 0775,
                    'private' => 0775,
                ],
                'file' => [
                    'public' => 0664,
                    'private' => 0664,
                ],
            ]
        ],
    ]
```

### Intervention/Imageをwsl2のdocker上で使用する際の注意事項

PHP5.4以上では、画像処理ライブラリのGDまたはImageMagickをインストールする必要がある
今回はGDをインストールするようにDockerfileに追記した。  

参考資料

1. [php - Official Image | Docker Hub](https://hub.docker.com/_/php)
2. [LaravelでIntervention/Imageを使う際に、GD拡張機能を使うためのDockerfileの書き方](https://qiita.com/wbraver/items/c27ccd52fb4e2ae05611)

```Dockerfile
## png,jpg,jpegの最小構成
RUN apt-get update && \
    apt-get -y install libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd
```

aliasesを設定した際にvscode上でエラーとなる。
IDEがFacadeを補完できていないためである。

```php
//laravel-ide-helperのinstall
composer require --dev barryvdh/laravel-ide-helper
//ファサードの情報を自動生成する
php artisan ide-helper:generate
```

### フォームリクエストバリデーション

Illuminate\Http\Requestで提供されるバリデーション以上に複雑なものが必要な場合は、  
フォームリクエストを作成する必要がある。

```php
//フォームリクエストを生成する
php artisan make:request YourAreFormRequest
```

生成したフォームリクエストクラスはapp/Http/Requestsディレクトリに配置される。  
下記の内容で生成される。  
今回は画像データアップロード時の検証用バリデーションを例に設定する。

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YouAreFormRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        //trueにするとこのクラスを使用する
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        //リクエスト中のデータを検証するバリデーションルールを設定する
        return [
        ];
    }
}

//設定後
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YouAreFormRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            //画像の複数アップロード時に配列の各要素をバリデーションする
            'files.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            // 配列でも指定できるその際はパイプ文字をカンマに置き換える必要がある
            // 'image' => ['image','mimes:jpg,jpeg,png','max:2048'],
            // 'files.*.image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

        ];
    }
    //エラーメッセージのカスタマイズ
    public function messages() {
        return [
            'image' => '指定されたファイルが画像ではありません',
            'mimes' => '指定された拡張子(jpg/jpeg/png)ではありません',
            'max' => 'ファイルサイズは2MB以内にしてください',
        ];
    }
}

```

画像を配列でアップロードする際の設定

```html
<input type="file" id="imgae" name="files[][image]" multiple accept="image/png,image/jpeg,image/jpg">
```

バリデーションを利用する

```php
<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\YouAreFormRequest;
use InterventionImage;

class YouAreController extends Controller {
    //etc...

    //RequestClassからYouAreFormRequestに変更する
    public function update(YouAreFormRequest $request, $id) {
        $imageFile = $request->image;
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileName = uniqid(rand() . '');
            $extension = $imageFile->extension();
            $fileNameToStore = $fileName . '.' . $extension;
            $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
            Storage::put('public/shops/' . $fileNameToStore, $resizedImage);
        }
        return redirect()->route('owners.shops.index');
    }
}
```

### Eloquent RelationとEager Loading

N+1問題が発生するリレーションへのプロパティアクセス

```php

//lazy loading
use App\Models\Book;

$books = Book::all();

foreach ($books as $book) {
    echo $book->author->name;
}

```

N+1問題を解消するためにEager Loadingを使用する  

```php

//Eager loading
$books = Book::with('author')->get();

foreach ($books as $book) {
    echo $book->author->name;
}

//複数リレーションのEagerロード
$books = Book::with(['author', 'publisher'])->get();

//ネストされたリレーションを取得する場合
$books = Book::with('author.contacts')->get();

//ネストされたリレーションを取得する場合(配列で指定する)
$books = Book::with([
    'author' => [
        'contacts',
        'publisher',
    ],
])->get();
```

### Eloquentでテーブルを明示的に定義する

通常テーブルを指定しない場合はクラス名をスネークケースにしたものが、テーブル名として使用される。  
下記の場合はPrimaryCategoryがprimary_categoriesとなる

```php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryCategory extends Model {
    use HasFactory;

    public function secondary() {
        return $this->hasMany(SecondaryCategory::class);
    }
}

```

テーブル名を指定するには、モデルのtableプロパティを定義し、カスタムテーブル名を設定することもできる。  
tableプロパティが定義されているとその値をテーブル名として使用する

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model {
    use HasFactory;

    //tableプロパティを定義しカスタムテーブル名を設定
    protected $table = 't_stocks';
}
```

### tailwindでbuttonタグをtype="button"に設定すると背景色がtransparentになってしまう

実行環境
version:3.1.8
chrome:105.0.5195.102

micromodal.jsをインストールしcssを作成後、app.cssにインポートした際に発生。

``` css
/* micromodalを追加 */
@tailwind base;
@tailwind components;
@tailwind utilities;
@import "micromodal";
```

micromodalを追加後npm run buildでエラー
[vite:css] @import must precede all other statements (besides @charset or empty @layer)  
2  |  @tailwind components;  
3  |  @tailwind utilities;  
4  |  @import "micromodal";  
   |   ^  
5  |  
エラーに沿って@importを最初に読み込みbuildした際にボタンの背景色がtransparentに上書きされてしまった。  
githubに該当するIssue:[preflight button reset in v3 inconsistent with v2 #6602](https://github.com/tailwindlabs/tailwindcss/issues/6602)を見つけたので修正する  

```css
/* エラー解消 */
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
@import "micromodal";
```

### ORMで頻出する検索条件をローカルクエリスコープに設定する

頻出する条件に修正が必要になった際に出現箇所すべてに作業をすることは好ましくない。
対策としてローカルクエリスコープとしモデル側で条件を管理する。

```php
//Contoroller
Stock::where('product_id', $product->id)->sum('quantity');

//ローカルクエリスコープを使用する
Stock::productId($product->id)->sum('quantity');

//Model
class Stock extends Model {
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
    ];

    protected $table = 't_stocks';

    //ローカルスコープクエリを使用するにはscopeを先頭につけたメソッドを定義する
    public function scopeProductId($query, $id) {
        return $query->where('product_id', $id);
    }
}
```

### 定数クラスを扱う

Laravelで定数クラスを扱う  
app配下にConstantsフォルダと定数クラスを作成する
config/app.phpでaliases登録する

```php
<?php

namespace App\Constants;

class Product {
    const PRODUCT_ADD = '1';
    const PRODUCT_REDUCE = '2';

    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'reduce' => self::PRODUCT_REDUCE
    ];
}

return [
    //省略

    'aliases' => Facade::defaultAliases()->merge([
        //Constants
        'ProductConstant' => App\Constants\Product::class,
    ])->toArray(),
]

//Controller
if ($request->type === \ProductConstant::PRODUCT_LIST['add']) {
    //etc
}
if ($request->type === \ProductConstant::PRODUCT_LIST['reduce']) {
    //etc
}

//blade
<input type="radio" name="type" value="{{ \ProductConstant::PRODUCT_LIST['add'] }}" class="mr-2">増加
<input type="radio" name="type" value="{{ \ProductConstant::PRODUCT_LIST['reduce'] }}" class="mr-2">削減
```

### tailwindで横２分割して中央揃え

```html
<!-- 両方ともコンテンツが存在する場合 -->
<div class="flex justify-around">
    <div class="p-3 flex justify-center">
        <button onclick="location.href='{{ route('owners.images.index') }}'" type="button"
            class="mx-auto text-gray-50 bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">戻る</button>
    </div>
    <div class="p-3 flex justify-center">
        <button type="submit"
            class="mx-auto text-white bg-purple-500 border-0 py-2 px-8 focus:outline-none hover:bg-purple-600 rounded text-lg">更新</button>
    </div>
</div>

<!-- 片方のコンテンツが無い場合 -->
<div class="flex">
    <div class="p-3 w-1/2">
    </div>
    <div class="p-3 w-1/2 flex justify-center">
        <button type="button" id="delete" data-id="{{ $image->id }}"
            class="mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">削除</button>
    </div>
</div>
```

### Laravel bladeでのXSS対策

{{  }}で囲んだphpコードは自動的にhtmlentites関数をかけた値で出力される。

### viteでJavascriptファイルをバンドルする

bootstrap.jsで汎用的なscriptをimportしてapp.jsでimportする。
特定のページでしか使用しないものはvite.config.jsでパスを設定する。

```js
//foo.js
'use strict';
function foo() {
    alert('foo');
}
foo();

//bar.js
'use strict';
function bar() {
    alert('bar');
}
bar();

//vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                //追加したいjavascriptファイルのパス設定
                'resources/js/foo.js',
                'resources/js/bar.js',
            ],
            refresh: true,
        }),
    ],
});

```

npm run buildでpublic/assetsにコンパイルされたファイルが出力される。

```php
//bladeファイルから読み込む
@vite('resources/js/foo.js')
//複数読み込む場合は配列で設定する
@vite(['resources/js/foo.js', 'resources/js/bar.js'])
```

### Laravel Viteで静的アセットのバンドルを行う

```js
//resources/js/app/js
import './bootstrap';

import Alpine from 'alpinejs';

//resources/images以下の画像を全てコンパイルする
import.meta.glob([
    '../images/**',
  ]);
```

bladeファイルでコンパイルされた画像を使用する

```php
//logo.blade.php
<img src="{{ Vite::asset('resources/images/logo.png') }}">
```

Viteファサードがうまく動作しない場合はconfig/app.jsのaliasesにViteを追加しcomposer updateとnpm updateを試す

```php
<?php

use Illuminate\Support\Facades\Facade;

return [
    //etc

    'aliases' => Facade::defaultAliases()->merge([
        //Vite
        'Vite' => \Illuminate\Support\Facades\Vite::class,
    ])->toArray(),
```
