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
//生成コマンド
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
            // 複数送付する場合は、配列にして送る
            // ->with([
            //     'message' => 'オーナー登録を実施しました',
            //     'session' => $request->session()->all()
            // ]);
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

### migration 外部キー制約の付与

```php
return new class extends Migration {

    public function up() {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // foreignIdで外部キー制約を付与する
            // Laravelのテーブル名の規則に従いownersテーブルのidカラムを参照するにはowner_idと定義する
            // テーブル名が規則と一致しない場合は、引数としてconstrainedメソッドに渡すことでテーブル名を指定出来る
            $table->foreignId('owner_id')->constrained();
            //etc...
    });
    }
    // etc...
}
```

### Eloquant relation

```php
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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;
class Shop extends Model {
    use HasFactory;

    //Shop側からOwnerモデルへ逆アクセスするメソッドを定義する
    public function Owner() {
        //Owner_idカラムと一致するidを持つShopモデルのレコードを返す
        return $this->belongsTo(Owner::class);
    }
}

```
