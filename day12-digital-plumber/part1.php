<?php

$pipes = explode("\n", file_get_contents(__DIR__ . '/input'));

$pipeNetwork = [];

foreach ($pipes as $pipe) {
    $match = preg_match('/^(\d+)\ \<\-\>\ ((?:\d+(?:\, )?)+)$/', $pipe, $matches);

    list(, $pipe, $childPipes) = $matches;
    $pipeNetwork[(int)$pipe] = array_map(function ($childPipe) {
        return (int)$childPipe;
    }, explode(', ', $childPipes));
}

function isConnectedWith($program, $pipe, $pipeNetwork, array &$passedPipes = [])
{
    if ($pipe === $program) {
        return true;
    }

    $passedPipes[$pipe] = true;

    $connectedPipes = $pipeNetwork[$pipe];

    if (in_array($program, $connectedPipes, true)) {
        return true;
    }

    foreach ($connectedPipes as $connectedPipe) {
        if (isset($passedPipes[$connectedPipe])) {
            // infinite loop protection
            continue;
        }

        if (isConnectedWith($program, $connectedPipe, $pipeNetwork, $passedPipes)) {
            return true;
        }
    }

    return false;
}

$connectedWithZero = 0;

foreach ($pipeNetwork as $pipe => $connectedPipes) {
    if (isConnectedWith(0, $pipe, $pipeNetwork)) {
        $connectedWithZero++;
    }
}

echo $connectedWithZero;