<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPaymentMethods()
    {
        $paymentMethods = PaymentMethod::where('is_active', 1)->get();
        if ($paymentMethods->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'No payment methods found'
            ], 200);
        }
        return response()->json([
            'error' => false,
            'message' => 'Payment methods fetched successfully',
            'records' => $paymentMethods,

        ], 200);
    }
}
