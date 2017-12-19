<?php

$rows = explode("\n", file_get_contents(__DIR__ . '/input'));
$tubes = array_map(function (string $row) {
    return str_split($row);
}, $rows);

const DOWN = 0;
const UP = 1;
const RIGHT = 2;
const LEFT = 3;

// find start

$row = 0;
$column = 0;

foreach ($tubes[0] as $index => $tube) {
    if ($tube !== ' ') {
        $column = $index;
        break;
    }
}


$wrong = false;
$outOfBounds = false;
$end = false;

$currentDirection = DOWN;

$word = '';

function outOfBounds(int $row, int $column, array $tubes) : bool
{
    if (count($tubes) <= $row) {
        return true;
    }

    if (count($tubes[$row]) <= $column) {
        return true;
    }

    return false;
}

$steps = 1;

while (!$outOfBounds && !$end) {
    switch ($currentDirection) {
        case UP:
            $row--;
            break;
        case DOWN:
            $row++;
            break;
        case RIGHT:
            $column++;
            break;
        case LEFT:
            $column--;
            break;
    }

    $outOfBounds = outOfBounds($row, $column, $tubes);
    if ($outOfBounds) {
        throw new \RuntimeException('OUT OF BOUNDS!');
        break;
    }

    $tube = $tubes[$row][$column];

    if ($tube === ' ') {
        // reached end
        break;
    }

    if ($tube === '+') {
        $steps++;
        // find new direction
        if ($currentDirection !== DOWN && !outOfBounds($row - 1, $column, $tubes) && $tubes[$row - 1][$column] === '|') {
            $currentDirection = UP;
            continue;
        }

        if ($currentDirection !== UP && !outOfBounds($row + 1, $column, $tubes) && $tubes[$row + 1][$column] === '|') {
            $currentDirection = DOWN;
            continue;
        }

        if ($currentDirection !== LEFT && !outOfBounds($row, $column + 1, $tubes) && $tubes[$row][$column + 1] === '-') {
            $currentDirection = RIGHT;
            continue;
        }

        if ($currentDirection !== RIGHT && !outOfBounds($row, $column - 1, $tubes) && $tubes[$row][$column - 1] === '-') {
            $currentDirection = LEFT;
            continue;
        }
        throw new \RuntimeException('WRONG!');
    }

    if ($tube >= 'A' && $tube <= 'Z') {
        $word .= $tube;
    }

    $steps++;
}

echo $steps;
