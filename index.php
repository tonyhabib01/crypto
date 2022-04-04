<?php

require 'vendor/autoload.php';

function dump($el){
    print "<pre>";
    print_r($el);
    print "</pre>";
}
function scrapConvert($currency_from, $currency_to, $amount)
{
    $url = "https://www.x-rates.com/calculator/?from={$currency_from}&to={$currency_to}&amount={$amount}";
    $httpClient = new \GuzzleHttp\Client();
    $response = $httpClient->get($url);
    $htmlString = (string) $response->getBody();
    //add this line to suppress any warnings
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($htmlString);
    $xpath = new DOMXPath($doc);
    $values = $xpath->evaluate("//span[@class='ccOutputRslt']");
    $result = (float) $values[0]->textContent;
    return $result;
}

$value_from = isset($_POST["currency_from"]) ? (int) $_POST["currency_from"] : 0;
$from_currency = $_POST["currency_from_select"] ?? "";
$to_currency = $_POST["currency_to_select"] ?? "";
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $value_to = scrapConvert($from_currency, $to_currency, $value_from);
}
else{
    $value_to = 0;
}
?>
<?php //scrap("TRY", "USD", 1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Time Currency Converter</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">CURRENCY CONVERTER</div>
        <form action="" method="POST">
            <div class="row">
                <input type="text" name="currency_from" placeholder="Enter amount" required="" value="<?= $value_from ?>">
                <select name="currency_from_select">
                    <option value="TRY" <?= $from_currency === "TRY" ? "selected" : ''?>>Turkish Lira</option>
                    <option value="USD" <?= $from_currency === "USD" ? "selected" : ''?> >United States Dollar</option>
                    <option value="EUR" <?= $from_currency === "EUR" ? "selected" : ''?>>Euro</option>
                </select>
            </div>
            <div class="row">
                <input type="text" name="currency_to" value="<?= $value_to ?>">
                <select name="currency_to_select">
                    <option value="TRY" <?= $to_currency === "TRY" ? "selected" : ''?>>Turkish Lira</option>
                    <option value="USD" <?= $to_currency === "USD" ? "selected" : ''?>>United States Dollar</option>
                    <option value="EUR" <?= $to_currency === "EUR" ? "selected" : ''?>>Euro</option>
                </select>
            </div>
            <div class="row">
                <button type="submit" name="convert_btn" value="convert">Convert</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

