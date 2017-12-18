<?php

$instructions = explode("\n", file_get_contents(__DIR__ . '/input'));

$sound = 0;

$registers = [];

$currentPosition = 0;

while (1) {
    $instruction = $instructions[$currentPosition];

    $jump = false;
    $arguments = explode(' ', $instruction);

    switch ($arguments[0]) {
        case 'snd':
            $register = $arguments[1];
            if (isset($registers[$register])) {
                $sound = $registers[$register];
            }
            break;
        case 'set':
            $register = $arguments[1];
            $value = $arguments[2];
            if ('a' <= $value && 'z' >= $value) {
                $value = $registers[$value] ?? 0;
            }
            $value = (int)$value;
            $registers[$register] = $value;
            break;
        case 'add':
            $register = $arguments[1];
            $value = $arguments[2];
            if ('a' <= $value && 'z' >= $value) {
                $value = $registers[$value] ?? 0;
            }
            $value = (int)$value;
            $registers[$register] += $value;
            break;
        case 'mul':
            $register = $arguments[1];
            $value = $arguments[2];
            if ('a' <= $value && 'z' >= $value) {
                $value = $registers[$value] ?? 0;
            }
            $value = (int)$value;
            $registers[$register] *= $value;
            break;
        case 'mod':
            $register = $arguments[1];
            $value = $arguments[2];
            if ('a' <= $value && 'z' >= $value) {
                $value = $registers[$value] ?? 0;
            }
            $value = (int)$value;
            $registers[$register] %= $value;
            break;
        case 'rcv':
            if ($sound !== 0) {
                echo $sound;
                exit();
            }
            break;
        case 'jgz':
            $register = $arguments[1];
            $value = $arguments[2];
            if ('a' <= $value && 'z' >= $value) {
                $value = $registers[$value] ?? 0;
            }
            $value = (int)$value;
            if ($registers[$register] > 0) {
                $currentPosition += $value;
                $jump = true;
            }

            break;
    }

    if (!$jump) {
        $currentPosition++;
    }
}

echo 'ended';
