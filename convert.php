<?php declare(strict_types=1);

use League\Csv\Reader;
use League\Csv\Writer;

require_once("vendor/autoload.php");

$source = $argv[1];
$target = $argv[2] ?? "output.csv";

$f = fopen($target, "r+");
if ($f !== false) {
    ftruncate($f, 0);
    fclose($f);
}

$reader = Reader::createFromPath($source);
$writer = Writer::createFromPath($target);

$reader->setHeaderOffset(0);

$records = $reader->getRecords();

foreach ($records as $offset => $line) {
    $date = $line["Date"];
    $moneyIn = $line["Money In"];
    $moneyOut = $line[" Money Out"];

    $moneyDelta = $moneyIn - $moneyOut;

    $reference = $line["Reference"];
    $transactionType = $line["Transaction Type"];

    $writer->insertOne([$date, $moneyDelta, $reference ?: $transactionType]);
}
