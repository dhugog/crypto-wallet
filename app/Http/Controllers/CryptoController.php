<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Notifications\TransactionConfirmation;
use App\Services\CryptoService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CryptoController extends BaseController
{
    private Request $request;
    private TransactionService $transactionService;
    private CryptoService $cryptoService;

    public function __construct(Request $request, TransactionService $transactionService, CryptoService $cryptoService)
    {
        $this->request = $request;
        $this->transactionService = $transactionService;
        $this->cryptoService = $cryptoService;
    }

    public function getPrice(string $currency): JsonResponse
    {
        if (!Currency::find($currency))
            throw new NotFoundHttpException("Currency $currency not found.");

        $price = $this->cryptoService->getPrice();

        return response()->json($price);
    }

    public function getPosition(string $currency): JsonResponse
    {
        if (!Currency::find($currency))
            throw new NotFoundHttpException("Currency $currency not found.");

        $position = $this->cryptoService->getPosition($currency);

        return response()->json($position);
    }

    public function transact(string $currency, string $action): JsonResponse
    {
        if (!in_array($action, ['purchase', 'sell']))
            throw new NotFoundHttpException();

        if (!Currency::find($currency))
            throw new NotFoundHttpException("Currency $currency not found.");

        $this->validate($this->request, [
            'amount' => 'required|numeric|min:1'
        ]);

        $transaction = $this->cryptoService->{$action}($currency, $this->request->amount);

        $cryptoCurrency = Currency::find($currency);

        $amount = number_format($transaction->{$action === 'purchase' ? 'debited_amount' : 'credited_amount'} / 100, 2, ',', '.');
        $cryptoAmount = number_format($transaction->{$action === 'purchase' ? 'credited_amount' : 'debited_amount'} / $cryptoCurrency->int_unit_multiplier, 8, ',', '.');

        $actionText = $action === 'purchase' ? "Compra" : "Venda";
        $message = "$actionText efetuada com sucesso!";

        $this->request->user()->notify(new TransactionConfirmation($transaction, $message, "Sua " . strtolower($actionText) . " de **{$cryptoAmount} {$currency}** no valor de **R$ {$amount}** foi realizada com sucesso!"));

        return response()->json([
            'message' => $message,
            'balance' => $this->transactionService->getBalance($currency)
        ]);
    }

    public function getTransactedVolume(string $currency): JsonResponse
    {
        if (!Currency::find($currency))
            throw new NotFoundHttpException("Currency $currency not found.");

        $volume = $this->cryptoService->getTransactedVolume($currency);

        return response()->json($volume);
    }

    public function getPriceHistory(string $currency): JsonResponse
    {
        if (!Currency::find($currency))
            throw new NotFoundHttpException("Currency $currency not found.");

        $history = $this->cryptoService->getPriceHistory($currency);

        return response()->json($history);
    }
}
