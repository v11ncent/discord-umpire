<?php

include __DIR__ . '/vendor/autoload.php';

use Umpire\Umpire;

$umpire = new Umpire();
$token = $umpire->getToken();
echo $token;
