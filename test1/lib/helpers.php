<?php
$GLOBALS['$lineItemCounter'] = 0;
$GLOBALS['$linesLength'] = 0;
function createCsv($xml)
{
    global $outputPath;
    foreach ($xml->children() as $item) {
        #burda gelen verinin alt değeri var mı sorguladım.
        $hasChild = count($item->children()) > 0;

        #alt değeri olmayan veriler header ve lin olduğu için ikisini kontrol ettim.
        #header ve line altındaki değerleri yazdırdım.
        if (!$hasChild) {
            if ($GLOBALS['$currentParentNode'] == "header") {
                $GLOBALS['$headerElementNames'] = $GLOBALS['$headerElementNames'] . ";" . $item->getName();
                $GLOBALS['$headerElementValues'] = $GLOBALS['$headerElementValues'] . ";" . $item;
                continue;
            }
            $GLOBALS['$lineElementNames'] = $GLOBALS['$lineElementNames'] . ";" . $item->getName();
            $GLOBALS['$lineElementValues'] = $GLOBALS['$lineElementValues'] . ";" . $item;
            continue;
        }
        $GLOBALS['$currentParentNode'] = $item->getName();
        $GLOBALS['$headerElementNames'] = "";
        $GLOBALS['$headerElementValues'] = "";
        $GLOBALS['$lineElementNames'] = "";
        $GLOBALS['$lineElementValues'] = "";
        #burda dizi oluşturarak değerleri diziye attım.
        createCsv($item);
        $nodeNames = explode(";", substr($GLOBALS['$headerElementNames'], 1));
        $nodeValues = explode(";", substr($GLOBALS['$headerElementValues'], 1));
        $lineNames = explode(";", substr($GLOBALS['$lineElementNames'], 1));
        $lineValues = explode(";", substr($GLOBALS['$lineElementValues'], 1));
        #gelen dizi değerlerine göre yazdırdım.
        switch (true) {
            case count($nodeNames) > 1 :
                file_put_contents($outputPath, implode(";", $nodeNames) . PHP_EOL, FILE_APPEND);
            case count($nodeValues) > 1 :
                $data = $nodeValues;
                break;
            case count($lineNames) > 1 and $GLOBALS['$lineNamesInserted'] == false :
                file_put_contents($outputPath, implode(";", $lineNames) . PHP_EOL, FILE_APPEND);
                $GLOBALS['$lineNamesInserted'] = true;
            case count($lineValues) > 1 :
                $data = $lineValues;
                $GLOBALS['$lineItemCounter']++;
                break;
            default:
                continue 2;
        }
        #en sonunda satır sayılarını karşılaştırarak 1 satırın birden fazla kez yazılmasını engelledim.
        if (is_null($xml->lines->line) == false) {
            $GLOBALS['$linesLength'] = count($xml->lines->line) . PHP_EOL;
        }
        if ($GLOBALS['$linesLength'] >= $GLOBALS['$lineItemCounter']) {
            file_put_contents($outputPath, implode(";", $data) . PHP_EOL, FILE_APPEND);
        }
    }
}
