<?php

include_once dirname(__DIR__) . '/src/LinearCongruentialGenerator.php';


function testScatter(int $min, int $max, ?int $seed, \Closure|string $generator, int $lastNumbers = 10)
{
    echo PHP_EOL . str_pad($min . ' - ' . $max . ' ', 42, ' ', STR_PAD_LEFT);
    $generator = $generator($min, $max, $seed);
    for ($i = 0; $i < 10; $i++) {
        echo str_pad(substr($generator->current(), -$lastNumbers, $lastNumbers) . ' ', 11, ' ', STR_PAD_LEFT);
        $generator->next();
    }
}



function testLinearCongruentialGenerator(int $start, int $end, string|\Closure $generator, bool $showNumbers = false)
{
    $startTime = microtime(true);

    $generator = $generator($start, $end, 1);

    $numbers = [];

    for ($i = $start; $i <= $end; $i++) {
        $numbers[$i] = 0;
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($showNumbers) {
            echo $generator->current() . PHP_EOL;
        }
        $numbers[$generator->current()]++;
        $generator->next();
    }
    if ($showNumbers) {
        echo PHP_EOL;
    }

    $numbersMany = array_filter($numbers, fn($count) => $count > 1);
    $numbersZero = array_filter($numbers, fn($v) => $v === 0);


    $allTime = microtime(true) - $startTime;
    if ($allTime >= 1) {
        $unit = 's';
        $time = round($allTime, 3);
    } else {
        $unit = 'ms';
        $time = round($allTime * 1000, 3);
    }


    return [
        'duplicate' => count($numbersMany),
        'missed' => count($numbersZero),
        'time' => sprintf('%.3f%s', $time, $unit)
    ];
}


return  [
    'SymLCG' => '\Symbiotic\LinearCongruentialGenerator\LCGenerator',
    'MMIX' => function (
        int $min = 1,
        int $max = PHP_INT_MAX - 1,
        ?int $seed = null
    ): \Generator {
        if ($min >= $max) {
            throw new \RangeException("The minimum number must be greater than the maximum in the range!");
        }
        if ($seed === null) {
            $seed = \random_int($min, $max);
        }
        $state = (string)$seed;
        $range = $max - $min;

        while (true) {
            $result = \bcmod(
                \bcadd(
                    \bcmul('6364136223846793005', $state),

                    '1442695040888963407',
                ),
                bcpow(2, 64)
            );
            $state = \bcadd($state, 1);

            yield \intval(bcmod($result, $range + 1)) + $min;
        }
    },
    'Msvcrt' => function (
        int $min = 1,
        int $max = PHP_INT_MAX,
        ?int $seed = null
    ): \Generator {
        if ($min >= $max) {
            throw new \RangeException("The minimum number must be greater than the maximum in the range!");
        }
        if ($seed === null) {
            $seed = \random_int($min, $max);
        }
        $state = (string)$seed;
        $range = $max - $min;

        while (true) {
            $result = ((214013 * $state + 2531011) % (1 << 31)) >> 16;
            $state = $result;

            yield \intval(bcmod($result, $range+1)) + $min;
        }
    },
    'BSD' => function (
        int $min = 1,
        int $max = PHP_INT_MAX,
        ?int $seed = null
    ): \Generator {
        if ($min >= $max) {
            throw new \RangeException("The minimum number must be greater than the maximum in the range!");
        }
        if ($seed === null) {
            $seed = \random_int($min, $max);
        }
        $state = (string)$seed;
        $range = $max - $min;

        while (true) {
            $result = (1103515245 * $state + 12345) % (1 << 31);
            $state = \bcadd($state, 1);

            yield \intval(bcmod($result, $range + 1)) + $min;
        }
    },
    'Minstd' => function (
        int $min = 1,
        int $max = PHP_INT_MAX,
        ?int $seed = null
    ): \Generator {
        if ($min >= $max) {
            throw new \RangeException("The minimum number must be greater than the maximum in the range!");
        }
        if ($seed === null) {
            $seed = \random_int($min, $max);
        }
        $state = (string)$seed;
        $range = $max - $min;

        while (true) {
            $result = \bcmod(
                \bcadd(
                    \bcmul('16807', $state),

                    '0',
                ),
                bcsub(bcpow(2, 31), 1)
            );
            $state = \bcadd($state, 1);

            yield \intval(bcmod($result, $range + 1)) + $min;
        }
    }
];