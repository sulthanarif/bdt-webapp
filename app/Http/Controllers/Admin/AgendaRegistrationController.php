<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaEvent;
use Illuminate\Http\Request;

class AgendaRegistrationController extends Controller
{
    public function index(Request $request, AgendaEvent $event)
    {
        $search = $request->string('q')->trim()->value();

        $registrations = $event->registrations()
            ->when($search, function ($query, string $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('member_identifier', 'like', '%' . $search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $fieldLabels = collect($event->registration_fields ?? [])
            ->filter(fn ($field) => is_array($field) && ! empty($field['key']))
            ->mapWithKeys(fn ($field) => [$field['key'] => $field['label'] ?? $field['key']])
            ->all();

        return view('admin.events.registrations', compact('event', 'registrations', 'search', 'fieldLabels'));
    }
}
