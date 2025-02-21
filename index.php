<?php

require './vendor/autoload.php';
require './credentials.php';
require './CurrencyApi.php';

use GuzzleHttp\Client;

$api = "https://api.telegram.org/bot$converter_bot_token/";
$client = new Client(['base_uri' => $api]);

$request = json_decode(file_get_contents("php://input"));

$text = $request->message->text ?? '';
$chatId = $request->message->chat->id ?? '';
$firstName = $request->message->from->first_name ?? '';

$currency = new CurrencyApi();


$currencies = [
    '🇺🇸 USD > 🇺🇿 UZS' => 'USD',
    '🇬🇧 GBP > 🇺🇿 UZS' => 'GBP',
    '🇷🇺 RUB > 🇺🇿 UZS' => 'RUB'
];


if ($text == '/start') {
    $text = "Assalomu alaykum, $firstName!\n\nBu bot quyidagi amallarni bajara oladi:\n/usd2uzs - Dollardan So'mga o'giradi";
}


if (isset($currencies[$text])) {
    $text = $currency->getRate($currencies[$text]) . " so'm";
}


function getKeyboard() {
    return json_encode([
        'keyboard' => [
            [['text' => '🇺🇸 USD > 🇺🇿 UZS'], ['text' => '🇬🇧 GBP > 🇺🇿 UZS'], ['text' => '🇷🇺 RUB > 🇺🇿 UZS']]
        ],
        'resize_keyboard' => true
    ]);
}

$client->post('sendMessage', [
    'form_params' => [
        'chat_id' => $chatId,
        'text' => $text,
        'reply_markup' => getKeyboard()
    ]
]);
