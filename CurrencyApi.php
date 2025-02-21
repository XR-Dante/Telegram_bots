<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class CurrencyApi {

  public $client;
  public $currentDate;
 
 function __construct() {
    $this->client       = new Client(['base_uri' => 'https://cbu.uz/uz/arkhiv-kursov-valyut/json/']);
    $this->currentDate  = date('Y-m-d');
  }

  public function getRate(string $currency) {
    $response = $this->client->get("$currency/$this->currentDate");

    $result = $response->getBody()->getContents();
    $result = json_decode($result);

    return $result[0]->Rate;
  }
}