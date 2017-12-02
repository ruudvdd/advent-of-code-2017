<?php

$input = file_get_contents(__DIR__ . '/input');

$numberOfChars = strlen($input);
$previous = $input[$numberOfChars - 1];

$sum = 0;

for ($index = 0; $index < $numberOfChars; ++$index) {
    $current = $input[$index];

    if ($current === $previous) {
        $sum += (int)$previous;
    }

    $previous = $current;
}

echo $sum;
