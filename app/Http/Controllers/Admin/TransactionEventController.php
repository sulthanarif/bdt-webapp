<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaEventRegistration;
use Illuminate\Http\Request;

class TransactionEventController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->string('q')->trim()->value(),
            'date_from' => $request->string('date_from')->trim()->value(),
            'date_to' => $request->string('date_to')->trim()->value(),
        ];

        $registrations = AgendaEventRegistration::query()
            ->with('event')
            ->when($filters['q'], function ($query, string $q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhereHas('event', fn ($eventQuery) => $eventQuery->where('title', 'like', '%' . $q . '%'));
            })
            ->when($filters['date_from'], fn ($query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'], fn ($query, string $date) => $query->whereDate('created_at', '<=', $date))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.transactions.events', compact('registrations', 'filters'));
    }
}
