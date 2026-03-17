<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Transaction;
use App\Models\TransactionMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $members = Member::query()
            ->with(['memberType', 'latestTransaction'])
            ->when($search, function ($query, string $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.membership.members.index', compact('members', 'search'));
    }

    public function create()
    {
        return view('admin.membership.members.form', [
            'member' => new Member(),
            'memberTypes' => MemberType::where('is_daily', false)->orderBy('order')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateMember($request);

        DB::transaction(function () use ($data, $request) {
            $member = Member::create($data);
        });

        return redirect()
            ->route('admin.membership.members.index')
            ->with('status', 'Member berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        return view('admin.membership.members.form', [
            'member' => $member,
            'memberTypes' => MemberType::where('is_daily', false)->orderBy('order')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Member $member)
    {
        $data = $this->validateMember($request, $member->id);
        $member->update($data);

        return redirect()
            ->route('admin.membership.members.index')
            ->with('status', 'Member berhasil diperbarui.');
    }

    private function validateMember(Request $request, ?int $memberId = null): array
    {
        $validator = Validator::make($request->all(), [
            'member_type_id' => ['nullable', 'integer', 'exists:member_types,id'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'string', 'max:20'],
            'nik' => ['nullable', 'string', 'max:32'],
            'birth_date' => ['nullable', 'date'],
            'domicile' => ['nullable', 'string', 'max:150'],
            'nim' => ['nullable', 'string', 'max:50'],
            'university' => ['nullable', 'string', 'max:150'],
            'expired_at' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:20'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (! $request->filled('member_type_id')) {
                return;
            }

            $memberType = MemberType::find($request->input('member_type_id'));
            if (! $memberType || $memberType->is_daily) {
                $validator->errors()->add('member_type_id', 'Pilih paket membership yang valid.');
                return;
            }

            if (! $request->filled('nik')) {
                $validator->errors()->add('nik', 'NIK wajib diisi untuk member.');
            }
            if (! $request->filled('birth_date')) {
                $validator->errors()->add('birth_date', 'Tanggal lahir wajib diisi.');
            }
            if (! $request->filled('domicile')) {
                $validator->errors()->add('domicile', 'Domisili wajib diisi.');
            }
            if (! $request->filled('gender')) {
                $validator->errors()->add('gender', 'Jenis kelamin wajib diisi.');
            }

            if ($memberType->is_student) {
                if (! $request->filled('nim')) {
                    $validator->errors()->add('nim', 'NIM wajib diisi untuk paket pelajar.');
                }
                if (! $request->filled('university')) {
                    $validator->errors()->add('university', 'Nama kampus wajib diisi untuk paket pelajar.');
                }
            }
        });

        $data = $validator->validate();

        $data['is_verified_student'] = $request->boolean('is_verified_student');
        if ($request->filled('member_type_id')) {
            $memberType = MemberType::find($request->input('member_type_id'));
            if (! $memberType || ! $memberType->is_student) {
                $data['is_verified_student'] = false;
            }
        }
        if (! $request->filled('expired_at') && $request->filled('member_type_id')) {
            $memberType = MemberType::find($request->input('member_type_id'));
            if ($memberType && $memberType->duration_days > 0) {
                $data['expired_at'] = now()->addDays($memberType->duration_days)->startOfDay();
            }
        }

        return $data;
    }
}
