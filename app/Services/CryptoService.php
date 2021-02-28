<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CryptoService
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getPrice($currency = 'BTC')
    {
        $response = Http::get('https://www.mercadobitcoin.net/api/BTC/ticker')->throw();

        return [
            'buy'  => (int) $response['ticker']['buy'] * 100 / 100000000, // Centavos / satoshi
            'sell' => (int) $response['ticker']['sell'] * 100 / 100000000
        ];
    }

    public function purchase(string $currency, int $amount): Transaction
    {
        $balance = $this->transactionService->getBalance('BRL');

        if ($amount > $balance)
            throw new BadRequestHttpException('Saldo insuficiente.');

        $sellPrice = $this->getPrice($currency)['sell'];

        return $this->transactionService->create([
            'debited_currency'  => 'BRL',
            'debited_amount'    => $amount,
            'credited_currency' => $currency,
            'credited_amount'   => $amount / $sellPrice,
        ]);
    }

    public function sell(string $currency, int $amount): Transaction
    {
        $buyPrice = $this->getPrice($currency)['buy'];

        $cryptoBalance = $this->transactionService->getBalance($currency);

        $balance = $buyPrice * $cryptoBalance;

        if ($amount > $balance)
            throw new BadRequestHttpException('Saldo insuficiente.');

        $cryptoAmount = $amount / $buyPrice;

        if ($cryptoBalance - $cryptoAmount <= 100)
            $cryptoAmount = $cryptoBalance;

        return $this->transactionService->create([
            'debited_currency'  => $currency,
            'debited_amount'    => $cryptoAmount,
            'credited_currency' => 'BRL',
            'credited_amount'   => $amount
        ]);
    }

    public function getPosition(string $currency)
    {
        $currentPrice = $this->getPrice($currency)['buy'];

        $user = auth()->user();

        $transactions = $user->transactions()->where('credited_currency', $currency)->get();

        $positions = [];

        $totalLiquidated = $user->transactions()->where('debited_currency', $currency)->sum('debited_amount');

        foreach ($transactions as $transaction) {
            $purchasePrice = $transaction->debited_amount / $transaction->credited_amount;

            if ($totalLiquidated >= $transaction->credited_amount) {
                $totalLiquidated -= $transaction->credited_amount;
                continue;
            }

            $currentQuantity = $transaction->credited_amount - $totalLiquidated;

            $totalLiquidated = 0;

            $positions[] = [
                'transaction_id'          => $transaction->id,
                'purchase_price'          => (float) number_format($purchasePrice, 3, '.', ''),
                'purchase_quantity'       => $transaction->credited_amount,
                'current_quantity'        => $currentQuantity,
                'amount_invested'         => $transaction->debited_amount,
                'current_amount_invested' => intval($currentPrice * $currentQuantity),
                'variation'               => (float) number_format($currentPrice / $purchasePrice, 3, '.', ''),
                'purchased_at'            => $transaction->created_at
            ];
        }

        return $positions;
    }
}