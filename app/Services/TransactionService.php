<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    /**
     * @var Transaction
     */
    private $transactionModel;

    public function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function create(array $data): Transaction
    {
        $transaction = $this->transactionModel->create($data);

        return $transaction;
    }

    public function getBalance(string $currency)
    {
        $user = auth()->user();

        return $user->transactions()->where('credited_currency', $currency)->sum('credited_amount') - $user->transactions()->where('debited_currency', $currency)->sum('debited_amount');
    }
}
