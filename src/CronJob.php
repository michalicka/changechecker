<?php

    declare(strict_types=1);

	require __DIR__ . '/Config.php'; 
    require __DIR__ . '/Checker.php'; 
    require __DIR__ . '/Grabber.php'; 
    require __DIR__ . '/Scraper.php'; 
    require __DIR__ . '/Notifier.php'; 
    require __DIR__ . '/../vendor/autoload.php'; 


    (new Checker(new Config($argv ?? [])))->process();
