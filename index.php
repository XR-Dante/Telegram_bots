<?php
require "../credentials.php";

$website = "https://api.telegram.org/bot$converter_bot_token/";

$content = file_get_contents("php://input");
$update = json_decode($content);

if (isset($update->message)) {
    $message = $update->message;
    $text = $message->text;
    $chatID = $message->chat->id;
    
    $first_name = $message->from->first_name;
    $last_name = isset($message->from->last_name) ? $message->from->last_name : "";
    $fullname = trim($first_name . " " . $last_name);

    if ($text == "/start") {
        $reply_name = "Salom, $fullname! Botimizga xush kelibsiz! 👋:";
        
        $lineKeyboard = [
            [
                ["text" => "🇺🇸 USD -> 🇺🇿 UZS", "callback_data" => "convert_usd_uzs"],
                ["text" => "🇪🇺 EUR -> 🇺🇿 UZS", "callback_data" => "convert_eur_uzs"],
                ["text" => "🇷🇺 RUBL -> 🇺🇿 UZS", "callback_data" => "convert_rub_uzs"]
            ]
        ];

        $replyMarkup = json_encode([
            "inline_keyboard" => $lineKeyboard
        ]);
        
        file_get_contents($website . "sendMessage?chat_id=$chatID&text=$reply_name&reply_markup=" . urlencode($replyMarkup));
    }
}

if (isset($update->callback_query)) {
    $callback_query = $update->callback_query;
    $callback_data = $callback_query->data;
    $chatID = $callback_query->message->chat->id;

    // Tugmani tekshirish va shart bajarish
    if ($callback_data == "convert_usd_uzs") {
        $reply = "1 USD = 12,000 UZS 💰";
    } elseif ($callback_data == "convert_eur_uzs") {
        $reply = "1 EUR = 13,000 UZS 💶";
    } elseif ($callback_data == "convert_rub_uzs") {    
        $reply = "1 RUB = 150 UZS 🇷🇺";
    } else {
        $reply = "Noto‘g‘ri tanlov!";
    }

    
    file_get_contents($website . "sendMessage?chat_id=$chatID&text=" . urlencode($reply));
}
