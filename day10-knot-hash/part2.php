<?php

$lengths = file_get_contents(__DIR__ . '/input');
$characters = [];
if (strlen($lengths) > 0) {
    $characters = str_split($lengths);
}

$bytes = array_map(function ($character) { return ord($character); }, $characters);
$bytes = array_merge($bytes, [
    17,
    31,
    73,
    47,
    23,
]);

$position = 0;

$list = range(0, 255);
$listLength = count($list);

$skipSize = 0;

for ($round = 0; $round < 64; ++$round) {
    foreach ($bytes as $length) {
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
}

$xorList = [];
$segmentCount = $listLength / 16;
for ($i = 0; $i < $segmentCount; $i++) {
    $segment = array_splice($list, 0, 16);

    $segmentXorValue = 0;
    foreach ($segment as $value) {
        $segmentXorValue ^= $value;
    }

    $xorList[] = sprintf('%02s', dechex($segmentXorValue));
}

echo implode('', $xorList);