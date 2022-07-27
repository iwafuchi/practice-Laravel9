# 作業メモ

## Laravel install
composer create-project laravel/laravel task_test --prefer-dist "9.*"

## 所有者:グループの変更
chown -R myusername:mygroupname task_test

## 権限変更
chmod -R 777 storage

## Breeze install
composer require laravel/breeze --dev<br>
php artisan breeze:install<br>
npm install<br>
npm run dev<br>
npm run build<br>

## migrate command
php artisan migrate

## Add locales
composer require laravel-lang/publisher laravel-lang/lang --dev
php artisan lang:add ja

## Update locales
php artisan lang:update

## 参考資料
https://readouble.com/laravel/9.x/ja/releases.html<br>
https://publisher.laravel-lang.com/