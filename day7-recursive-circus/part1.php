<?php

$input = file_get_contents(__DIR__ . '/input');

$programs = explode("\n", $input);

$programsWithChildren = [];
$parentsByChildren = [];

foreach ($programs as $program) {
    $match = preg_match('/([a-z]+)\ \(\d+\)(?: \-\>\ ([a-z\,\ ]+))?/', $program, $matches);

    if (!$match) {
        throw new \RuntimeException(sprintf('Program %s did not match the regular expression', $program));
    }

    $children = isset($matches[2]) ? explode(', ', $matches[2]) : [];

    foreach ($children as $child) {
        $parentsByChildren[$child] = $matches[1];
    }

    $programsWithChildren[$matches[1]] = $children;
}

reset($programsWithChildren);
$currentProgram = key($programsWithChildren);

while (isset($parentsByChildren[$currentProgram])) {
    $currentProgram = $parentsByChildren[$currentProgram];
}

echo $currentProgram;
