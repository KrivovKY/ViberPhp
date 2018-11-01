<?php
require_once("../vendor/autoload.php");
use Viber\Client;
$apiKey = '48b38a2546e7d1d6-5ee50d9b8222c486-5a98983ba29825a8'; // <- PLACE-YOU-API-KEY-HERE
$webhookUrl = 'https://demo.suprotec.by//viber-bot//viber-bot.php'; // <- PLACE-YOU-HTTPS-URL
try {
    $client = new Client([ 'token' => $apiKey ]);
    $result = $client->setWebhook($webhookUrl);
    echo "Success!\n";
} catch (Exception $e) {
    echo "Error: ". $e->getMessage() ."\n";
}