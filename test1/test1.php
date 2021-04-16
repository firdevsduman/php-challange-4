<?php
require_once __DIR__ . '/vendor/autoload.php';
$currentParentNode = "   ";
$inboxPath = __DIR__ . '/data/tmp';
$outboxPath = __DIR__ . '/data/out';
$filexml = 'input.xml';
$filePathList = glob("$inboxPath/*.xml");
$fileCount = count($filePathList);
if (file_exists($filePathList[0])) {
    $outputPath = __DIR__ . "/data/out/output.csv";
    if (file_exists($outputPath)) {
        unlink($outputPath);
    }
    $xml = simplexml_load_file($filePathList[0]);
    $GLOBALS['$lineNamesInserted']=False;
    createCsv($xml);
}














