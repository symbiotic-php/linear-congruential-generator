# Linear congruential pseudo-random number generator in the range MIN - MAX

- For any range width from 3, it eliminates the repetition of numbers and gaps
- Generates unique numbers in the given range until the entire range is passed
- After passing the range, it produces numbers in the same order, the order of generation of numbers depends on the starting position
- Prime numbers for bias are chosen based on the passed range


``
Although the linear congruential method generates a statistically good pseudo-random sequence of numbers, it is not cryptographically secure. Generators based on the linear congruential method are predictable, so they cannot be used in cryptography.
``

## Installation

```
composer require symbiotic/linear-congruential-generator
```

## Usage

Important: The generator uses a cyclic range generation, so you must limit the number of iterations yourself!

```php
use \Symbiotic\LinearCongruentialGenerator\LinearCongruentialGenerator;
 $LCGenerator = LinearCongruentialGenerator::create(
    1,    // Minimum number to generate
    30,   // Max number to generate
    1     // Generator starting position
  );
 // 
 for ($i = 0; $i < 30; $i++) {
        echo $LCGenerator->current() . ' ';
        $LCGenerator->next();
 }
 // output: 25 18 11 4 27 20 13 6 29 22 15 8 1 24 17 10 3 26 19 12 5 28 21 14 7 30 23 16 9 2

```

## Comparison of generation with other algorithms

