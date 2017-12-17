<?php

$input = file_get_contents(__DIR__ . '/input');

[$a, $b] = explode("\n", $input);

$factorA = 16807;
$factorB = 48271;

$modulo = 2147483647;

$total = 0;

for ($i = 0; $i < 40000000; ++$i) {
    $a = ($a * $factorA) % $modulo;
    $b = ($b * $factorB) % $modulo;

    $binA = decbin($a);
    $binB = decbin($b);

    if (substr($binA, -16) === substr($binB, -16)) {
        $total++;
    }
}

echo $total;
