<?php

declare(strict_types=1);

$input = (int)file_get_contents(__DIR__ . '/input');

final class ShellInfo
{
    public $numbersOnOneSide;
    public $lastShellNumber;
    public $shellNumber;

    public function __construct(int $numbersOnOneSide, int $lastShellNumber, int $shellNumber)
    {
        $this->numbersOnOneSide = $numbersOnOneSide;
        $this->lastShellNumber = $lastShellNumber;
        $this->shellNumber = $shellNumber;
    }
}

function findShell(int $number) : ShellInfo
{
    $lastShellNumber = 1;
    $numbersOnOneSide = 1;
    $shell = 0;

    while (true) {
        if ($number <= $lastShellNumber) {
            return new ShellInfo($numbersOnOneSide, $lastShellNumber, $shell);
        }

        $shell++;
        $numbersOnOneSide += 2;
        $lastShellNumber += ($numbersOnOneSide * 4) - 4;
    }

    throw new OutOfBoundsException(sprintf('Could not find shell of number %s', $number));
}

const SIDE_RIGHT = 0;
const SIDE_TOP = 1;
const SIDE_LEFT = 2;
const SIDE_BOTTOM = 3;

final class SideInfo {
    public $shellInfo;
    public $side;
    public $firstCorner;
    public $lastCorner;

    public function __construct(ShellInfo $shellInfo, int $side, int $firstCorner, int $lastCorner)
    {
        $this->shellInfo = $shellInfo;
        $this->side = $side;
        $this->firstCorner = $firstCorner;
        $this->lastCorner = $lastCorner;
    }
}

function findSideOnShell(ShellInfo $shellInfo, $number) : SideInfo
{
    $firstCorner = false;

    for ($side = SIDE_BOTTOM; $side >= 0; $side--) {
        $lastCorner = $firstCorner ?: $shellInfo->lastShellNumber;
        $firstCorner = ($lastCorner - $shellInfo->numbersOnOneSide) + 1;


        if ($number <= $lastCorner && $number >= $firstCorner) {
            return new SideInfo($shellInfo, $side, $firstCorner, $lastCorner);
        }
    }

    throw new \OutOfBoundsException(
        sprintf('Could not find the side on shell %s where %s is located', $shellInfo->shellNumber, $number)
    );
}

$sideInfo = findSideOnShell(findShell($input), $input);

// get center number of the side
$centerSideNumber = ($sideInfo->lastCorner + $sideInfo->firstCorner) / 2;

// get steps to the center of the side
$stepsToCenterOfSide = abs($centerSideNumber - $input);

// the amount of steps, needed to make from the center of the shell to the number 1 equals to the shell number we found
// before
$stepsToCenterOfSpiral = $sideInfo->shellInfo->shellNumber;

// steps to the center of the spiral equals the steps to the center of the side + the steps to the center of the spiral
echo $stepsToCenterOfSide + $stepsToCenterOfSpiral;
