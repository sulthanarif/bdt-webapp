<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handleInvoice(Request $request)
    {
        $xenditToken = config('services.xendit.webhook_token');
        $headers = $request->headers->all();

        // Verify webhook token
        if (isset($headers['x-callback-token'][0]) && $headers['x-callback-token'][0] !== $xenditToken) {
            Log::warning('Xendit Webhook Invalid Token', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Invalid token'], 403);
        }

        $payload = $request->all();
        $invoiceId = $payload['external_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$invoiceId || !$status) {
            return response()->json(['message' => 'Missing required data'], 400);
        }

        $transaction = Transaction::where('invoice_id', $invoiceId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update transaction status
        if ($status === 'PAID' || $status === 'SETTLED') {
            $transaction->status = 'paid';
            $transaction->paid_at = $payload['paid_at'] ?? now();
            if ($transaction->member) {
                $transaction->member->update(['status' => 'active']);
            }
        } elseif ($status === 'EXPIRED') {
            $transaction->status = 'expired';
        }

        // Update gateway payload
        $gatewayPayload = $transaction->gateway_payload ?? [];
        $gatewayPayload['webhook_updates'][] = [
            'timestamp' => now()->toIso8601String(),
            'payload' => $payload,
        ];
        $transaction->gateway_payload = $gatewayPayload;
        
        $transaction->save();

        return response()->json(['message' => 'Webhook received']);
    }
}
