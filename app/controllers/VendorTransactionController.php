<?php

require_once __DIR__ . '/../models/VendorTransaction.php';

class VendorTransactionController
{
    private $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new VendorTransaction();
    }

    public function getVendorTransactions($vendorID)
    {
        return $this->transactionModel->getVendorTransactions($vendorID);
    }
}

?>
