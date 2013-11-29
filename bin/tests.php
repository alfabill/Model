<?php

use Testes\Coverage\Coverage;
use Testes\Finder\Finder;
use Lib\TestEvent;
use Testes\Autoloader;

$base = __DIR__ . '/..';

require $base . '/vendor/autoload.php';

Autoloader::register();
Autoloader::addPath($base . '/tests');
Autoloader::addPath($base . '/src');

$event    = new TestEvent();
$coverage = new Coverage;
$finder   = new Finder($base . '/tests', 'Test');

$coverage->start();

echo PHP_EOL;

$suite = $finder->run($event->get());

echo PHP_EOL . PHP_EOL . sprintf('Ran %d test%s.', count($suite), count($suite) === 1 ? '' : 's');

$analyzer = $coverage->stop()->addDirectory($base . '/src')->is('\.php$');

echo PHP_EOL . PHP_EOL . 'Coverage: ' . $analyzer->getPercentTested() . '%' . PHP_EOL . PHP_EOL;

if (count($assertions = $suite->getAssertions()->getFailed())) {
    echo 'Assertions' . PHP_EOL;
    echo '----------' . PHP_EOL;

    foreach ($assertions as $ass) {
        echo '  ' . $ass->getTestClass() . ':' . $ass->getTestLine() . ' ' . $ass->getMessage() . PHP_EOL;
    }

    echo PHP_EOL;
}

if (count($exceptions = $suite->getExceptions())) {
    echo 'Exceptions' . PHP_EOL;
    echo '----------' . PHP_EOL;

    foreach ($exceptions as $exc) {
        echo '  ' . $exc->getTestFile() . ':' . $exc->getTestLine() . ' ' . $exc->getMessage() . PHP_EOL;
    }

    echo PHP_EOL;
}

if (count($benchmarks = $suite->getBenchmarks())) {
    echo 'Benchmarks' . PHP_EOL;
    echo '----------' . PHP_EOL;

    foreach ($benchmarks as $name => $bench) {
        echo '  ' . $name . ': ' . round($bench->getTime(), 3) . PHP_EOL;
    }

    echo PHP_EOL;
}