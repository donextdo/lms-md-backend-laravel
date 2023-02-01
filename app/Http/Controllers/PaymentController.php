<?php

namespace App\Http\Controllers;

use App\Modules\Payment\Services\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $payment)
    {
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $response = $this->payment->new($request->all());

            return $this->sendResponse($response, 'Payment created successfully.');
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function edit(Request $request): JsonResponse
    {
        try {
            $response = $this->payment->edit($request->all());

            return $this->sendResponse($response, 'Payment updated successfully.');
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $response = $this->payment->delete($request->all());

            return $this->sendResponse($response, 'Payment deleted successfully.');
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function view(): JsonResponse
    {
        try {
            $response = $this->payment->view();

            return $this->sendResponse($response, 'Payment shown successfully.');
        } catch (Exception $e) {
            dd($e);
        }
    }
}
