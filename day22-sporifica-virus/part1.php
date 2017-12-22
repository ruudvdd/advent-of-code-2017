<?php

$inputRows = explode("\n", file_get_contents(__DIR__ . '/input'));

$grid = [];

/** @var string $row */
foreach ($inputRows as $rowIndex => $row) {
    $cells = str_split($row);

    foreach ($cells as $colIndex => $cell) {
        $grid[$colIndex . ',' . $rowIndex] = $cell === '#';
    }
}

// find center
$y = (count($inputRows) - 1) / 2;
$x = (strlen($inputRows[$y]) - 1) / 2;

const UP = 0;
const RIGHT = 1;
const DOWN = 2;
const LEFT = 3;

$direction = UP;

$infectionBursts = 0;

foreach (range(1, 10000) as $burst) {
    $coordinates = $x . ',' . $y;

    $infected = isset($grid[$coordinates]) && $grid[$coordinates];

    if ($infected) {
        $direction++;
        $direction %= 4;
    } else {
        $direction--;
        $direction = $direction >= 0 ? $direction : 4 + $direction;
    }


    if (!$infected) {
        $infectionBursts++;
    }

    $grid[$coordinates] = !$infected;

    switch ($direction) {
        case UP:
            $y--;
            break;
        case RIGHT:
            $x++;
            break;
        case DOWN:
            $y++;
            break;
        case LEFT:
            $x--;
            break;
        default:
            throw new \LogicException(sprintf('Unknown direction %s', $direction));
    }
}

echo $infectionBursts;
