<?php
    $str = "Twinkle, twinkle, little star.";
    $arr = explode(",", $str);
    var_dump($arr);

    
    $letter = 'v';

    if ($letter == 'z') {
        echo 'a';
    } else {
        echo chr(ord($letter) + 1);
    }


    $str = "The brown fox";
    $result = str_replace("The ", "The quick ", $str);
    echo $result;

    $str = "The quick brown fox";
    $words = explode(" ", $str);
    echo $words[0];


    $str = "0000657022.24";
    $result = ltrim($str, "0");
    echo $result;

    $str = "The quick brown fox jumps over the lazy dog---";
    $result = rtrim($str, "-");
    echo $result;

    $str = "The quick brown fox jumps over the lazy dog";
    $words = explode(" ", $str);
    $result = array_slice($words, 0, 5);
    echo implode(" ", $result);

    $str = "2,543.12";
    $result = str_replace(",", "", $str);
    echo $result;
?>
