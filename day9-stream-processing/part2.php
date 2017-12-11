<?php

$stream = str_split(file_get_contents(__DIR__ . '/input'));

$ignore = false;
$garbage = false;

$garbageCharacterCount = 0;

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
        continue;
    }

    if ($currentCharacter === '}' && !$garbage) {
        continue;
    }

    if ($garbage) {
        $garbageCharacterCount++;
    }
}

echo $garbageCharacterCount;
