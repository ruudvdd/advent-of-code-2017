<?php

$input = file_get_contents(__DIR__ . '/input');

[$a, $b] = explode("\n", $input);

$factorA = 16807;
$factorB = 48271;

$modulo = 2147483647;

$total = 0;

for ($i = 0; $i < 5000000; ++$i) {
    do {
        $a = ($a * $factorA) % $modulo;
    } while ($a % 4 !== 0);

    do {
        $b = ($b * $factorB) % $modulo;
    } while ($b % 8 !== 0);

    $binA = base_convert($a, 10, 2);
    $binB = base_convert($b, 10, 2);

    if (substr($binA, -16) === substr($binB, -16)) {
        $total++;
    }
}

echo $total;
