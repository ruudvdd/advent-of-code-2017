<?php

$instructions = explode("\n", file_get_contents(__DIR__ . '/input'));

$sound = 0;

$registers = [];

$currentPosition = 0;

function getValue(array $registers, $value)
{
    if ('a' <= $value && 'z' >= $value) {
        $value = $registers[$value] ?? 0;
    }

    return (int)$value;
}

$numbMul = 0;

while ($currentPosition < count($instructions)) {
    $instruction = $instructions[$currentPosition];

    $jump = false;
    $arguments = explode(' ', $instruction);

    switch ($arguments[0]) {
        case 'set':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] = $value;
            break;
        case 'sub':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] -= $value;
            break;
        case 'mul':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] *= $value;
            $numbMul++;
            break;
        case 'jnz':
            $compareValue = getValue($registers, $arguments[1]);
            $value = getValue($registers, $arguments[2]);
            if ($compareValue !== 0) {
                $jump = true;
                $currentPosition += $value;
            }

            break;
    }

    if (!$jump) {
        $currentPosition++;
    }
}

echo $numbMul;
