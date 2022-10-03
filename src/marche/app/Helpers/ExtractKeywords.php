<?php

/**
 * myExtractKeywords function
 * あらゆる空白文字で分割し，重複を除外する
 * @param string $input
 * @param integer $limit
 * @return array
 */
function myExtractKeywords(string $input, int $limit = -1): array {
    return array_values(array_unique(preg_split('/[\p{Z}\p{Cc}]++/u', $input, $limit, PREG_SPLIT_NO_EMPTY)));
}
