<?php

declare(strict_types=1);

function slugify(string $string, string $divider = '-'): string
{
    // replace non letter or digits by divider
    $string = preg_replace('~[^\pL\d]+~u', $divider, $string);

    // transliterate
    $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string ?? '');

    // remove unwanted characters
    $string = preg_replace('~[^-\w]+~', '', ($string === false) ? '' : $string);

    // trim
    $string = trim($string ?? '', $divider);

    // remove duplicate divider
    $string = preg_replace('~-+~', $divider, $string);

    // lowercase
    $string = strtolower($string ?? '');

    if (empty($string)) {
        return 'n-a';
    }

    return $string;
}
