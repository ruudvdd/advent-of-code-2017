<?php

$input = file_get_contents(__DIR__ . '/input');

$sequences = explode(',', $input);

$programs = range('a', 'p');

/**
 * @param array $programs
 * @param int   $a
 * @param int   $b
 *
 * @return array
 */
function swap($programs, int $a, int $b) : array
{
    $temp = $programs[$a];
    $programs[$a] = $programs[$b];
    $programs[$b] = $temp;

    return $programs;
}

/**
 * @param int   $times
 * @param array $programs
 *
 * @return array
 */
function rotate(int $times, array $programs) : array
{
    for ($i = 0; $i < $times; ++$i) {
        $programs = array_values(array_merge([$programs[count($programs) - 1]],
            array_slice($programs, 0, count($programs) - 1)));
    }

    return $programs;
}

$cycleCounter = 0;

$encounteredPrograms = [];

for ($i = 0; $i < 1000000000; ++$i) {
    foreach ($sequences as $sequence) {
        $type = $sequence[0];

        switch ($type) {
            case 's':
                $times = (int)substr($sequence, 1);
                $programs = rotate($times, $programs);
                break;
            case 'x':
                $who = substr($sequence, 1);
                [$a, $b] = explode('/', $who);

                $programs = swap($programs, (int)$a, (int)$b);
                break;
            case 'p':
                $who = substr($sequence, 1);
                [$pa, $pb] = explode('/', $who);

                $a = array_search($pa, $programs, true);
                $b = array_search($pb, $programs, true);

                $programs = swap($programs, (int)$a, (int)$b);
                break;
        }
    }

    $foundIndex = array_search(implode($programs), $encounteredPrograms, true);

    if (false === $foundIndex) {
        $encounteredPrograms[$i] = implode($programs);
        continue;
    }

    break;
}

$index = 1000000000 % count($encounteredPrograms);

echo $encounteredPrograms[$index - 1];