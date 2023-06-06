<?php
use App\Database\Database;
use App\Database\Migration;

$migrations = [];

$db = new Database();
$db = $db->connect();


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
$migrations[] = new Migration("payments", $db, [
    "process_id" => "TEXT|PRIMARY KEY", //идентификатор текущего процесса интеграции 
    "status" => "TEXT|NOT NULL", //статус оплаты
    "date" => "TEXT|DEFAULT NULL", //дата 
    "transaction_id" => "TEXT|UNIQUE|DEFAULT NULL", //строковывй индекатор успешной транзакции
    "address" => "TEXT|DEFAULT NULL", //адрес магазина
    "amount" => "TEXT|DEFAULT NULL", //сколько денег
    "bin" => "TEXT|DEFAULT NULL", //БИН организации
    "city" => "TEXT|DEFAULT NULL", //город
    "method" => "TEXT|DEFAULT NULL", //тип оплаты qr или card 
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
    "cheque_status" => "TEXT|DEFAULT NULL",
    "cheque_amount" => "TEXT|DEFAULT NULL",
    "type" => "TEXT|DEFAULT NULL"
]);

try {
    foreach($migrations as $migration){
        $migration->migrate();
    }
} catch(PDOException $e) {
    die("ERROR: Could not able to execute " . $e->getMessage());
}
?>
