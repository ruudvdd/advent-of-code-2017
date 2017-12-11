<?php

$rawProgram = file_get_contents(__DIR__ . '/input');

// parse program

$rawInstructions = explode("\n", $rawProgram);

function validCondition(string $conditionRepresentation, array $registers)
{
    $match = preg_match('/([a-z]+)\ ([\<\>\=\!]{1,2})\ (\-?\d+)/', $conditionRepresentation, $matches);

    if (!$match) {
        throw new \Exception(sprintf('Condition could not be read: %s', $conditionRepresentation));
    }

    $leftExpression = 0;

    $register = $matches[1];
    if (isset($registers[$register])) {
        $leftExpression = (int)$registers[$register];
    }

    $rightExpression = (int)$matches[3];

    $operator = $matches[2];
    switch ($operator) {
        case '<':
            return $leftExpression < $rightExpression;
        case '<=':
            return $leftExpression <= $rightExpression;
        case '>':
            return $leftExpression > $rightExpression;
        case '>=':
            return $leftExpression >= $rightExpression;
        case '==':
            return $leftExpression === $rightExpression;
        case '!=':
            return $leftExpression !== $rightExpression;
        default:
            throw new \Exception(sprintf('Could not find meaning of operator %s', $operator));
    }
}

$registers = [];
$maxValue = PHP_INT_MIN;

foreach ($rawInstructions as $rawInstruction) {
    $match = preg_match('/^([a-z]+)\ (inc|dec)\ (\-?\d+)\ if\ (.+)$/', $rawInstruction, $matches);

    if (!$match) {
        throw new \Exception(sprintf('Instruction could not be read: %s', $rawInstruction));
    }

    $register = $matches[1];
    $instruction = $matches[2];
    $weight = (int)$matches[3];
    $condition = $matches[4];


    if (!validCondition($condition, $registers)) {
        // skip
        continue;
    }

    if (!isset($registers[$register])) {
        $registers[$register] = 0;
    }

    if ($instruction === 'dec') {
        $registers[$register] -= $weight;
    } else {
        $registers[$register] += $weight;
    }

    $values = array_values($registers);

    if (count($values) === 1) {
        $maxValueThisCycle = array_shift($values);
    } else {
        $maxValueThisCycle = max(...array_values($registers));
    }

    if ($maxValue < $maxValueThisCycle) {
        $maxValue = $maxValueThisCycle;
    }
}

echo $maxValue;
