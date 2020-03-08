<?php

include __DIR__ . '/../vendor/autoload.php';

use Bench\Bench;

$b = new Bench();
$b->init();
$b->benchProc();