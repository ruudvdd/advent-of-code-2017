<?php

declare(strict_types=1);

$input = (int)file_get_contents(__DIR__ . '/input');

require __DIR__ . '/SideInfo.php';

$sideInfo = SideInfo::createByNumber($input);

// get center number of the side
$centerSideNumber = ($sideInfo->lastCorner + $sideInfo->firstCorner) / 2;

// get steps to the center of the side
$stepsToCenterOfSide = abs($centerSideNumber - $input);

// the amount of steps, needed to make from the center of the shell to the number 1 equals to the shell number we found
// before
$stepsToCenterOfSpiral = $sideInfo->shellInfo->shellNumber;

// steps to the center of the spiral equals the steps to the center of the side + the steps to the center of the spiral
echo $stepsToCenterOfSide + $stepsToCenterOfSpiral;
