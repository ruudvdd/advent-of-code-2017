<?php

$instructions = explode("\n", file_get_contents(__DIR__ . '/input'));

$registers = [];

$currentPosition0 = 0;
$currentPosition1 = 0;

$registers0 = ['p' => 0];
$registers1 = ['p' => 1];

$messageQueue0to1 = [];
$messageQueue1to0 = [];

/**
 * @param array $instructions
 * @param int   $currentPosition
 * @param array $registers
 * @param array $receiving
 * @param array $sending
 * @param int   $countSends
 *
 * @return int
 */
function doNextInstruction(
    int $id,
    array $instructions,
    $currentPosition,
    array &$registers,
    array &$receiving,
    array &$sending,
    int &$countSends = 0
) : int {
    $instruction = $instructions[$currentPosition];

    $arguments = explode(' ', $instruction);

    switch ($arguments[0]) {
        case 'snd':
            $value = getValue($registers, $arguments[1]);

            $sending[] = $value;
            $countSends++;
            break;
        case 'set':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] = $value;
            break;
        case 'add':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] += $value;
            break;
        case 'mul':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] *= $value;
            break;
        case 'mod':
            $register = $arguments[1];
            $value = getValue($registers, $arguments[2]);
            $registers[$register] %= $value;
            break;
        case 'rcv':
            if (empty($receiving)) {
                // pause program
                return $currentPosition;
            }

            $register = $arguments[1];
            $registers[$register] = array_shift($receiving);
            break;
        case 'jgz':
            $compareValue = getValue($registers, $arguments[1]);
            $value = getValue($registers, $arguments[2]);
            if ($compareValue > 0) {
                return $currentPosition + $value;
            }

            break;
        default:
            die('unkown instruction');
    }

    return $currentPosition + 1;
}

/**
 * @param array $registers
 * @param       $value
 *
 * @return int|mixed
 */
function getValue(array $registers, $value)
{
    if ('a' <= $value && 'z' >= $value) {
        $value = $registers[$value] ?? 0;
    }
    $value = (int)$value;

    return $value;
}

$paused0 = false;
$paused1 = false;

$countSends = 0;

while ($paused0 === false || $paused1 === false) {
    $previousCurrentPosition = $currentPosition0;
    $currentPosition0 = doNextInstruction(
        0,
        $instructions,
        $currentPosition0,
        $registers0,
        $messageQueue1to0,
        $messageQueue0to1
    );
    $paused0 = $previousCurrentPosition === $currentPosition0;

    $previousCurrentPosition = $currentPosition1;
    $currentPosition1 = doNextInstruction(
        1,
        $instructions,
        $currentPosition1,
        $registers1,
        $messageQueue0to1,
        $messageQueue1to0,
        $countSends
    );
    $paused1 = $previousCurrentPosition === $currentPosition1;
}


echo $countSends;
