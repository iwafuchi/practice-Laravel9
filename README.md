# 作業メモ

## Laravel install

composer create-project laravel/laravel marche "9.*" --prefer-dist

## 所有者:グループの変更

chown -R myusername:mygroupname marche

## 権限変更

chmod -R 777 storage

## 所有者変更

chown -R www-data:www-data storage

## debuger install

composer require barryvdh/laravel-debugbar
Switch modes with APP_DEBUG in .env

## Breeze install

composer require laravel/breeze --dev  
php artisan breeze:install  
npm install  
npm run dev  
npm run build  

## migrate command

php artisan migrate

## Add locales

composer require laravel-lang/publisher laravel-lang/lang --dev  
php artisan lang:add ja

## Update locales

php artisan lang:update

## config and cache clear command

php artisan config:clear  
php artisan cache:clear

## インストール後の実施事項

画像のダミーデータはpublic/imagesフォルダ内にsample1.jsg~sample6.jpgとして保存しています。  
php artisan storage:linkでstorageフォルダにリンク後、storage/app/public/productsフォルダ内に保存すると表示されます。

## 参考資料

<https://readouble.com/laravel/9.x/ja/releases.html>  
<https://publisher.laravel-lang.com>
