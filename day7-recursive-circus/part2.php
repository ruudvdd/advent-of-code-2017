<?php

$input = file_get_contents(__DIR__ . '/input');

$programs = explode("\n", $input);

$programsWithChildren = [];
$parentsByChildren = [];
$weights = [];

foreach ($programs as $program) {
    $match = preg_match('/([a-z]+)\ \((\d+\))(?: \-\>\ ([a-z\,\ ]+))?/', $program, $matches);

    if (!$match) {
        throw new \RuntimeException(sprintf('Program %s did not match the regular expression', $program));
    }

    $children = isset($matches[3]) ? explode(', ', $matches[3]) : [];

    $programName = $matches[1];
    foreach ($children as $child) {
        $parentsByChildren[$child] = $programName;
    }

    $weight = (int)$matches[2];
    $weights[$programName] = $weight;
    $programsWithChildren[$programName] = $children;
}

reset($programsWithChildren);
$currentProgram = key($programsWithChildren);

while (isset($parentsByChildren[$currentProgram])) {
    $currentProgram = $parentsByChildren[$currentProgram];
}

$bottomProgram = $currentProgram;

function findWeightOfProgram($bottomProgram, $programsWithChildren, $weights)
{
    $children = (array)$programsWithChildren[$bottomProgram];

    if (empty($children)) {
        return $weights[$bottomProgram];
    }

    $totalWeight = $weights[$bottomProgram];
    foreach ($children as $child) {
        $totalWeight += findWeightOfProgram($child, $programsWithChildren, $weights);
    }

    return $totalWeight;
}

function findWrongChild($program, $programsWithChildren, $weights)
{
    $children = $programsWithChildren[$program];
    $weightChildrenMap = [];

    foreach ($children as $child) {
        $weightOfChild = findWeightOfProgram($child, $programsWithChildren, $weights);

        if (!isset($weightChildrenMap[$weightOfChild])) {
            $weightChildrenMap[$weightOfChild] = [];
        }

        $weightChildrenMap[$weightOfChild][] = $child;
    }

    if (count($weightChildrenMap) === 1) {
        return null;
    }

    if (count($weightChildrenMap) > 2) {
        throw new \Exception('Multiple programs wrong!!');
    }

    $firstWeightPrograms = array_shift($weightChildrenMap);
    $secondWeightPrograms = array_shift($weightChildrenMap);

    if (count($firstWeightPrograms) === count($secondWeightPrograms)) {
        var_dump($firstWeightPrograms);
        var_dump($secondWeightPrograms);
        throw new \Exception('Witch one is wrong!!');
    }

    if (count($firstWeightPrograms) === 1) {
        $program = array_shift($firstWeightPrograms);
        return $program;
    }

    if (count($secondWeightPrograms) === 1) {
        $program = array_shift($secondWeightPrograms);
        return $program;
    }

    throw new \Exception('Witch one is wrong 2!!');
}

$currentProgram = $bottomProgram;

while (true) {
    $nextProgram = findWrongChild($currentProgram, $programsWithChildren, $weights);

    if ($nextProgram === null) {
        $parentProgram = $parentsByChildren[$currentProgram];
        $children = $programsWithChildren[$parentProgram];

        $weightsOfChildren = [];
        foreach ($children as $child) {
            $weightsOfChildren[$child] = [
                findWeightOfProgram($child, $programsWithChildren, $weights),
                $weights[$child],
            ];

        }

        print_r($weightsOfChildren);
        break;
    }

    $currentProgram = $nextProgram;
}
