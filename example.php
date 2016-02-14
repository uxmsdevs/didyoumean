<?php
include 'src/MatchWord.php';

use Uxms\DidYouMean\MatchWord;

$dym = new MatchWord('en', 'Banana');


$dym = new MatchWord;
$dym->setLanguage('en')->setWord('Banana');


$dym = new MatchWord;
$dym->setLanguage('en');
$dym->setWord('Bananu');


echo $dym->checkMatch();
