<?php

$steps = (int)file_get_contents(__DIR__ . '/input');

$buffer = [0];

$currentPosition = 0;

for ($i = 1; $i <= 2017; ++$i) {
    $insertHere = ($currentPosition + $steps) % count($buffer);
    array_splice($buffer, $insertHere + 1, 0, $i);
    $currentPosition = $insertHere + 1;
}

echo $buffer[($currentPosition + 1) % count($buffer)];


