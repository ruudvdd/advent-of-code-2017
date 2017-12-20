<?php

$serializedParticles = explode("\n", file_get_contents(__DIR__ . '/input'));

$coordinateRegex = '(\-?\d+)';
$coordinatesRegex = sprintf('\<%s,%s,%s\>', $coordinateRegex, $coordinateRegex, $coordinateRegex);

$particles = [];

foreach ($serializedParticles as $index => $serializedParticle) {
    $match = preg_match(
        '/^p\=' . $coordinatesRegex . '\,\ v\=' . $coordinatesRegex . '\,\ a\=' . $coordinatesRegex . '$/',
        $serializedParticle,
        $matches
    );

    if (!$match) {
        throw new \RuntimeException(sprintf('Could not read particle %s', $serializedParticle));
    }

    array_shift($matches);

    $position = array_splice($matches, 0, 3);
    $velocity = array_splice($matches, 0, 3);
    $acceleration = array_splice($matches, 0, 3);

    $particles[] = [
        'position'     => $position,
        'velocity'     => $velocity,
        'acceleration' => $acceleration,
    ];
}

function calcDistance($x, $y, $z)
{
    return abs($x) + abs($y) + abs($z);
}

$steps = 0;

while (count($particles) > 1) {
    $groupedByDistance = [];

    foreach ($particles as $particleIndex => &$particle) {
        $particle['velocity'][0] += $particle['acceleration'][0];
        $particle['velocity'][1] += $particle['acceleration'][1];
        $particle['velocity'][2] += $particle['acceleration'][2];

        $particle['position'][0] += $particle['velocity'][0];
        $particle['position'][1] += $particle['velocity'][1];
        $particle['position'][2] += $particle['velocity'][2];

        $position = $particle['position'];
        $coords = $position[0] . ',' . $position[1] . ',' . $position[2];

        if (!isset($groupedByDistance[$coords])) {
            $groupedByDistance[$coords] = [];
        }

        $groupedByDistance[$coords][] = $particleIndex;
    }
    unset($particle);

    $moreThanTwoIndexes = array_filter($groupedByDistance, function ($particleIndexes) {
        return count($particleIndexes) > 1;
    });

    foreach ($moreThanTwoIndexes as $group) {
        foreach ($group as $particleIndex) {
            unset($particles[$particleIndex]);
        }
    }

    if ($steps > 1000) {
        // be lazy and just end after 1000 steps
        break;
    }

    $steps++;
}

echo count($particles);
