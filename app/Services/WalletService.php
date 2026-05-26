<?php

namespace App\Services;

use App\Repositories\Interfaces\WalletRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Enums\TransactionType;
use App\Exceptions\InsufficientFundsException;
use Illuminate\Support\Facades\DB;

class WalletService
{
    protected WalletRepositoryInterface $walletRepository;
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        WalletRepositoryInterface $walletRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function depositFiat(int $userId, float $amount, string $description = 'Deposit'): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            $wallet = $this->walletRepository->getWalletByUserIdForUpdate($userId);
            
            $wallet->balance += $amount;
            $wallet->save();

            $walletDeposit = \App\Models\WalletDeposit::firstOrNew(['user_id' => $userId]);
            $walletDeposit->amount = ($walletDeposit->amount ?? 0) + $amount;
            $walletDeposit->save();

            $this->transactionRepository->create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::DEPOSIT->value,
                'amount' => $amount,
                'currency' => 'USD',
                'description' => $description,
            ]);
        });
    }

    public function withdrawFiat(int $userId, float $amount, string $description = 'Withdrawal'): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            $wallet = $this->walletRepository->getWalletByUserIdForUpdate($userId);
            
            $walletDeposit = \App\Models\WalletDeposit::firstOrNew(['user_id' => $userId]);
            $fiatBalance = $walletDeposit->amount ?? 0;
            
            if ($fiatBalance < $amount) {
                throw new InsufficientFundsException("Insufficient fiat balance.");
            }

            $wallet->balance -= $amount;
            $wallet->save();

            $walletDeposit->amount -= $amount;
            $walletDeposit->save();

            $this->transactionRepository->create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::WITHDRAWAL->value,
                'amount' => $amount,
                'currency' => 'USD',
                'description' => $description,
            ]);
        });
    }
    
    public function addMetal(int $userId, string $metal, float $amount): void
    {
        DB::transaction(function () use ($userId, $metal, $amount) {
            $wallet = $this->walletRepository->getWalletByUserIdForUpdate($userId);
            $column = strtolower($metal) . '_balance';
            $wallet->$column += $amount;
            $wallet->save();
        });
    }

    public function deductMetal(int $userId, string $metal, float $amount): void
    {
        DB::transaction(function () use ($userId, $metal, $amount) {
            $wallet = $this->walletRepository->getWalletByUserIdForUpdate($userId);
            $column = strtolower($metal) . '_balance';
            
            if ($wallet->$column < $amount) {
                throw new InsufficientFundsException("Insufficient {$metal} balance.");
            }

            $wallet->$column -= $amount;
            $wallet->save();
        });
    }

    public function addGold(int $userId, float $goldAmount): void
    {
        $this->addMetal($userId, 'gold', $goldAmount);
    }
    
    public function deductGold(int $userId, float $goldAmount): void
    {
        $this->deductMetal($userId, 'gold', $goldAmount);
    }
}
