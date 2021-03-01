<?php

namespace App\Console\Commands;

use App\Models\CryptoPrice;
use App\Services\CryptoService;
use Illuminate\Console\Command;

class StoreCryptoPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:store-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stores the price of the cryptocurrency in the price history table';

    private CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        parent::__construct();

        $this->cryptoService = $cryptoService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $price = $this->cryptoService->getPrice('BTC');

        CryptoPrice::create([
            'sell' => $price['sell'],
            'buy'  => $price['buy']
        ]);
    }
}
