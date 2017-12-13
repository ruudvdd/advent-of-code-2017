<?php

$layers = explode("\n", file_get_contents(__DIR__ . '/input'));

const UP = 1;
const DOWN = -1;

$ranges = [];
$scanners = [];

$currentPosition = 0;

foreach ($layers as $layer) {
    list($depth, $range) = explode(': ', $layer);

    $ranges[$depth] = $range;
    $scanners[$depth] = [
        'position' => 0,
        'direction' => UP,
    ];
}

function moveScanners(array &$scanners, array $ranges)
{
    foreach ($scanners as $depth => &$scanner) {
        if ($scanner['direction'] === UP) {
            if ($scanner['position'] !== $ranges[$depth] - 1) {
                $scanner['position']++;
            } else {
                $scanner['direction'] = DOWN;
                $scanner['position']--;
            }
        } elseif ($scanner['direction'] === DOWN) {
            if ($scanner['position'] !== 0) {
                $scanner['position']--;
            } else {
                $scanner['direction'] = UP;
                $scanner['position']++;
            }
        }
    }

    unset($scanner);
}

$lastLayer = max(...array_keys($ranges));

$severity = 0;

while ($currentPosition <= $lastLayer) {
    if (isset($scanners[$currentPosition]) && $scanners[$currentPosition]['position'] === 0) {
        $severity += ($currentPosition * $ranges[$currentPosition]);
    }

    // move package
    $currentPosition++;
    moveScanners($scanners, $ranges);
}

echo $severity;

