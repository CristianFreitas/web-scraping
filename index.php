<?php

require "vendor/autoload.php";

use GuzzleHttp\Client;

    $client = new Client([
        'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36'
        ]
    ]);
    $sanitized = array();
    $url = "http://www.guiatrabalhista.com.br/guia/salario_minimo.htm";
    $html = $client->request("GET", $url)->getBody()->getContents();

    $htmlDocDom = new DOMDocument();
    @$htmlDocDom->loadHTML($html);
    $htmlDocDom->preserveWhiteSpace = false;
    $tableCounter = 0;
    $htmlDocTableArray = array();
    $htmlDocTables = $htmlDocDom->getElementsByTagName('table');
    foreach ($htmlDocTables as $htmlDocTable) {
        $htmlDocTableArray[$tableCounter] = array();
        $htmlDocRows= $htmlDocTable->getElementsByTagName('tr');
        $htmlDocRowCount = 0;
        $htmlDocTableArray[$tableCounter] = array();
        foreach ($htmlDocRows as $htmlDocRow) {
            if (strlen($htmlDocRow->nodeValue) > 1)
            {
                $htmlDocColCount = 0;
                $htmlDocTableArray[$tableCounter][$htmlDocRowCount] = array();
                $htmlDocCols = $htmlDocRow->getElementsByTagName('td');
                foreach ($htmlDocCols as $htmlDocCol) {
                    $htmlDocTableArray[$tableCounter][$htmlDocRowCount][] = trim($htmlDocCol->nodeValue);
                    $htmlDocColCount++;
                }
                $htmlDocRowCount++;
            }
        }
        if ($htmlDocRowCount > 1) $tableCounter++;
    }
    $quantity = 0;
    if(!isset($htmlDocTableArray[0])) return; 
    foreach ($htmlDocTableArray[0] as $doc) {
        $quantity++;
        if($quantity == 1) {
            continue;
        }
        $sanitized[$quantity]["VIGÊNCIA"] = $doc[0];
        $sanitized[$quantity]["VALOR MENSAL"] = $doc[1];
    }
    die(var_dump($sanitized));
?>