<?php


$generators = include __DIR__ . '/Generators.php';


testScatter(1, 10, 1, $generators['SymLCG']);
testScatter(1, 20, 1, $generators['SymLCG']);
testScatter(1, 30, 1, $generators['SymLCG']);
testScatter(1, 40, 1, $generators['SymLCG']);
testScatter(1, 50, 1, $generators['SymLCG']);
testScatter(1, 70, 1, $generators['SymLCG']);
testScatter(1, 100, 1, $generators['SymLCG']);
testScatter(100, 999, 1, $generators['SymLCG']);
testScatter(1000, 2000, 1, $generators['SymLCG']);
testScatter(1000, 9999, 1, $generators['SymLCG']);
testScatter(10000, 99999, 1, $generators['SymLCG']);
testScatter(100000, 999999, 1, $generators['SymLCG']);
testScatter(1000000, 9999999, 1, $generators['SymLCG']);
testScatter(10000000, 99999999, 1, $generators['SymLCG']);
testScatter(100000000, 9999999999, 1, $generators['SymLCG']);
testScatter(PHP_INT_MAX-100000000, PHP_INT_MAX-1, 1, $generators['SymLCG']);


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
];


$generators = [
    'SymLCG' => '\Symbiotic\LinearCongruentialGenerator\LCGenerator',
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
echo PHP_EOL . str_pad('Generator duplicate/missed (time)', 40, ' ', STR_PAD_LEFT);
foreach (array_keys($generators) as $name) {
    echo str_pad($name, 30, ' ', STR_PAD_BOTH);
}
echo PHP_EOL . PHP_EOL;

foreach ($results as $name => $data) {
    echo str_pad($name, 40, ' ', STR_PAD_LEFT);
    foreach ($data as $v) {
        echo str_pad($v['duplicate'] . ' ' . $v['missed'] . ' (' . $v['time'] . ')', 30, ' ', STR_PAD_BOTH);
    }
    echo PHP_EOL;
}








