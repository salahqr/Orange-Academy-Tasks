<?php
function PrimeNumb($num) {
    if ($num < 2) {
        echo "$num is not a prime number<br>";
        return;
    }
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) {
            echo "$num is not a prime number<br>";
            return;
        }
    }
    echo "$num is a prime number<br>";
}
PrimeNumb(3);

function ReveseStr($str) {
    return strrev($str);
}
echo ReveseStr("remove") . "<br>";

// 3. Swap
function Swapnum(&$a, &$b) {
    [$a, $b] = [$b, $a];
}
$x = 12;
$y = 10;
Swapnum($x, $y);
echo "y=$y x=$x<br>";

// 4. Armstrong
function Armstrong($num) {
    $digits = str_split((string)$num);
    $cur = 0;
    foreach ($digits as $n) {
        $cur += $n ** 3;
    }
    if ($cur == $num) {
        echo "$num is Armstrong Number<br>";
    } else {
        echo "$num is not Armstrong Number<br>";
    }
}
Armstrong(407);

// 5. Palindrome
function palindrome($str) {
    $st = "";
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str[$i];
        if (ctype_alpha($char)) {
            $st .= strtolower($char);
        }
    }
    if ($st === strrev($st)) {
        echo "Yes it is a palindrome<br>";
    } else {
        echo "No it is not a palindrome<br>";
    }
}
palindrome("Eva, can I see bees in a cave?");

// 6. Remove duplicates
function removeDuplicates($arr) {
    return array_values(array_unique($arr));
}
$array1 = array(2, 4, 7, 4, 8, 4);
print_r(removeDuplicates($array1));