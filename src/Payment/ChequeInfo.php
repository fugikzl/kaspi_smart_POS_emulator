<?php

namespace App\Payment;

use Faker\Generator;

class ChequeInfo
{
    private string $address;
    private string $amount;
    private string $bin;
    private string $city;
    private string $date;
    private string $method;
    private string $orderNumber;
    private string $status;
    private string $storeName;
    private string $terminalId;
    private string $type;

    public function __construct(
        int $amount,
        string $type = "payment",
        string $address = null,
        string $bin = null,
        string $city = null,
        string $date = null,
        string $method = null,
        string $orderNumber = null,
        string $status = null,
        string $storeName = null,
        string $terminalId = null,
    ){
        $this->type = $type;
        $this->amount = $this->generateAmount($amount);
        $this->address = $address ?? $this->generateAddress();
        $this->bin = $bin ?? $this->generateBin();
        $this->city = $city ?? $this->generateCity();
        $this->date = $date ?? $this->generateDate();
        $this->method = $method ?? $this->generateMethod();
        $this->orderNumber = $orderNumber ?? $this->generateOrderNumber();
        $this->status = $status ?? $this->generateStatus();
        $this->storeName = $storeName ?? $this->generateStoreName();
        $this->terminalId = $terminalId ?? $this->generateTerminalId();
    }

    public function toArray()
    {
        return [
            "address" => $this->address,
            "amount" => $this->amount,
            "bin" => $this->bin,
            "city" => $this->city,
            "date" => $this->date,
            "method" => $this->method,
            "orderNumber" => $this->orderNumber,
            "status" => $this->status,
            "storeName" => $this->storeName,
            "terminalId" => $this->terminalId ,
            "type" => $this->type
        ];
    }

    private function generateAddress() : string
    {
        $address = Configuration::get("address") ?? "Абая 45";
        return $address;
    }

    private function generateAmount(int $amount) : string
    {
        return "$amount ₸";
    }

    private function generateBin() : string
    {
        $bin = Configuration::get("bin") ?? "100845001293"; 
        
        return $bin;
    }

    private function generateCity() : string
    {
        $city = Configuration::get("city") ?? "г. Тараз";
        return $city;
    }

    private function generateDate() : string
    {
        return date("d.m.y h:i:s");
    }

    private function generateMethod() : string
    {
        //at current time only qr is available
        return "qr";
    }

    private function generateOrderNumber() : string
    {
        $orderNumber = rand(1,9);
        for($i = 0; $i < 10; $i++){
            $orderNumber = $orderNumber.rand(0,9);
        }
        return $orderNumber;    
    }

    private function generateStatus() : string
    {
        return "Покупка с Kaspi Gold. Одобрено";
    }

    private function generateStoreName() : string
    {
        $city = Configuration::get("storeName") ?? "Магазин Ralphs";
        return $city;
    }

    private function generateTerminalId() : string
    {
        $terminalId = Configuration::get("terminalId") ?? "25308281";
        return $terminalId;
    }
}
//blya pizdec zaebalsya