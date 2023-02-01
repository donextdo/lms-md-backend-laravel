<?php

namespace App\Modules\Payment\Repositories;

use App\Models\Payment;
use App\Modules\Payment\Repositories\Interfaces\PaymentRepositoryInterface;
use Exception;
use Illuminate\Support\Str;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(protected Payment $payment)
    {
    }

    public function create(array $data): Payment
    {
        try {
            return $this->store($data);
        } catch (Exception $e) {
            return abort(500);
        }
    }

    public function all()
    {
        return $this->payment::all();
    }

    public function get(int $id): Payment
    {
        return $this->payment;
    }

    public function update(int $id, array $data): Payment
    {
        $this->payment::where('id', $id)->update([
             'name' => $data['name'],
             'slug' => Str::slug($data['name']),
         ]);

        return $this->subject;
    }

    public function delete(int $id)
    {
        $this->payment::where('id', $id)->delete();
    }

    protected function store(array $data): Payment
    {
        return $this->payment::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    protected function localPayments(array $data): Payment
    {
        return $this->payment::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }
}
