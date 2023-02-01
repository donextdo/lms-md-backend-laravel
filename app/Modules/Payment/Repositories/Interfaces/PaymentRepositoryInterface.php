<?php

namespace App\Modules\Payment\Repositories\Interfaces;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function all();

    public function create(array $data): Payment;

    public function get(int $id): Payment;

    public function update(int $id, array $data): Payment;

    public function delete(int $id);
}
