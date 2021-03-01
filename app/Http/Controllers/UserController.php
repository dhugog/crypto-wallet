<?php

namespace App\Http\Controllers;

use App\Notifications\TransactionConfirmation;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    private Request $request;
    private TransactionService $transactionService;

    public function __construct(Request $request, TransactionService $transactionService)
    {
        $this->request = $request;
        $this->transactionService = $transactionService;
    }

    public function deposit(): JsonResponse
    {
        $this->validate($this->request, [
            'amount' => 'required|numeric|min:1'
        ]);

        $transaction = $this->transactionService->create([
            'credited_currency' => 'BRL',
            'credited_amount'   => $this->request->amount
        ]);

        $amount = number_format($transaction->credited_amount / 100, 2, ',', '.');

        $this->request->user()->notify(new TransactionConfirmation($transaction, "Depósito efetuado com sucesso!", "Seu depósito no valor de **R$ {$amount}** foi efetuado com sucesso!"));

        return response()->json([
            'message' => 'Depósito realizado com sucesso!',
            'balance' => $this->transactionService->getBalance('BRL')
        ]);
    }

    public function balance($currency): JsonResponse
    {
        return response()->json([
            'balance' => $this->transactionService->getBalance($currency)
        ]);
    }

    public function getStatement(): JsonResponse
    {
        $this->validate($this->request, [
            'from' => 'filled|date',
            'to'   => 'filled|date'
        ]);

        $transactions = $this->transactionService->getStatement($this->request->from, $this->request->to);

        return response()->json($transactions);
    }
}
