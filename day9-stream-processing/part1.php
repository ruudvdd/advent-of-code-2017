<?php

$stream = str_split(file_get_contents(__DIR__ . '/input'));

$ignore = false;
$garbage = false;

$totalScore = 0;

$localScore = 0;

foreach ($stream as $currentCharacter) {
    if ($ignore) {
        $ignore = false;
        continue;
    }

    if ($currentCharacter === '!') {
        $ignore = true;
        continue;
    }

    if ($currentCharacter === '<' && !$garbage) {
        $garbage = true;
        continue;
    }

    if ($currentCharacter === '>' && $garbage) {
        $garbage = false;
        continue;
    }

    if ($currentCharacter === '{' && !$garbage) {
        $localScore++;
        continue;
    }

    if ($currentCharacter === '}' && !$garbage) {
        $totalScore += $localScore;
        $localScore--;
        continue;
    }
}

echo $totalScore;
