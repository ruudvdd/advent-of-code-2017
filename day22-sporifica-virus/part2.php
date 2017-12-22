<?php

$inputRows = explode("\n", file_get_contents(__DIR__ . '/input'));

const CLEAN = 0;
const WEAKENED = 1;
const INFECTED = 2;
const FLAGGED = 3;

$grid = [];

/** @var string $row */
foreach ($inputRows as $rowIndex => $row) {
    $cells = str_split($row);

    foreach ($cells as $colIndex => $cell) {
        $grid[$colIndex . ',' . $rowIndex] = $cell === '#' ? INFECTED : CLEAN;
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

foreach (range(1, 10000000) as $burst) {
    $coordinates = $x . ',' . $y;

    $infected = CLEAN;
    if (isset($grid[$coordinates])) {
        $infected = $grid[$coordinates];
    }

    switch ($infected) {
        case INFECTED:
            $direction++;
            $direction %= 4;
            break;
        case CLEAN:
            $direction--;
            $direction = $direction >= 0 ? $direction : 4 + $direction;
            break;
        case FLAGGED:
            $direction = ($direction + 2) % 4;
            break;
    }


    if ($infected === WEAKENED) {
        $infectionBursts++;
    }

    $grid[$coordinates] = ($infected + 1) % 4;

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

$minX = 0;
$minY = 0;

$maxX = 0;
$maxY = 0;

foreach ($grid as $coordinates => $infected) {
    [$i, $j] = explode(',', $coordinates);

    if ($i < $minX) {
        $minX = $i;
    }

    if ($j < $minY) {
        $minY = $j;
    }

    if ($i > $maxX) {
        $maxX = $i;
    }

    if ($j > $minY) {
        $maxY = $j;
    }
}

foreach (range($minY, $maxY) as $j) {
    foreach (range($minX, $maxX) as $i) {
        $cell = ' %s ';

        if ((int)$i === (int)$x && (int)$j === (int)$y) {
            $cell = '[%s]';
        }

        $coordinates = $i . ',' . $j;

        $infected = isset($grid[$coordinates]) && $grid[$coordinates];

    }
}

echo $infectionBursts;
