<?php


$generators = include __DIR__ . '/Generators.php';

/**
 * Compare Numbers
 */

echo PHP_EOL . PHP_EOL . 'SymLCG' . PHP_EOL;
testScatter(10000, 99999, 1, $generators['SymLCG']);
testScatter(100000, 999999, 1, $generators['SymLCG']);
testScatter(1000000, 9999999, 1, $generators['SymLCG']);
testScatter(10000000, 99999999, 1, $generators['SymLCG']);
testScatter(100000000, 9999999999, 1, $generators['SymLCG']);

echo PHP_EOL . PHP_EOL . 'Minstd' . PHP_EOL;
testScatter(10000, 99999, 1, $generators['Minstd']);
testScatter(100000, 999999, 1, $generators['Minstd']);
testScatter(1000000, 9999999, 1, $generators['Minstd']);
testScatter(10000000, 99999999, 1, $generators['Minstd']);
testScatter(100000000, 9999999999, 1, $generators['Minstd']);

echo PHP_EOL . PHP_EOL . 'BSD' . PHP_EOL;
testScatter(10000, 99999, 1, $generators['BSD']);
testScatter(100000, 999999, 1, $generators['BSD']);
testScatter(1000000, 9999999, 1, $generators['BSD']);
testScatter(10000000, 99999999, 1, $generators['BSD']);
testScatter(100000000, 9999999999, 1, $generators['BSD']);

echo PHP_EOL . PHP_EOL . 'MMIX' . PHP_EOL;
testScatter(10000, 99999, 1, $generators['MMIX']);
testScatter(100000, 999999, 1, $generators['MMIX']);
testScatter(1000000, 9999999, 1, $generators['MMIX']);
testScatter(10000000, 99999999, 1, $generators['MMIX']);
testScatter(100000000, 9999999999, 1, $generators['MMIX']);

echo PHP_EOL . PHP_EOL . 'Msvcrt' . PHP_EOL;
testScatter(10000, 99999, 1, $generators['Msvcrt']);
testScatter(100000, 999999, 1, $generators['Msvcrt']);
testScatter(1000000, 9999999, 1, $generators['Msvcrt']);
testScatter(10000000, 99999999, 1, $generators['Msvcrt']);
testScatter(100000000, 9999999999, 1, $generators['Msvcrt']);


/**
 * Omissions and collisions of numbers when working in limited ranges
 */

$testNumbers = [
    [
        'min' => 2,
        'max' => 4,
    ],
    [
        'min' => 1,
        'max' => 7,
    ],
    [
        'min' => 13,
        'max' => 87,
    ],
    [
        'min' => 1,
        'max' => 100,
    ],
    [
        'min' => 73,
        'max' => 100,
    ],
    [
        'min' => 23,
        'max' => 139,
    ],
    [
        'min' => 1,
        'max' => 1000,
    ],
    [
        'min' => 457,
        'max' => 1325,
    ],
    [
        'min' => 1,
        'max' => 200000,
    ],
     [
        'min' => 100001,
        'max' => 200000,
    ],
   [
        'min' => 100001,
        'max' => 300000,
    ],
    [
        'min' => 1304001,
        'max' => 2000000,
    ],
    [
        'min' => PHP_INT_MAX - 100000,
        'max' => PHP_INT_MAX - 1,
    ],
    [
        'min' => PHP_INT_MAX - 1000000,
        'max' => PHP_INT_MAX - 1,
    ],
];

$results = [];
foreach ($testNumbers as $data) {
    if (!isset($results[$data['min'] . ' - ' . $data['max']])) {
        $results[$data['min'] . ' - ' . $data['max']] = [];
    }
    foreach ($generators as $name => $generator) {
        $results[$data['min'] . ' - ' . $data['max']][$name] = testLinearCongruentialGenerator(
            $data['min'],
            $data['max'],
            $generator
        );
    }
}
echo str_pad('Generator duplicate/missed (time)', 40, ' ', STR_PAD_LEFT);
foreach (array_keys($generators) as $name) {
    echo str_pad($name, 30, ' ', STR_PAD_BOTH);
}
echo PHP_EOL . PHP_EOL;

foreach ($results as $name => $data) {
    echo str_pad($name, 40, ' ', STR_PAD_LEFT);
    foreach ($data as $v) {
        echo str_pad($v['duplicate'] . ' ' . $v['missed'] . ' (' . $v['time'].')', 30, ' ', STR_PAD_BOTH);
    }
    echo PHP_EOL;
}

//testLinearCongruentialGenerator(100001,200000,$generators['Msvcrt'],true);
