<?php

include __DIR__ . '/../vendor/autoload.php';

use Bench\Bench;

$bench = new Bench();
$bench->init();
$bench->benchProc();