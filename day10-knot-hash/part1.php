<?php

$lengths = explode(',', file_get_contents(__DIR__ . '/input'));

$position = 0;

$list = range(0, 255);
$listLength = count($list);

$skipSize = 0;

foreach ($lengths as $length) {
    $startIndex = $position;
    $endIndex = ($position + $length - 1) % $listLength;

    $tempSlice = [];
    for ($i = 0; $i < $length; ++$i) {
        $index = ($endIndex - $i);
        if ($index < 0) {
            $index = $listLength + $index;
        }

        $tempSlice[] = $list[$index];
    }

    $index = $startIndex;
    foreach ($tempSlice as $value) {
        $list[$index] = $value;
        $index = ($index + 1) % $listLength;
    }


    $position = ($position + $length + $skipSize) % $listLength;
    $skipSize++;
}

echo $list[0] * $list[1];