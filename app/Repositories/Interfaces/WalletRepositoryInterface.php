<?php

namespace App\Repositories\Interfaces;

use App\Models\Wallet;

interface WalletRepositoryInterface extends BaseRepositoryInterface
{
    public function getWalletByUserIdForUpdate(int $userId): Wallet;
}
