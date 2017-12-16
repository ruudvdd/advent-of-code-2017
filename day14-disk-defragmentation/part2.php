<?php

$string = file_get_contents(__DIR__ . '/input');

/**
 * @param string $string
 *
 * @return string
 */
function knotHash(string $string) : string
{
    $characters = [];
    if (strlen($string) > 0) {
        $characters = str_split($string);
    }

    $bytes = array_map(function ($character) {
        return ord($character);
    }, $characters);
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

    return implode('', $xorList);
}

$usedSquares = 0;

$rows = [];

for ($row = 0; $row < 128; ++$row) {
    $hash = knotHash($string . '-' . $row);

    $currentRow = '';

    $hexadecimals = str_split($hash);
    foreach ($hexadecimals as $hexadecimal) {
        $currentRow .= sprintf('%04d', base_convert($hexadecimal, 16, 2));
    }

    $rows[] = str_split($currentRow);
}

function inExistingRegion(array $regions, $row, $column)
{
    foreach ($regions as $region) {
        if (in_array($row . '-' . $column, $region, true)) {
            return true;
        }
    }

    return false;
}

function findRegion($rows, $row, $column, $region = [])
{
    $neighbours = [
        [$row, $column - 1],
        [$row + 1, $column],
        [$row - 1, $column],
        [$row, $column + 1],
    ];
    foreach ($neighbours as $neighbour) {
        [$x, $y] = $neighbour;

        if ($x < 0 && $x > 127) {
            continue;
        }

        if ($y < 0 && $y > 127) {
            continue;
        }

        if (in_array($x . '-' . $y, $region, true)) {
            continue;
        }

        if ($rows[$x][$y] === '1') {
            $region[] = $x . '-' . $y;
            $region = findRegion($rows, $x, $y, $region);
        }
    }
    return $region;
}

$regions = [];

foreach ($rows as $x => $row) {
    foreach ($row as $y => $column) {
        if ($column === '0') {
            continue;
        }

        if (inExistingRegion($regions, $x, $y)) {
            continue;
        }

        $region = [$x . '-' . $y];
        $regions[] = findRegion($rows, $x, $y, $region);
    }
}


echo count($regions);
