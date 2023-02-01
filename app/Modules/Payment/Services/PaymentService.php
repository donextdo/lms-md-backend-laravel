<?php

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Repositories\Interfaces\PaymentRepositoryInterface as PaymentRepository;
use App\Http\Resources\Grade;
use App\Http\Resources\GradeCollection;
use App\Support\Enums\Response;

class PaymentService
{
    public function __construct(protected PaymentRepository $paymentRepository)
    {
    }

    public function new($data):payment
    {
    try {
          $payment= $this->paymentRepository->create($data);
          return new Payment($payment);
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function edit($data)
    {
        try {
            $id = $data['updateid'];
            $payment= $this->paymentRepository->update($id, $data);
            return new Payment($payment);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function delete($data)
    {
        try {
            return $this->paymentRepository->delete($data['id']);
        } catch(ModelNotFoundException $e) {
            abort(404, Response::not_found());
        } catch (Exception $e) {
            abort(500, Response::server_error());
        }
    }

    public function view()
    {
        return $this->paymentRepository->all();
    }

    public function localPayments(array $data)
    {
        return $this->paymentRepository->localPayments($data);
    }
}
