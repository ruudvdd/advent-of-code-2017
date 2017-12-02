<?php

const ROW_SEPARATOR = "\n";
const COLUMN_SEPARATOR = "\t";


$input = file_get_contents(__DIR__ . '/input');

$rows = explode(ROW_SEPARATOR, $input);

$inputMatrix = array_map(function ($row) {
    return explode(COLUMN_SEPARATOR, $row);
}, $rows);

$result = 0;

/**
 * @param array $rowCells
 *
 * @return int
 */
function getUniqueDivision(array $rowCells) : int
{
    $numberOfCells = count($rowCells);

    /** @var array $rowCells */
    foreach ($rowCells as $i => $firstCell) {
        $firstCell = (int)$firstCell;

        for ($j = $i + 1; $j < $numberOfCells; ++$j) {
            $secondCell = (int)$rowCells[$j];

            if ($secondCell % $firstCell === 0) {
                return $secondCell / $firstCell;
            }

            if ($firstCell % $secondCell === 0) {
                return $firstCell / $secondCell;
            }
        }
    }

    return 0;
}

foreach ($inputMatrix as $rowCells) {
    $result += getUniqueDivision($rowCells);
}

echo $result;
