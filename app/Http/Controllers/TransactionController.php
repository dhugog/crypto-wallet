<?php

namespace App\Http\Controllers;

use App\Notifications\DepositConfirmation;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TransactionController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var TransactionService
     */
    private $transactionService;

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
            'user_id'           => $this->request->user()->id,
            'credited_currency' => 'BRL',
            'credited_amount'   => $this->request->amount
        ]);

        $this->request->user()->notify(new DepositConfirmation($transaction));

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
}
