<?php

namespace App\Constants;

class Product {
    const PRODUCT_ADD = '1';
    const PRODUCT_REDUCE = '2';
    const PRODUCT_CANCEL = '3';

    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'reduce' => self::PRODUCT_REDUCE,
        'cancel' => self::PRODUCT_CANCEL
    ];
}
