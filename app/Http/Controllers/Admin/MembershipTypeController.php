<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembershipTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $memberTypes = MemberType::query()
            ->withCount('benefits')
            ->when($search, function ($query, string $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('label', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('order')
            ->paginate(12)
            ->withQueryString();

        return view('admin.membership.types.index', compact('memberTypes', 'search'));
    }

    public function create()
    {
        return view('admin.membership.types.form', [
            'memberType' => new MemberType(),
            'benefits' => collect(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($request, $data) {
            $memberType = MemberType::create($data);
            $this->syncBenefits($memberType, $request->input('benefits', []));
        });

        return redirect()
            ->route('admin.membership.types.index')
            ->with('status', 'Paket membership berhasil dibuat.');
    }

    public function edit(MemberType $type)
    {
        return view('admin.membership.types.form', [
            'memberType' => $type,
            'benefits' => $type->benefits()->orderBy('order')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, MemberType $type)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($request, $data, $type) {
            $type->update($data);
            $this->syncBenefits($type, $request->input('benefits', []));
        });

        return redirect()
            ->route('admin.membership.types.index')
            ->with('status', 'Paket membership berhasil diperbarui.');
    }

    public function destroy(MemberType $type)
    {
        $type->delete();

        return redirect()
            ->route('admin.membership.types.index')
            ->with('status', 'Paket membership berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'label' => ['nullable', 'string', 'max:40'],
            'accent_color' => ['nullable', 'string', 'max:32'],
            'pricing' => ['required', 'integer', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_student'] = $request->boolean('is_student');
        $data['is_daily'] = $request->boolean('is_daily');
        $data['is_full_color'] = $request->boolean('is_full_color');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = $data['order'] ?? 0;
        $data['accent_color'] = $this->normalizeColor($data['accent_color'] ?? null);

        if ($data['is_daily']) {
            $data['is_student'] = false;
        }

        return $data;
    }

    private function normalizeColor(?string $color): ?string
    {
        if (! $color) {
            return null;
        }

        $color = trim($color);
        if ($color === '') {
            return null;
        }

        return str_starts_with($color, '#') ? $color : '#' . $color;
    }

    private function syncBenefits(MemberType $memberType, array $benefits): void
    {
        $payload = collect($benefits)
            ->map(function ($benefit, int $index) {
                $label = trim((string) ($benefit['label'] ?? ''));
                if ($label === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'is_included' => ! empty($benefit['is_included']),
                    'order' => $index,
                ];
            })
            ->filter()
            ->values()
            ->all();

        $memberType->benefits()->delete();

        if ($payload !== []) {
            $memberType->benefits()->createMany($payload);
        }
    }
}
