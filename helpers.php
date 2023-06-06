<?php
require_once(__DIR__."/vendor/autoload.php");


function generateSuccessPayment($amount, $method)
{
    $faker = Faker\Factory::create();

    $transactionId = "";

    for($i = 0; $i < 10; $i++){
        $transactionId = $transactionId.$faker->randomDigit();
    }

    // Поле status может быть одним из значений «wait», «fail», «success».
    // При статусе «success» дополнительно приходят следующие поля:
    // «transactionId» - уникальное значение для каждой успешной покупки
    // «addInfo» - дополнительная информация о транзакции со
    // следующими полями:

    // «IsOffer» - true, если покупка по акции, иначе false

    // «ProductType» - один из значений «Gold» (оплата по карте Kaspi
    // Gold), «Red» (Kaspi Red), «Loan» (покупка в кредит или рассрочку),
    // «OtherCard» (оплата по карте другого банка)

    // «LoanTerm» - числовое значение срока кредита или акции, в
    // месяцах. Может быть одним из следующих значений: 3, 6, 9, 12, 18,
    // 24. В случае если это НЕ покупка в кредит или в рассрочку, то
    // значение может быть -1 или отсутствовать

    // «LoanOfferName» - название акции, приходит если покупка по акции
    // - «chequeInfo» - группа полей, информация по чеку
    return [
        "status" => "TEXT|NOT NULL", //статус оплаты
        "date" => "TEXT|DEFAULT NULL", //дата 
        "transaction_id" => $transactionId, //строковывй индекатор успешной транзакции
        "address" => "TEXT|DEFAULT NULL", //адрес магазина
        "amount" => $amount, //сколько денег
        "bin" => "TEXT|DEFAULT NULL", //БИН организации
        "city" => "TEXT|DEFAULT NULL", //город
        "method" => $method, //тип оплаты qr или card 
        "order_number" => "TEXT|DEFAULT NULL", //номер заказа qr
        "transaction_status" => "TEXT|DEFAULT NULL", //
        "store_name" => "TEXT|DEFAULT NULL", //название магазина
        "terminal_id" => "TEXT|DEFAULT NULL", //id термианала
        "transaction_type" => "TEXT|DEFAULT NULL", //
        "loan_offer_name" => "TEXT|DEFAULT NULL", // название акции
        "loan_term" => "INTEGER|DEFAULT NULL", // срок крелита или акции
        "is_offer" => "INTEGER|DEFAULT NULL|CHECK( is_offer IN (0, 1) )", //транзакция по акции или нет
        "product_type" => "TEXT|DEFAULT NULL", //метод оплаты(Кюар, голд, ред)
        "card_mask" => "TEXT|DEFAULT NULL", //маска карты
        "icc" => "TEXT|DEFAULT NULL", //тип карты (Мастеркард, Виса)
        "cheque_status" => "TEXT|DEFAULT NULL", //текстовый статус транзакции
        "pin_entered" => "INTEGER|DEFAULT NULL|CHECK( is_offer IN (0, 1) )", //ввелось ли пин-код
    ];

}