<?php

namespace App\Constants;

class SortOrder {
    const ORDER_RECOMMEND = '0';
    const ORDER_HIGHER = '1';
    const ORDER_LOWER = '2';
    const ORDER_NEWST = '3';
    const ORDER_OLDEST = '4';

    const SORT_ORDER = [
        'recommend' => ['value' => self::ORDER_RECOMMEND, 'description' => 'おすすめ順'],
        'higherPrice' => ['value' => self::ORDER_HIGHER, 'description' => '価格の高い順'],
        'lowerPrice' => ['value' => self::ORDER_LOWER, 'description' => '価格の低い順'],
        'newst' => ['value' => self::ORDER_NEWST, 'description' => '新しい順'],
        'oldest' => ['value' => self::ORDER_OLDEST, 'description' => '古い順']
    ];
}
