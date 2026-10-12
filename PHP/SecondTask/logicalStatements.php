<?php

// 1. Check if the year is a leap year

$year = 2023;

if (($year % 400 == 0) || ($year % 4 == 0 && $year % 100 != 0)) {
    echo "This year is a leap year";
} else {
    echo "This year is not a leap year";
}

echo "<br><br>";


// 2. Check the season depending on temperature

$temperature = 27;

if ($temperature < 20) {
    echo "It is wintertime!";
} else {
    echo "It is summertime!";
}

echo "<br><br>";


// 3. Calculate sum, or triple the sum if the numbers are equal

$firstInteger = 2;
$secondInteger = 2;

$sum = $firstInteger + $secondInteger;

if ($firstInteger == $secondInteger) {
    $result = $sum * 3;
} else {
    $result = $sum;
}

echo "( $firstInteger + $secondInteger ) = $result";

echo "<br><br>";


// 4. Numbers between 200 and 250 divisible by 4

for ($i = 200; $i <= 250; $i++) {

    if ($i % 4 == 0) {
        echo $i . " ";
    }

}

echo "<br><br>";


// 5. Generate 10 unique random numbers between 11 and 20

$numbers = [];

while (count($numbers) < 10) {

    $random = rand(11, 20);

    if (!in_array($random, $numbers)) {
        $numbers[] = $random;
    }

}

echo implode(" ", $numbers);

?>