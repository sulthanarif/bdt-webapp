<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $campaigns = Campaign::query()
            ->when($search, function ($query, string $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.campaigns.index', compact('campaigns', 'search'));
    }

    public function create()
    {
        $campaign = new Campaign();
        $memberships = \App\Models\MemberType::where('is_active', true)->get();
        $events = \App\Models\AgendaEvent::where('is_active', true)->get();
        return view('admin.campaigns.form', compact('campaign', 'memberships', 'events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'code' => ['nullable', 'string', 'max:50', 'unique:campaigns,code'],
            'discount_type' => ['required', 'string', 'in:percentage,fixed,duration'],
            'discount_value' => ['nullable', 'integer', 'min:1'],
            'target_type' => ['required', 'string', 'in:any,membership,event'],
            'applicable_to' => ['required', 'string', 'in:any,new_member,renewal'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'target_items_checked' => ['nullable', 'array'],
            'target_item_overrides' => ['nullable', 'array'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['code'] = $request->input('promo_method') === 'voucher' ? ($data['code'] ? strtoupper($data['code']) : null) : null;
        
        if ($data['target_type'] === 'any') {
            $data['target_items'] = null;
        } else {
            $targetItems = [];
            $checked = $request->input('target_items_checked', []);
            $overrides = $request->input('target_item_overrides', []);
            
            foreach ($checked as $id) {
                $val = $overrides[$id] ?? null;
                $targetItems[(string)$id] = ($val !== null && $val !== '') ? (string)$val : null;
            }
            $data['target_items'] = empty($targetItems) ? null : $targetItems;
        }

        unset($data['target_items_checked'], $data['target_item_overrides']);

        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign berhasil ditambahkan.');
    }

    public function show(Campaign $campaign)
    {
        $transactions = $campaign->transactions()
            ->with([
                'member', 
                'member.memberType',
                'membershipItems.memberType',
                'visitItems.memberType',
                'eventRegistrations.event'
            ])
            ->latest()
            ->paginate(15);

        return view('admin.campaigns.show', compact('campaign', 'transactions'));
    }

    public function edit(Campaign $campaign)
    {
        $memberships = \App\Models\MemberType::where('is_active', true)->get();
        $events = \App\Models\AgendaEvent::where('is_active', true)->get();
        return view('admin.campaigns.form', compact('campaign', 'memberships', 'events'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'code' => ['nullable', 'string', 'max:50', 'unique:campaigns,code,' . $campaign->id],
            'discount_type' => ['required', 'string', 'in:percentage,fixed,duration'],
            'discount_value' => ['nullable', 'integer', 'min:1'],
            'target_type' => ['required', 'string', 'in:any,membership,event'],
            'applicable_to' => ['required', 'string', 'in:any,new_member,renewal'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'target_items_checked' => ['nullable', 'array'],
            'target_item_overrides' => ['nullable', 'array'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['code'] = $request->input('promo_method') === 'voucher' ? ($data['code'] ? strtoupper($data['code']) : null) : null;
        
        if ($data['target_type'] === 'any') {
            $data['target_items'] = null;
        } else {
            $targetItems = [];
            $checked = $request->input('target_items_checked', []);
            $overrides = $request->input('target_item_overrides', []);
            
            foreach ($checked as $id) {
                $val = $overrides[$id] ?? null;
                $targetItems[(string)$id] = ($val !== null && $val !== '') ? (string)$val : null;
            }
            $data['target_items'] = empty($targetItems) ? null : $targetItems;
        }

        unset($data['target_items_checked'], $data['target_item_overrides']);

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign berhasil dihapus.');
    }
}

