<?php

$input = str_split($argv[1]);

$numberOfChars = count($input);

$sum = 0;

for ($index = 0; $index < $numberOfChars / 2; ++$index) {
    $current = $input[$index];

    $halfwayAroundIndex = ($index + ($numberOfChars / 2)) % $numberOfChars;
    $halfwayAround = $input[$halfwayAroundIndex];

    if ($halfwayAround === $current) {
        $sum += $current;
    }
}

echo $sum * 2;
