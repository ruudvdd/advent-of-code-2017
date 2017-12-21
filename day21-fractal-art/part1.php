<?php

/**
 * @param $grid
 *
 * @return array
 * @throws Exception
 */
function divideGridIntoParts($grid) : array
{
    $rows = explode('/', $grid);

    if (count($rows) % 2 === 0) {
        $size = 2;
    } elseif (count($rows) % 3 === 0) {
        $size = 3;
    } else {
        throw new \Exception(sprintf('Grid of size %s not divisible by 2 nor 3', count($rows)));
    }

    $parts = [];
    $sizePartsSide = count($rows) / $size;

    for ($partsRowIndex = 0; $partsRowIndex < $sizePartsSide; ++$partsRowIndex) {

        $partsRow = [];
        $gridRowIndex = $partsRowIndex * $size;

        for ($partsColIndex = 0; $partsColIndex < $sizePartsSide; ++$partsColIndex) {

            $gridColIndex = $partsColIndex * $size;

            // create part
            $part = [];
            for ($rowOffset = 0; $rowOffset < $size; ++$rowOffset) {
                $part[$rowOffset] = [];

                for ($colOffset = 0; $colOffset < $size; ++$colOffset) {
                    $part[$rowOffset][$colOffset] = $rows[$gridRowIndex + $rowOffset][$gridColIndex + $colOffset];
                }
            }

            $partsRow[] = $part;
        }
        $parts[] = $partsRow;
    }

    return $parts;
}

function checkSwaps(array $part, array $rules)
{
    $partSize = count($part);
    $combinations = $partSize === 2 ? [
        // [0, 1],
        [1, 0],
    ] : [
        // [0, 1, 2],
        [2, 1, 0],
    ];

    // swap rows first
    foreach ($combinations as $combination) {
        $iteration = [];
        foreach ($combination as $rowIndex) {
            $iteration[] = $part[$rowIndex];
        }

        $stringPart = partToString($iteration);
        if (isset($rules[$stringPart])) {
            echo sprintf('Found it! %s', $stringPart), "\n";

            return explode('/', $rules[$stringPart]);
        }

        $iterationRotation = checkRotations($iteration, $rules);
        if ($iterationRotation !== false) {
            return $iterationRotation;
        }

        // swap columns of iteration
        foreach ($combinations as $columnCombination) {
            $colIteration = [];
            foreach ($columnCombination as $colIndex) {
                for ($i = 0; $i < $partSize; ++$i) {
                    $colIteration[$i][] = $iteration[$i][$colIndex];
                }
            }

            $stringPart = partToString($colIteration);
            if (isset($rules[$stringPart])) {
                echo sprintf('Found it! %s', $stringPart), "\n";

                return explode('/', $rules[$stringPart]);
            }

            $iterationRotation = checkRotations($colIteration, $rules);
            if ($iterationRotation !== false) {
                return $iterationRotation;
            }
        }
    }

    // swap columns first
    foreach ($combinations as $combination) {
        $iteration = [];
        foreach ($combination as $colIndex) {
            for ($i = 0; $i < $partSize; ++$i) {
                $iteration[$i][] = $part[$i][$colIndex];
            }
        }

        $stringPart = partToString($iteration);
        if (isset($rules[$stringPart])) {
            echo sprintf('Found it! %s', $stringPart), "\n";

            return explode('/', $rules[$stringPart]);
        }

        $iterationRotation = checkRotations($iteration, $rules);
        if ($iterationRotation !== false) {
            return $iterationRotation;
        }

        // swap rows of the iteration
        foreach ($combinations as $rowCombination) {
            $rowIteration = [];
            foreach ($rowCombination as $rowIndex) {
                $rowIteration[] = $iteration[$rowIndex];
            }

            $stringPart = partToString($rowIteration);
            if (isset($rules[$stringPart])) {
                echo sprintf('Found it! %s', $stringPart), "\n";

                return explode('/', $rules[$stringPart]);
            }

            $iterationRotation = checkRotations($rowIteration, $rules);
            if ($iterationRotation !== false) {
                return $iterationRotation;
            }
        }
    }

    return false;
}

