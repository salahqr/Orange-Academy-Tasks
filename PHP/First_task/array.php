<?php

$colors = array('white', 'green', 'red');

print_r($colors);
echo "<br>";

$cities = array(
    "Italy" => "Rome",
    "Luxembourg" => "Luxembourg",
    "Belgium" => "Brussels",
    "Denmark" => "Copenhagen",
    "Finland" => "Helsinki",
    "France" => "Paris",
    "Slovakia" => "Bratislava",
    "Slovenia" => "Ljubljana",
    "Germany" => "Berlin",
    "Greece" => "Athens",
    "Ireland" => "Dublin",
    "Netherlands" => "Amsterdam",
    "Portugal" => "Lisbon",
    "Spain" => "Madrid"
);

asort($cities);

foreach ($cities as $key => $value) {
    echo "The capital of " . $key . " is " . $value . "<br>";
}


$color = array(
    4 => 'white',
    6 => 'green',
    11 => 'red'
);

echo $color[4] . "<br>";


$numbers = array(1, 2, 3, 4, 5);

$position = 4;
$newItem = "$";

array_splice($numbers, $position - 1, 0, $newItem);

echo implode(" ", $numbers) . "<br>";


$fruits = array(
    "d" => "lemon",
    "a" => "orange",
    "b" => "banana",
    "c" => "apple"
);

krsort($fruits);

foreach ($fruits as $key => $value) {
    echo $key . " = " . $value . "<br>";
}


$number = array(
    78, 60, 62, 68, 71, 68, 73, 85, 66, 64,
    76, 63, 75, 76, 73, 68, 62, 73, 72, 65,
    74, 62, 62, 65, 64, 68, 73, 75, 79, 73
);

$val = 0;

$firstSaven = array();
$LastSaven = array();

for ($i = 0; $i < count($number); $i++) {

    $val += $number[$i];

    if ($i < 7) {
        array_push($firstSaven, $number[$i]);
        array_push($LastSaven, $number[count($number) - 1 - $i]);
    }
}

echo "The avg = " . $val / count($number) . "<br>";

print_r($firstSaven);
echo "<br>";

print_r($LastSaven);
echo "<br>";


$array1 = array("color" => "red", 2, 4);

$array2 = array(
    "a",
    "b",
    "color" => "green",
    "shape" => "trapezoid",
    4
);

$array1 = array_merge($array1, $array2);

print_r($array1);
echo "<br>";


$colors = array("red", "blue", "white", "yellow");

foreach ($colors as $color) {
    echo strtoupper($color) . "<br>";
}

?>