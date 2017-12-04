<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . '/input');

$passphrases = explode("\n", $input);

function isValidPassphrase(string $passphrase) : bool
{
    $words = explode(' ', $passphrase);

    $uniqueWords = [];

    foreach ($words as $word) {
        $characters = str_split($word);
        sort($characters);

        $alphabeticallyOrderedWord = implode($characters);

        foreach ($uniqueWords as $uniqueWord => $_) {
            if ($alphabeticallyOrderedWord === $uniqueWord) {
                return false;
            }
        }

        $uniqueWords[$alphabeticallyOrderedWord] = true;
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