Algorithms for tests and comparisons are taken from the Internet
and [Wikipedia](https://ru.wikipedia.org/wiki/Linear_congruent_method) .
They are not adapted to work within a small range, so each result for is adapted by modulo equal to the range.

General formula:  X(n+1) = A*Xn + C mod M;

```text
 MMIX    M = 2^64     A = 6364136223846793005   C = 1442695040888963407
 Minstd  M = 2^31-1   A = 16807                 C = 0
 BSD     M = 2^31     A = 1103515245            C = 12345
 Msvcrt  M = 2^32     A = 214013                C = 2531011
```

### Numbers

```text
SymLCG
           10000 - 99999      78094      93491      18888      34285      49682      65079      80476      95873      21270      36667 
         100000 - 999999     780794     451233     121672     692111     362550     932989     603428     273867     844306     514745 
       1000000 - 9999999    7807750    9346253    1884756    3423259    4961762    6500265    8038768    9577271    2115774    3654277 
     10000000 - 99999999   78076998   42933901   97790804   62647707   27504610   82361513   47218416   12075319   66932222   31789125 
  100000000 - 9999999999 7357692322 4516864389 1676036456 8735208523 5894380590 3053552657  212724724 7271896791 4431068858 1590240925 

Minstd
           10000 - 99999      26807      65249      50073      83658      38930      61272      57544      40878      67923      67709 
         100000 - 999999     116807     875249     950073     443658     308930     511272     327544     850878     877923     337709 
       1000000 - 9999999    1016807    4475249    3650073    4943658    2108930    3211272    3027544    9850878    1777923    1237709 
     10000000 - 99999999   10016807   22475249   12650073   94943658   74108930   30211272   21027544   27850878   28777923   37237709 
  100000000 - 9999999999  100016807  382475249 1722650073 1084943658 1244108930  570211272  201027544 1557850878 1558777923 2107237709 

BSD
           10000 - 99999      47590      41575      74084      52781      35474      80899      89952      86185      77886      94847 
         100000 - 999999     227590     401575     524084     502781     215474     800899     629952     356185     617886     634847 
       1000000 - 9999999    6527590    9401575    6824084    5902781    2015474    9800899    6029952    1256185    1517886    7834847 
     10000000 - 99999999   33527590   27401575   42824084   77902781   65015474   18800899   78029952   46256185   82517886   97834847 
  100000000 - 9999999999 1203527590  477401575  762824084 1247902781 2135015474  468800899 1608029952  586256185 1162517886  367834847 

MMIX

           10000 - 99999      16412      89417      50806      33811      16816      68205      51210      34215      85604      68609 
         100000 - 999999     556412     449417     590806     483811     376816     518205     411210     304215     445604     338609 
       1000000 - 9999999    7756412    8549417    7790806    8583811    9376816    8618205    9411210    1204215    9445604    1238609 
     10000000 - 99999999   25756412   62549417   79790806   26583811   63376816   80618205   27411210   64204215   81445604   28238609 
  100000000 - 9999999999 6235756412 4382549417  619790806 8666583811 6813376816 3050618205 1197411210 9244204215 5481445604 3628238609 

Msvcrt
           10000 - 99999      10041      10172      10600      11997      16559      31457      14572      24968      26149      30006 
         100000 - 999999     100041     100172     100600     101997     106559     121457     104572     114968     116149     120006 
       1000000 - 9999999    1000041    1000172    1000600    1001997    1006559    1021457    1004572    1014968    1016149    1020006 
     10000000 - 99999999   10000041   10000172   10000600   10001997   10006559   10021457   10004572   10014968   10016149   10020006 
  100000000 - 9999999999  100000041  100000172  100000600  100001997  100006559  100021457  100004572  100014968  100016149  100020006 
```


### Omissions and collisions of numbers when working in limited ranges

```text
       Generator duplicate/missed (time)            SymLCG                         MMIX                         Msvcrt                         BSD                          Minstd            

                                   2 - 4        0 0 (0.099ms)             1      1 (0.037ms)            0      0 (0.022ms)              1      1 (0.020ms)              0      0 (0.030ms)         
                                   1 - 7        0 0 (0.041ms)             2      2 (0.059ms)            1      1 (0.039ms)              3      3 (0.037ms)              1      6 (0.055ms)         
                                 13 - 87        0 0 (0.385ms)            26     26 (0.571ms)           21     28 (0.313ms)              6      6 (0.410ms)              0      0 (0.536ms)         
                                 1 - 100        0 0 (0.518ms)            40     40 (0.944ms)           27     40 (0.496ms)             12     12 (0.464ms)              0      0 (0.672ms)         
                                73 - 100        0 0 (0.150ms)             6      6 (0.211ms)            8      9 (0.109ms)              0      0 (0.128ms)              4     24 (0.191ms)        
                                23 - 139        0 0 (0.599m              43     43 (0.908ms)           32     45 (0.436ms)             24     24 (0.511ms)              0      0 (0.860ms)         
                                1 - 1000        0 0 (4.473ms)           472    472 (7.174ms)          126    874 (3.706ms)             53     53 (4.524ms)              0      0 (6.480ms)         
                              457 - 1325        0 0 (3.949ms)           196    196 (6.486ms)          125    744 (3.142ms)             80     80 (4.023ms)              0      0 (5.747ms)         
                              1 - 200000        0 0 (1.035s)          46913 145407 (1.913s)           135 199865 (760.741ms)        54366  54366 (897.711ms)        49879  49879 (1.747s)     
                         100001 - 200000        0 0 (506.476ms)       21345  76736 (799.293ms)        135  99865 (434.825ms)        24948  24948 (717.396ms)            0      0 (726.560ms)        
                         100001 - 300000        0 0 (1.076s)          46913 145407 (1.983s)           135 199865 (743.496ms)        54366  54366 (878.400ms)        49879  49879 (1.365s)     
                       1304001 - 2000000        0 0 (3.579s)          16934  16934 (5.364s)           135 695865 (2.528s)           43550  43550 (3.028s)          255743 369619 (4.880s)    
9223372036854675807 - 9223372036854775806       0 0 (546.518ms)       21345  76736 (762.389ms)        135  99865 (364.254ms)        24948  24948 (436.867ms)            0      0 (690.514ms)        
9223372036853775807 - 9223372036854775806       0 0 (6.761s)         403515 404456 (7.858s)           135 999865 (3.628s)          114868 114868 (4.337s)          100726 100726 (7.011s)
```



