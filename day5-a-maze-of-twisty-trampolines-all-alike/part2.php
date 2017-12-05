<?php

$input = file_get_contents(__DIR__ . '/input');

$program = array_map(function ($step) {
    return (int)$step;
}, explode("\n", $input));

$position = 0;
$steps = 0;

while ($position < count($program)) {
    $nextPosition = $position + $program[$position];

    if ($program[$position] >= 3) {
        $program[$position]--;
    } else {
        $program[$position]++;
    }


    $position = $nextPosition;
    $steps++;
}

echo $steps;