/**
 * @param array $part
 * @param array $rules
 *
 * @return array
 * @throws Exception
 */
function findReplacementPart(array $part, array $rules)
{
    $stringPart = partToString($part);
    if (isset($rules[$stringPart])) {
        echo sprintf('Found it! %s', $stringPart), "\n";

        return explode('/', $rules[$stringPart]);
    }

    $replacement = checkSwaps($part, $rules);
    if ($replacement !== false) {
        return $replacement;
    }

    // rotate!
    return checkRotations($part, $rules);

    throw new \Exception(sprintf('Could not find replacement for %s', partToString($part)));
}

/**
 * @param array $part
 * @param array $rules
 *
 * @return array|bool
 */
function checkRotations(array $part, array $rules)
{
    $partSize = count($part);

    $rotations = $partSize === 2 ? [
        '1,0' => '0,0',
        '0,0' => '0,1',
        '1,1' => '1,0',
        '0,1' => '1,1',
    ] : [
        '2,0' => '0,0',
        '1,0' => '0,1',
        '0,0' => '0,2',
        '2,1' => '1,0',
        '1,1' => '1,1',
        '0,1' => '1,2',
        '2,2' => '2,0',
        '1,2' => '2,1',
        '0,2' => '2,2',
    ];

    $rotation = $part;

    $numberOfRotations = $partSize === 2 ? 3 : 7;

    foreach (range(0, $numberOfRotations - 1) as $numberOfRotation) {

        $newRotation = $partSize === 2 ? [[], []] : [[], [], []];

        foreach ($rotations as $from => $to) {
            [$fromRow, $fromCol] = explode(',', $from);
            [$toRow, $toCol] = explode(',', $to);

            $newRotation[$toRow][$toCol] = $rotation[$fromRow][$fromCol];
        }

        $stringPart = partToString($newRotation);
        if (isset($rules[$stringPart])) {
            echo sprintf('Found it! %s', $stringPart), "\n";

            return explode('/', $rules[$stringPart]);
        }

        $rotation = $newRotation;
    }

    return false;
}

/**
 * @param array $part
 *
 * @return string
 */
function partToString(array $part) : string
{
    return implode('/', array_map(function ($row) {
        if (is_string($row)) {
            return $row;
        }

        return implode('', $row);
    }, $part));
}

$grid = '.#./..#/###';

printGrid($grid);
echo "\n";

$rules = [];
$rawRules = explode("\n", file_get_contents(__DIR__ . '/input'));
foreach ($rawRules as $rawRule) {
    $match = preg_match('/^(.*)\ \=\>\ (.*)$/', $rawRule, $matches);

    if (!$match) {
        throw new \Exception(sprintf('Did not recognize %s', $rawRule));
    }

    list(, $from, $to) = $matches;

    $rules[$from] = $to;
}

function printGrid(string $grid)
{
    $part = explode('/', $grid);

    foreach ($part as $row) {
        echo implode(' ', str_split($row)), "\n";
    }
}

for ($i = 0; $i < 5; ++$i) {
    $parts = divideGridIntoParts($grid);

    $newGrid = [];
    /** @var array $row */
    foreach ($parts as $rowIndex => $row) {
        foreach ($row as $part) {
            $newPart = findReplacementPart($part, $rules);
            $sizeNewPart = count($newPart);

            $offset = $sizeNewPart * $rowIndex;

            foreach (range(0, $sizeNewPart - 1) as $newRow) {
                if (!isset($newGrid[$offset + $newRow])) {
                    $newGrid[$offset + $newRow] = '';
                }

                $newGrid[$offset + $newRow] .= $newPart[$newRow];
            }
        }
    }

    $grid = implode('/', $newGrid);

    printGrid($grid);
    echo "\n";
}

echo mb_substr_count($grid, '#');
