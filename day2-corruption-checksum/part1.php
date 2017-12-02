<?php

const ROW_SEPARATOR = "\n";
const COLUMN_SEPARATOR = "\t";


$input = file_get_contents(__DIR__ . '/input');

$rows = explode(ROW_SEPARATOR, $input);

$inputMatrix = array_map(function ($row) {
    return explode(COLUMN_SEPARATOR, $row);
}, $rows);

$result = 0;

foreach ($inputMatrix as $rowCells) {
    $min = PHP_INT_MAX;
    $max = PHP_INT_MIN;

    /** @var array $rowCells */

    foreach ($rowCells as $cell) {
        if ((int)$cell > $max) {
            $max = (int)$cell;
        }

        if ((int)$cell < $min) {
            $min = (int)$cell;
        }
    }

    $result += ($max - $min);
}

echo $result;
