<?php

$string = "hello world from orange coding academy";

echo strtoupper($string) . "<br>";
echo strtolower($string) . "<br>";
echo ucfirst($string) . "<br>";
echo ucwords($string) . "<br>";

$string = "085119";
echo substr($string, 0, 2) . ":";
echo substr($string, 2, 2) . ":";
echo substr($string, 4, 2);

$sentence = "I am a full stack developer at orange coding academy";
$word = "Orange";
if (stripos($sentence, $word) !== false) {
    echo "Word Found!";
} else {
    echo "Word Not Found!";
}

$url = "www.orange.com/index.php";
echo basename($url);


$email = "info@orange.com";
echo strstr($email, "@", true);


$email = "info@orange.com";
$array = explode("@", $email);
echo $array[0];


$string = "info@orange.com";
echo substr($string, -3);

$string = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

$random = substr(str_shuffle($string), 0, 7);
echo $random;


$sentence = "Our new trainee is so genius.";
$sentence = preg_replace("/^Our/", "the", $sentence);
echo $sentence;


$sentence = "Our new trainee is so genius.";
$sentence = str_replace("Our", "the", $sentence);
echo $sentence;


?>