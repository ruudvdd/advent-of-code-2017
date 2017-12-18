<?php

$steps = (int)file_get_contents(__DIR__ . '/input');

$buffer = [0];

$currentPosition = 0;

for ($i = 1; $i <= 50000000; ++$i) {
    $insertHere = ($currentPosition + $steps) % $i;
    $currentPosition = $insertHere + 1;

    if ($currentPosition === 1) {
        $value = $i;
    }
}

echo $value;


