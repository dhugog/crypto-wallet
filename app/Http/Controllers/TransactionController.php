<?php

namespace App\Http\Controllers;

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

        $this->transactionService->create([
            'user_id'           => $this->request->user()->id,
            'credited_currency' => 'BRL',
            'credited_amount'   => $this->request->amount
        ]);

        return response()->json([
            'message' => 'Deposited successfully!',
            'balance' => $this->transactionService->getUserBalance($this->request->user(), 'BRL')
        ]);
    }

    public function balance($currency): JsonResponse
    {
        return response()->json([
            'balance' => $this->transactionService->getUserBalance($this->request->user(), $currency)
        ]);
    }
}
