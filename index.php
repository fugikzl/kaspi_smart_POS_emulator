<?php
require_once(__DIR__."/vendor/autoload.php");

use App\Database\Database;
use App\Payment\AddInfo;
use App\Payment\Payment;
use App\Payment\ChequeInfo;
use Bramus\Router\Router;
use Faker\Factory;

$router = new Router();
$faker = Factory::create();
$db = new Database();


$router->get("/payment", function() use ($faker,$db){
    if(!array_key_exists("amount", $_GET)){
        echo json_encode([
            "message" => "Invalid amount. Should be positive integer number. amount: null"
        ]);
        exit(400);
    }

    if(!is_numeric($_GET["amount"]) || (intval($_GET["amount"] < 1)) )
    {
        echo json_encode([
            "message" => "Invalid amount. Should be positive integer number. amount: ".$_GET["amount"]
        ]);
        exit(400);
    }

    if(array_key_exists("method", $_GET) && $_GET["method"] !== "qr"){
        echo json_encode([
            "message" => "Бля пока только кюар"
        ]);
        exit(418);
    }

    $processId = rand(1,9);
    for($i = 0; $i < 12; $i++){
        $processId = $processId.$faker->randomDigit();
    }    

    $db->insert("payments",[
        "process_id" => $processId,
        "status" => "wait",
        "amount" => (int)$_GET["amount"]
    ]);

    echo(json_encode([
        "processId" => $processId,
        "status" => "wait"
    ]));

    exit(200);
});

$router->get("/status", function() use ($faker, $db){
    if(!array_key_exists("processId", $_GET) || !($_GET["processId"])){
        echo json_encode([
            "message" => "Empty processId"
        ]);
        exit(400);
    }

    $processId = $_GET["processId"];
    $data = $db->select("payments","process_id = $processId");

    if($data){
        $data = $data[0];
        if($data["status"] === "success"){
            $arr = [
                "processId" => $data["process_id"],
                "status" => $data["status"],
                "transactionId" => $data["transaction_id"],
                "addInfo" => [
                    "LoanOfferName" => $data["loan_offer_name"],
                    "LoanTerm" => $data["loan_term"],
                    "IsOffer" => $data["is_offer"],
                    "ProductType" => $data["product_type"]
                ],
                "chequeInfo" => [
                    "address" => $data["address"],
                    "amount" => $data["cheque_amount"],
                    "bin" => $data["bin"],
                    "city" => $data["city"],
                    "date" => $data["date"],
                    "method" => $data["method"],
                    "orderNumber" => $data["order_number"],
                    "status" => $data["cheque_status"],
                    "storeName" => $data["store_name"],
                    "terminalId" => $data["terminal_id"],
                    "type" => $data["type"]
                ]
            ];
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            exit(200);
        }else{
            echo json_encode([
                "processId" => "$processId",
                "status" => $data["status"]
            ]);

            exit(200);
        }
    }else{
        echo json_encode([
            "message" => "Process $processId not Found"
        ]);
        exit(500);
    }
});


$router->get("emulate/pay/{processId}", function($processId) use ($db){
    
    $data = $db->select("payments","process_id = $processId");

    if($data){
        $data = $data[0];
        
        if($data["status"] !== "wait"){
            echo json_encode([
                "message" => "Process should be with status wait"
            ]);
            exit(400);
        }

        $chequeInfo = new ChequeInfo($data["amount"]);
        // var_dump($chequeInfo);
        // die();
        $addInfo = new AddInfo();
        $payment = new Payment($data["process_id"], (int)$data["amount"], $addInfo, $chequeInfo);
        
        $arr = $payment->toArray();

        $db->update("payments",[
            "status" => "success", //статус оплаты
            "date" => $arr["chequeInfo"]["date"], //дата 
            "transaction_id" => $arr["transactionId"], //строковывй индекатор успешной транзакции
            "address" => $arr["chequeInfo"]["address"], //адрес магазина
            "cheque_amount" => $arr["chequeInfo"]["amount"], //сколько денег
            "bin" => $arr["chequeInfo"]["bin"], //БИН организации
            "city" => $arr["chequeInfo"]["city"], //город
            "type" => $arr["chequeInfo"]["type"], 
            "method" => $arr["chequeInfo"]["method"], //тип оплаты qr или card 
            "order_number" => $arr["chequeInfo"]["orderNumber"], //номер заказа qr
            "store_name" => $arr["chequeInfo"]["storeName"], //название магазина
            "terminal_id" => $arr["chequeInfo"]["terminalId"], //id термианала
            "transaction_type" => $arr["chequeInfo"]["type"], //
            "loan_offer_name" => $arr["addInfo"]["LoanOfferName"], // название акции
            "loan_term" => $arr["addInfo"]["LoanTerm"], // срок крелита или акции
            "is_offer" => (int)$arr["addInfo"]["IsOffer"], //транзакция по акции или нет
            "product_type" => $arr["addInfo"]["ProductType"], //метод оплаты(Кюар, голд, ред)
            "cheque_status" => $arr["chequeInfo"]["status"], //текстовый статус транзакции
        ],
        "process_id = $processId");

        echo json_encode([
            "message" => "Ok"
        ]);
        exit(200);
    }else{
        echo json_encode([
            "message" => "Process $processId not Found"
        ]);
        exit(404);
    }
});


$router->get("emulate/decline/{processId}",function($processId) use ($db){
    $data = $db->select("payments","process_id = $processId");

    if($data){
        $data = $data[0];
        if($data["status"] !== "wait"){
            echo json_encode([
                "message" => "Process should be with status wait"
            ]);
            exit(400);
        }else{
            
            $db->update("payments",[
                "status" => ""
            ],"processId = $processId");

            exit(200);
        }
    }else{
        echo json_encode([
            "message" => "Process $processId not Found"
        ]);
        exit(404);
    }

});

$router->run();