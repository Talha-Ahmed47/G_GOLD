<?php

namespace App\Repositories\Eloquent;

use App\Models\Wallet;
use App\Repositories\Interfaces\WalletRepositoryInterface;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
    }

    public function getWalletByUserIdForUpdate(int $userId): Wallet
    {
        return $this->model->where('user_id', $userId)->lockForUpdate()->firstOrFail();
    }
}
