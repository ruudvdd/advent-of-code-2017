<?php

$steps = explode(',', file_get_contents(__DIR__ . '/input'));

$x = 0;
$y = 0;

$furthest = 0;

foreach ($steps as $step) {
    switch ($step) {
        case 'ne':
            $x += 1;
            break;
        case 'se':
            $x += 1;
            $y -= 1;
            break;
        case 's':
            $y -= 1;
            break;
        case 'sw':
            $x -= 1;
            break;
        case 'nw':
            $x -= 1;
            $y += 1;
            break;
        case 'n':
            $y += 1;
    }
    $furthest = max($furthest, $x + $y);
}

echo $furthest;
