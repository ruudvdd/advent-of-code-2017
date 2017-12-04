<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input');

$passphrases = explode("\n", $input);

function isValidPassphrase(string $passphrase) : bool {
    $words = explode(' ', $passphrase);

    $uniqueWords = [];

    if (!is_array($words)) {
        var_dump($words);
    }

    foreach ($words as $word) {
        if (isset($uniqueWords[$word])) {
            return false;
        }

        $uniqueWords[$word] = true;
    }

    return true;
}

$validPassphrases = 0;

foreach ($passphrases as $passphrase) {
    if (isValidPassphrase($passphrase)) {
        $validPassphrases++;
    }
}

echo $validPassphrases;
