<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService extends BaseService
{
    public function __construct(Transaction $transactionModel)
    {
        parent::__construct($transactionModel);
    }

    public function create(array $data): Transaction
    {
        $transaction = auth()->user()->transactions()->create($data);

        return $transaction;
    }

    public function getBalance(string $currency)
    {
        $user = auth()->user();
        
        return $user->transactions()->where('credited_currency', $currency)->sum('credited_amount') - $user->transactions()->where('debited_currency', $currency)->sum('debited_amount');
    }
}
