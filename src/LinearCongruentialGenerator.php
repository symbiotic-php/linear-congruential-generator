<?php

namespace Symbiotic\LinearCongruentialGenerator;


/**
 * Функция возвращает Простое число больше переданного
 *
 *
 * @param int $num
 *
 * @return int
 */
function getNextPrimeNumber(int $num): int
{
    if (\function_exists('\gmp_nextprime')) {
        return (int)\gmp_strval(\gmp_nextprime($num), 10);
    } else {
        if ($num === 1) {
            return 2;
        }
        $num++;
        // Делаем нечетным
        if ($num % 2 === 0) {
            $num++;
        }
        while (true) {
            $ceil = ceil(sqrt($num));
            for ($i = 3; $i <= $ceil; $i = $i + 2) {
                if ($num % $i == 0) {
                    $num += 2;
                    continue 2;
                }
            }

            return $num;
        }
    }
}

/**
 * Линейный конгруэнтный генератор псевдослучайных чисел в диапазоне MIN - MAX
 *
 * Генерирует уникальные числа в заданном диапазоне, до тех пор пока не будет пройден весь диапазон
 * После прохождения диапазона выдает числа в том же порядке, порядок генерации чисел зависит от начальной позиции
 *
 * Простые числа для смещения выбираются на основе переданного диапазона
 *
 * @param int      $min  Минимальное число для генерации
 * @param int      $max  Максимальное число для генерации
 * @param int|null $seed Начальная позиция генератора
 *
 * @return \Generator
 */
function LCGenerator(
    int $min = 1,
    int $max = PHP_INT_MAX - 1,
    ?int $seed = null
): \Generator {
    if ($max === PHP_INT_MAX) {
        throw new \InvalidArgumentException("The maximum number must be less than PHP_INT_MAX!");
    }
    if ($min >= $max) {
        throw new \InvalidArgumentException("The minimum number must be greater than the maximum in the range!");
    }
    if ($seed === null) {
        $seed = \random_int($min, $max);
    }
    $state = (string)$seed;

    $range = $max - $min;
    $primeNumber = $range > 20 ? getNextPrimeNumber($range > 1000 ? ($max / 13+$range/2 ) : $max / 5 ) : 2;
    if ($max % $primeNumber === 0) {
        $primeNumber = getNextPrimeNumber($primeNumber);
    }
    $secondPrimeNumber = getNextPrimeNumber($primeNumber*2);
    $stateIncrement = strlen((string)$range) % 2 === 0 ?  getNextPrimeNumber($secondPrimeNumber): 1;

    while (true) {
        $result = \bcmod(
            \bcadd(
                \bcmul($secondPrimeNumber, $state),
                $primeNumber
            ),
            $range + 1
        );
        $state = \bcadd($state, $stateIncrement);

        yield \intval($result) + $min;
    }
}

/**
 * Билдер для автолоада
 */
class LinearCongruentialGenerator
{
    public static function create(
        int $min = 1,
        int $max = PHP_INT_MAX - 1,
        ?int $seed = null
    ): \Generator {
        return LCGenerator($min, $max, $seed);
    }
}







