<?php
function SumNum($num1, $num2) {

    if ($num1 + $num2 === 30) {
        return 30;
    } else {
        return false;
    }

}

$num1 = 33;
if ($num1 % 3 === 0) {
    echo true;
} else {
    echo false;
}
echo "<br>";

if ($num1 >= 20 && $num1 <= 50) {
    echo true;
} else {
    echo false;
}
echo "<br>";


$num1 = 1;
$num2 = 5;
$num3 = 9;

if ($num1 >= $num2 && $num1 >= $num3) {

    echo $num1;

} elseif ($num2 >= $num1 && $num2 >= $num3) {

    echo $num2;

} else {

    echo $num3;

}

echo "<br>";

$units = 200;
$bill = 0;

if ($units <= 50) {

    $bill = $units * 2.50;

} elseif ($units <= 150) {

    $bill = (50 * 2.50) + (($units - 50) * 5.00);

} elseif ($units <= 250) {

    $bill = (50 * 2.50) + (100 * 5.00) + (($units - 150) * 6.20);

} else {

    $bill = (50 * 2.50) + (100 * 5.00) + (100 * 6.20) + (($units - 250) * 7.50);

}

echo "Electricity bill = " . $bill . " JOD";
echo "<br>";


$num1 = 10;
$num2 = 5;
$operator = "*";

if ($operator == "+") {

    echo $num1 + $num2;

} elseif ($operator == "-") {

    echo $num1 - $num2;

} elseif ($operator == "*") {

    echo $num1 * $num2;

} elseif ($operator == "/") {

    if ($num2 != 0) {
        echo $num1 / $num2;
    } else {
        echo "Cannot divide by zero";
    }

} else {

    echo "Invalid operator";

}

$age = 15;
if ($age < 18) {
    echo "is no eligible to vote";
} else {
    echo "is eligible to vote";
}

echo "<br>";

$num = 15;
if ($num > 0) {
    echo "p";
} elseif ($num == 0) {
    echo "zero";
} else {
    echo "n";
}

echo "<br>";

$scores = array(60, 86, 95, 63, 55, 74, 79, 62, 50);

$sum = 0;
foreach ($scores as $score) {
    $sum += $score;
}

$average = $sum / count($scores);

echo "Average = " . $average . "<br>";

if ($average < 60) {
    echo "Grade = F";
} elseif ($average < 70) {
    echo "Grade = D";
} elseif ($average < 80) {
    echo "Grade = C";
} elseif ($average < 90) {
    echo "Grade = B";
} elseif ($average < 100) {
    echo "Grade = A";
}
?>
