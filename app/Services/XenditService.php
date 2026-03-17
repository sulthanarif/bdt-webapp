<?php

namespace App\Services;

use Exception;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    /**
     * @var InvoiceApi
     */
    protected $apiInstance;

    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $this->apiInstance = new InvoiceApi();
    }

    /**
     * Create Invoice from Transaction using Xendit
     * 
     * @param \App\Models\Transaction $transaction
     * @param string $description
     * @param string $successRedirectUrl
     * @param string $failureRedirectUrl
     * @return array
     * @throws Exception
     */
    public function createInvoice($transaction, $description = 'Payment Invoice', $successRedirectUrl = null, $failureRedirectUrl = null)
    {
        try {
            $customer = [
                'given_names' => $transaction->customer_name,
                'email' => $transaction->customer_email,
                'mobile_number' => $transaction->customer_phone,
            ];

            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => $transaction->invoice_id,
                'amount' => $transaction->amount_total,
                'payer_email' => $transaction->customer_email,
                'description' => $description,
                'customer' => $customer,
                'success_redirect_url' => $successRedirectUrl,
                'failure_redirect_url' => $failureRedirectUrl,
                'currency' => 'IDR',
                'items' => $this->formatItemsFromTransaction($transaction),
            ]);

            $result = $this->apiInstance->createInvoice($createInvoiceRequest);
            return $result;
        } catch (Exception $e) {
            // Log error
            \Log::error('Xendit Create Invoice Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function formatItemsFromTransaction($transaction)
    {
        $items = [];
        // Extract membership items
        if ($transaction->membershipItems) {
            foreach ($transaction->membershipItems as $item) {
                $items[] = [
                    'name' => 'Membership: ' . ($item->memberType->name ?? 'Unknown'),
                    'quantity' => $item->qty,
                    'price' => $item->unit_price,
                ];
            }
        }
        
        // Extract visit items
        if ($transaction->visitItems) {
            foreach ($transaction->visitItems as $item) {
                $items[] = [
                    'name' => 'Daily Ticket' . ($item->memberType ? ' - ' . $item->memberType->name : ''),
                    'quantity' => $item->qty,
                    'price' => $transaction->amount_total / max(1, $item->qty), // Fallback if no specific unit price
                ];
            }
        }

        // Extract event registrations
        if ($transaction->eventRegistrations) {
            foreach ($transaction->eventRegistrations as $item) {
                $items[] = [
                    'name' => 'Event: ' . ($item->event->title ?? 'Unknown'),
                    'quantity' => 1,
                    'price' => $item->event->price ?? $transaction->amount_total,
                ];
            }
        }
        
        return $items;
    }
}
