<?php

function myExtractKeywords(string $input, int $limit = -1): array {
    return array_values(array_unique(preg_split('/[\p{Z}\p{Cc}]++/u', $input, $limit, PREG_SPLIT_NO_EMPTY)));
}
