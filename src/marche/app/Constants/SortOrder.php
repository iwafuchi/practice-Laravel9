<?php

namespace App\Constants;

class SortOrder {
    const ORDER_RECOMMEND = '0';
    const ORDER_HIGHER = '1';
    const ORDER_LOWER = '2';
    const ORDER_NEWST = '3';
    const ORDER_OLDEST = '4';

    const SORT_ORDER = [
        'recommend' => self::ORDER_RECOMMEND,
        'higherPrice' => self::ORDER_HIGHER,
        'lowerPrice' => self::ORDER_LOWER,
        'newst' => self::ORDER_NEWST,
        'oldest' => self::ORDER_OLDEST
    ];
}
