<?php

$input = file_get_contents(__DIR__ . '/input');

$inputArray = str_split($input);

$numberOfChars = count($inputArray);

$sum = 0;

for ($index = 0; $index < $numberOfChars / 2; ++$index) {
    $current = $inputArray[$index];

    $halfwayAroundIndex = ($index + ($numberOfChars / 2)) % $numberOfChars;
    $halfwayAround = $inputArray[$halfwayAroundIndex];

    if ($halfwayAround === $current) {
        $sum += $current;
    }
}

echo $sum * 2;
