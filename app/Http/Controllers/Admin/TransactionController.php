<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);

        $transactions = Transaction::query()
            ->with(['member', 'createdBy'])
            ->when($filters['q'], function ($query, string $q) {
                $query->where('invoice_id', 'like', '%' . $q . '%')
                    ->orWhere('customer_name', 'like', '%' . $q . '%')
                    ->orWhere('customer_email', 'like', '%' . $q . '%')
                    ->orWhere('customer_phone', 'like', '%' . $q . '%');
            })
            ->when($filters['status'], fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['channel'], fn ($query, string $channel) => $query->where('channel', $channel))
            ->when($filters['date_from'], fn ($query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'], fn ($query, string $date) => $query->whereDate('created_at', '<=', $date))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'filters'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['member', 'createdBy', 'membershipItems.memberType', 'visitItems.memberType']);

        return view('admin.transactions.show', compact('transaction'));
    }

    private function filters(Request $request): array
    {
        return [
            'q' => $request->string('q')->trim()->value(),
            'status' => $request->string('status')->trim()->value(),
            'channel' => $request->string('channel')->trim()->value(),
            'date_from' => $request->string('date_from')->trim()->value(),
            'date_to' => $request->string('date_to')->trim()->value(),
        ];
    }
}
