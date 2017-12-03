<?php

final class ShellInfo
{
    public $numbersOnOneSide;
    public $lastShellNumber;
    public $shellNumber;

    public function __construct(int $numbersOnOneSide, int $lastShellNumber, int $shellNumber)
    {
        $this->numbersOnOneSide = $numbersOnOneSide;
        $this->lastShellNumber = $lastShellNumber;
        $this->shellNumber = $shellNumber;
    }

    public static function createByNumber(int $number) : self
    {
        $lastShellNumber = 1;
        $numbersOnOneSide = 1;
        $shell = 0;

        if ($number === 1) {
            return new ShellInfo(1, 1, 0);
        }

        while (true) {
            if ($number <= $lastShellNumber) {
                return new ShellInfo($numbersOnOneSide, $lastShellNumber, $shell);
            }

            $shell++;
            $numbersOnOneSide += 2;
            $lastShellNumber += ($numbersOnOneSide * 4) - 4;
        }

        throw new OutOfBoundsException(sprintf('Could not find shell of number %s', $number));
    }
}

final class SideInfo
{
    const SIDE_RIGHT = 0;
    const SIDE_TOP = 1;
    const SIDE_LEFT = 2;
    const SIDE_BOTTOM = 3;

    public $shellInfo;
    public $side;
    public $firstCorner;
    public $lastCorner;

    public function __construct(ShellInfo $shellInfo, int $side, int $firstCorner, int $lastCorner)
    {
        $this->shellInfo = $shellInfo;
        $this->side = $side;
        $this->firstCorner = $firstCorner;
        $this->lastCorner = $lastCorner;
    }

    public static function createByNumber(int $number) : self
    {
        $firstCorner = false;
        $shellInfo = ShellInfo::createByNumber($number);

        for ($side = self::SIDE_BOTTOM; $side >= 0; $side--) {
            $lastCorner = $firstCorner ?: $shellInfo->lastShellNumber;
            // SIDE_RIGHT will normally have the last number of the shell as his first corner,
            // but for further calculations, we will assume it's the last number of the previous shell
            $firstCorner = ($lastCorner - $shellInfo->numbersOnOneSide) + 1;


            if ($number <= $lastCorner && $number >= $firstCorner) {
                return new SideInfo($shellInfo, $side, $firstCorner, $lastCorner);
            }
        }

        throw new \OutOfBoundsException(
            sprintf('Could not find the side on shell %s where %s is located', $shellInfo->shellNumber, $number)
        );
    }
}
