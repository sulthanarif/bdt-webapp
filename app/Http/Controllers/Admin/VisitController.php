<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberType;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionMembership;
use App\Models\TransactionVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderVisits($request, 'onsite');
    }

    public function indexOnline(Request $request)
    {
        return $this->renderVisits($request, 'online');
    }

    public function create()
    {
        return view('admin.membership.visits.form', [
            'dailyTypes' => MemberType::where('is_daily', true)->orderBy('order')->get(),
            'memberTypes' => MemberType::where('is_daily', false)->orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_kind' => ['required', 'string', 'in:daily,member'],
            'daily_type_id' => ['nullable', 'integer', 'exists:member_types,id'],
            'member_type_id' => ['nullable', 'integer', 'exists:member_types,id'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'visit_date' => ['nullable', 'date'],
            'gender' => ['required', 'string', 'max:20'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:50'],
            'nik' => ['nullable', 'string', 'max:32'],
            'birth_date' => ['nullable', 'date'],
            'domicile' => ['nullable', 'string', 'max:150'],
            'nim' => ['nullable', 'string', 'max:50'],
            'university' => ['nullable', 'string', 'max:150'],
            'expired_at' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:pending,paid'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:120'],
            'paid_at' => ['nullable', 'date'],
            'is_verified_student' => ['nullable', 'boolean'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $kind = $request->input('visit_kind');
            if ($kind === 'daily') {
                $memberType = MemberType::find($request->input('daily_type_id'));
                if (! $memberType || ! $memberType->is_daily) {
                    $validator->errors()->add('daily_type_id', 'Pilih paket kunjungan harian yang valid.');
                }
                if (! $request->filled('qty')) {
                    $validator->errors()->add('qty', 'Jumlah orang wajib diisi untuk tiket harian.');
                }
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
        $kind = $data['visit_kind'];
        $invoiceId = $this->generateInvoiceId();

        $paidAt = $data['status'] === 'paid'
            ? ($request->filled('paid_at') ? $request->date('paid_at') : now())
            : null;

        if ($kind === 'daily') {
            $memberType = MemberType::find($data['daily_type_id']);
            $qty = (int) ($data['qty'] ?? 1);
            $amountTotal = ($memberType?->pricing ?? 0) * $qty;

            DB::transaction(function () use ($data, $memberType, $amountTotal, $invoiceId, $request, $paidAt, $qty) {
                $transaction = Transaction::create([
                    'member_id' => null,
                    'created_by' => $request->user()?->id,
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'] ?? null,
                    'customer_phone' => $data['customer_phone'] ?? null,
                    'invoice_id' => $invoiceId,
                    'status' => $data['status'],
                    'amount_total' => $amountTotal,
                    'currency' => 'IDR',
                    'channel' => 'onsite',
                    'payment_method' => $data['payment_method'] ?? null,
                    'payment_reference' => $data['payment_reference'] ?? null,
                    'paid_at' => $paidAt,
                ]);

                TransactionVisit::create([
                    'transaction_id' => $transaction->id,
                    'member_type_id' => $memberType?->id,
                    'visit_date' => $data['visit_date'] ?? now()->toDateString(),
                    'gender' => $data['gender'],
                    'qty' => $qty,
                ]);
            });

            return redirect()
                ->route('admin.membership.visits.index')
                ->with('status', 'Kunjungan di tempat berhasil dibuat.');
        }

        $memberType = MemberType::find($data['member_type_id']);
        $amountTotal = $memberType?->pricing ?? 0;

        DB::transaction(function () use ($data, $memberType, $amountTotal, $invoiceId, $request, $paidAt) {
            $member = Member::create([
                'member_type_id' => $memberType?->id,
                'name' => $data['customer_name'],
                'email' => $data['customer_email'] ?? null,
                'phone' => $data['customer_phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'nik' => $data['nik'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'domicile' => $data['domicile'] ?? null,
                'nim' => $data['nim'] ?? null,
                'university' => $data['university'] ?? null,
                'expired_at' => $data['expired_at'] ?? null,
                'status' => $data['status'] === 'paid' ? 'active' : 'pending',
                'is_verified_student' => $memberType?->is_student ? (bool) ($data['is_verified_student'] ?? false) : false,
            ]);

            if (! $member->expired_at && $memberType && $memberType->duration_days > 0) {
                $member->update([
                    'expired_at' => now()->addDays($memberType->duration_days)->startOfDay(),
                ]);
            }

            $transaction = Transaction::create([
                'member_id' => $member->id,
                'created_by' => $request->user()?->id,
                'customer_name' => $member->name,
                'customer_email' => $member->email,
                'customer_phone' => $member->phone,
                'invoice_id' => $invoiceId,
                'status' => $data['status'],
                'amount_total' => $amountTotal,
                'currency' => 'IDR',
                'channel' => 'onsite',
                'payment_method' => $data['payment_method'] ?? null,
                'payment_reference' => $data['payment_reference'] ?? null,
                'paid_at' => $paidAt,
            ]);

            TransactionMembership::create([
                'transaction_id' => $transaction->id,
                'member_id' => $member->id,
                'member_type_id' => $memberType?->id,
                'qty' => 1,
                'unit_price' => $amountTotal,
                'subtotal' => $amountTotal,
            ]);
        });

        return redirect()
            ->route('admin.membership.members.index')
            ->with('status', 'Pendaftaran member onsite berhasil dibuat.');
    }

    private function generateInvoiceId(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';

        do {
            $suffix = Str::upper(Str::random(6));
            $invoiceId = $prefix . $suffix;
        } while (Transaction::where('invoice_id', $invoiceId)->exists());

        return $invoiceId;
    }

    private function renderVisits(Request $request, string $channel)
    {
        $search = $request->string('q')->trim()->value();

        $visits = TransactionVisit::query()
            ->with(['transaction', 'memberType'])
            ->whereHas('memberType', function ($memberTypeQuery) {
                $memberTypeQuery->where('is_daily', true);
            })
            ->whereHas('transaction', function ($transactionQuery) use ($channel) {
                $transactionQuery->where('channel', $channel);
            })
            ->when($search, function ($query, string $search) {
                $query->whereHas('transaction', function ($transactionQuery) use ($search) {
                    $transactionQuery->where('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('customer_email', 'like', '%' . $search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $search . '%')
                        ->orWhere('invoice_id', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $mode = $channel === 'online' ? 'online' : 'onsite';
        $showAddButton = $mode === 'onsite';

        return view('admin.membership.visits.index', compact('visits', 'search', 'mode', 'showAddButton'));
    }
}
