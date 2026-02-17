<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionVisit;
use Illuminate\Http\Request;

class TransactionVisitController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->string('q')->trim()->value(),
            'status' => $request->string('status')->trim()->value(),
            'channel' => $request->string('channel')->trim()->value(),
            'date_from' => $request->string('date_from')->trim()->value(),
            'date_to' => $request->string('date_to')->trim()->value(),
        ];

        $items = TransactionVisit::query()
            ->with(['transaction', 'memberType'])
            ->whereHas('memberType', fn ($query) => $query->where('is_daily', true))
            ->when($filters['q'], function ($query, string $q) {
                $query->whereHas('transaction', function ($transactionQuery) use ($q) {
                    $transactionQuery->where('invoice_id', 'like', '%' . $q . '%')
                        ->orWhere('customer_name', 'like', '%' . $q . '%')
                        ->orWhere('customer_email', 'like', '%' . $q . '%')
                        ->orWhere('customer_phone', 'like', '%' . $q . '%');
                });
            })
            ->when($filters['status'], function ($query, string $status) {
                $query->whereHas('transaction', fn ($transactionQuery) => $transactionQuery->where('status', $status));
            })
            ->when($filters['channel'], function ($query, string $channel) {
                $query->whereHas('transaction', fn ($transactionQuery) => $transactionQuery->where('channel', $channel));
            })
            ->when($filters['date_from'], function ($query, string $date) {
                $query->whereHas('transaction', fn ($transactionQuery) => $transactionQuery->whereDate('created_at', '>=', $date));
            })
            ->when($filters['date_to'], function ($query, string $date) {
                $query->whereHas('transaction', fn ($transactionQuery) => $transactionQuery->whereDate('created_at', '<=', $date));
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.transactions.visits', compact('items', 'filters'));
    }
}
