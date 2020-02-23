# laravel-postalcode-japan

[郵便番号データ](https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip)を取り込んで住所マスタを作成します。

## Install 

```
composer require shibuyakosuke/laravel-postalcode-japan
```

## Setup

マイグレーションを実行します。

```
php artisan migrate
```

postal_codes, cities, prefectures テーブルが作成されます。

```
php artisan postalcode:update
```

を実行すると、自動的に[郵便番号データ](https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip)をダウンロードし、
各テーブルにデータを投入します。同じコマンドを再度実行しても、cities, prefectures のデータは updateOrCreate() で更新されます。

## Usage

あらかじめルートが設定されており、`ajax/prefectures/` にアクセスすると都道府県の一覧、`ajax/cities/{prefecture}`
にアクセスすると市区町村の一覧をJSONデータで取得することができます。