<?php

$memoryBanks = array_map(function ($blocks) {
    return (int)$blocks;
}, explode("\t", file_get_contents(__DIR__ . '/input')));

$previousStates = [];

function serializeMemoryBank(array $memoryBanks)
{
    return implode('|', $memoryBanks);
}

$serializedState = serializeMemoryBank($memoryBanks);

$steps = 0;

while (!isset($previousStates[$serializedState])) {
    $previousStates[$serializedState] = 0;

    // find memory bank with the most blocks
    $bankWithMostBlocks = array_search(max(...$memoryBanks), $memoryBanks, true);

    $blocksToRedistribute = $memoryBanks[$bankWithMostBlocks];
    $memoryBanks[$bankWithMostBlocks] = 0;

    $position = $bankWithMostBlocks + 1;
    for ($blocks = $blocksToRedistribute; $blocks > 0; $blocks--) {
        $position %= count($memoryBanks);

        $memoryBanks[$position]++;
        $position++;
    }

    foreach ($previousStates as $state => $previousStateSteps) {
        $previousStates[$state] = $previousStateSteps + 1;
    }

    $serializedState = serializeMemoryBank($memoryBanks);
    $steps++;
}

echo $previousStates[$serializedState];
