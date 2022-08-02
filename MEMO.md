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
    protected $userRoot = 'user.login';
    protected $ownerRoot = 'owner.login';
    protected $adminRoot = 'admin.login';
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
                return route($this->ownerRoot);
            }
            if (Route::is('admin.*')) {
                return route($this->adminRoot);
            }
            return route($this->userRoot);
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
