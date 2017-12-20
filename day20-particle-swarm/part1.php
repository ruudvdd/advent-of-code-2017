<?php

$serializedParticles = explode("\n", file_get_contents(__DIR__ . '/input'));

$coordinateRegex = '(\-?\d+)';
$coordinatesRegex = sprintf('\<%s,%s,%s\>', $coordinateRegex, $coordinateRegex, $coordinateRegex);

$particles = [];

function calcDistance($x, $y, $z)
{
    return abs($x) + abs($y) + abs($z);
}

$minAccelerationParticles = [];
$minDistance = PHP_INT_MAX;

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

    $distance = calcDistance($acceleration[0], $acceleration[1], $acceleration[2]);

    if ($distance < $minDistance) {
        $minAccelerationParticles = [$index];
        $minDistance = $distance;
        continue;
    }

    if ($distance === $minDistance) {
        $minAccelerationParticles[] = $index;
    }
}

if (count($minAccelerationParticles) === 1) {
    echo array_shift($minAccelerationParticles);
    exit();
}

$minVelocityParticles = [];
$minDistance = PHP_INT_MAX;

foreach ($minAccelerationParticles as $minParticleIndex) {
    $particleVelocity = $particles[$minParticleIndex]['velocity'];

    $distance = calcDistance($particleVelocity[0], $particleVelocity[1], $particleVelocity[2]);

    if ($distance < $minDistance) {
        $minVelocityParticles = [$minParticleIndex];
        $minDistance = $distance;
        continue;
    }

    if ($distance === $minDistance) {
        $minAccelerationParticles[] = $minParticleIndex;
    }
}

if (count($minVelocityParticles) === 1) {
    echo array_shift($minVelocityParticles);
    exit();
}

$minPositionParticles = [];
$minDistance = PHP_INT_MAX;

foreach ($minVelocityParticles as $minParticleIndex) {
    $particlePosition = $particles[$minParticleIndex]['position'];

    $distance = calcDistance($particlePosition[0], $particlePosition[1], $particlePosition[2]);

    if ($distance < $minDistance) {
        $minPositionParticles = [$minParticleIndex];
        $minDistance = $distance;
        continue;
    }

    if ($distance === $minDistance) {
        $minAccelerationParticles[] = $minParticleIndex;
    }
}

echo array_shift($minPositionParticles);
