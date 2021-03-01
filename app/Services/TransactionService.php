<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;

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

    public function getStatement($from = null, $to = null)
    {
        return auth()->user()->transactions()
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->when(!$from && !$to, fn ($query) => $query->whereDate('created_at', '>=', Carbon::now()->subDays(90)->toDateString()))
            ->get();
    }
}
