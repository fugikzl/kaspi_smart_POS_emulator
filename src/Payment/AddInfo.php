<?php

namespace App\Payment;

class AddInfo
{
    private bool $isOffer;
    private string $productType;
    private int $loanTerm;
    private string $loanOfferName;

    public function __construct(bool $isOffer = null, string $productType = null, int $loanTerm = null, string $loanOfferName = null)
    {
        $this->isOffer = $isOffer ?? $this->generateIsOffer();
        $this->productType = $productType ?? $this->generateProductType();
        $this->loanTerm = $loanTerm ?? $this->generateLoanTerm();
        $this->loanOfferName = $loanOfferName ?? $this->generateLoanOfferName();
    }

    public function toArray()
    {
        return [
            "LoanOfferName" => $this->loanOfferName,
            "LoanTerm" => $this->loanTerm,
            "IsOffer" => $this->isOffer,
            "ProductType" => $this->productType
        ];
    }

    private function generateIsOffer(bool $isOffer = false) : bool
    {
        return $isOffer;
    }

    private function generateLoanTerm() : int
    {
        $loanTerm = $this->isOffer ? rand(1,6) * 3 : 0;
        return $loanTerm;
    }

    private function generateLoanOfferName() : string
    {
        $loanOfferName = $this->isOffer ? "АКЦИЯ ".date("m.d")."!" : "";
        return $loanOfferName;
    }

    private function generateProductType() : string
    {
        // at current time only gold
        return "GOLD";
    }
}

