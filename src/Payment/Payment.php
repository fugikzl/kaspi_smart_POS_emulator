<?php

namespace App\Payment;

class Payment
{
    private string $transactionId;
    private string $status = "success";
    private string $processId;
    private int $amount;

    private AddInfo $addInfo;
    private ChequeInfo $chequeInfo;

    public function __construct(string $processId, int $amount, AddInfo $addInfo, ChequeInfo $chequeInfo)
    {
        $transactionId = rand(1,9);

        for($i = 0; $i < 10; $i++){
            $transactionId = $transactionId.rand(0,9);
        }

        $this->transactionId = $transactionId;
        $this->processId = $processId;
        $this->amount = $amount;

        $this->addInfo = $addInfo;
        $this->chequeInfo = $chequeInfo;
    }

    public function toArray()
    {
        return [
            "transactionId" => $this->transactionId,
            "status" => $this->status,
            "processId" => $this->processId,
            "addInfo" => $this->addInfo->toArray(),
            "chequeInfo" => $this->chequeInfo->toArray()
        ];
    }

}

// {
//     "addInfo": {
//       "LoanOfferName": "",
//       "LoanTerm": 0,
//       "IsOffer": false,
//       "ProductType": "Gold"
//     },
//     "chequeInfo": {
//       "address": "Северное Кольцо, 60",
//       "amount": "1 ₸",
//       "bin": "100640009693",
//       "city": "г. Алматы",
//       "date": "04.06.23 20:52:31",
//       "method": "qr",
//       "orderNumber": "3699352521",
//       "status": "Покупка с Kaspi Gold. Одобрено",
//       "storeName": "АЗС Compass",
//       "terminalId": "31104288",
//       "type": "payment"
//     },
//     "processId": "1685890325386",
//     "status": "success",
//     "transactionId": "3699352521"
//   }