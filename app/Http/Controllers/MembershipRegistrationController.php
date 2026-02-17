<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberType;
use App\Models\Transaction;
use App\Models\TransactionMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MembershipRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $memberType = MemberType::query()
            ->where('is_active', true)
            ->findOrFail($request->input('member_type_id'));

        $validator = Validator::make($request->all(), [
            'member_type_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'string', 'max:20'],
            'nim' => ['nullable', 'string', 'max:50'],
            'university' => ['nullable', 'string', 'max:150'],
            'nik' => ['nullable', 'string', 'max:32'],
            'birth_date' => ['nullable', 'date'],
            'domicile' => ['nullable', 'string', 'max:150'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:50'],
            'payment_method' => ['nullable', 'string', 'in:qris,bank_transfer'],
        ]);

        $isDaily = $memberType->is_daily || Str::contains(Str::lower($memberType->name), 'harian');

        $validator->after(function ($validator) use ($memberType, $request, $isDaily) {
            if ($isDaily) {
                if (! $request->filled('gender')) {
                    $validator->errors()->add('gender', 'Jenis kelamin wajib diisi untuk kunjungan harian.');
                }
                if (! $request->filled('qty')) {
                    $validator->errors()->add('qty', 'Jumlah orang wajib diisi untuk kunjungan harian.');
                }
                if (! $request->filled('payment_method')) {
                    $validator->errors()->add('payment_method', 'Pilih metode pembayaran.');
                }
                return;
            }

            if (! $request->filled('nik')) {
                $validator->errors()->add('nik', 'NIK wajib diisi untuk pendaftaran anggota.');
            }
            if (! $request->filled('birth_date')) {
                $validator->errors()->add('birth_date', 'Tanggal lahir wajib diisi.');
            }
            if (! $request->filled('domicile')) {
                $validator->errors()->add('domicile', 'Domisili wajib diisi.');
            }
            if (! $request->filled('gender')) {
                $validator->errors()->add('gender', 'Jenis kelamin wajib diisi untuk pendaftaran anggota.');
            }
            if (! $request->filled('payment_method')) {
                $validator->errors()->add('payment_method', 'Pilih metode pembayaran.');
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

        $invoiceId = $this->generateInvoiceId();

        DB::transaction(function () use ($data, $memberType, $invoiceId, $request, $isDaily) {
            if ($isDaily) {
                $qty = (int) ($data['qty'] ?? 1);
                $amountTotal = $memberType->pricing * $qty;

                $transaction = Transaction::create([
                    'member_id' => null,
                    'customer_name' => $data['name'],
                    'customer_email' => $data['email'] ?? null,
                    'customer_phone' => $data['phone'] ?? null,
                    'invoice_id' => $invoiceId,
                    'status' => 'pending',
                    'amount_total' => $amountTotal,
                    'currency' => 'IDR',
                    'channel' => 'online',
                    'payment_method' => $data['payment_method'] ?? null,
                    'expires_at' => now()->addDay(),
                ]);

                \App\Models\TransactionVisit::create([
                    'transaction_id' => $transaction->id,
                    'member_type_id' => $memberType->id,
                    'visit_date' => now()->toDateString(),
                    'gender' => $data['gender'] ?? null,
                    'qty' => $qty,
                ]);

                return;
            }

            $expiresAt = $memberType->duration_days > 0
                ? now()->addDays($memberType->duration_days)->startOfDay()
                : null;

            $member = Member::create([
                'member_type_id' => $memberType->id,
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'nik' => $data['nik'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'domicile' => $data['domicile'] ?? null,
                'nim' => $data['nim'] ?? null,
                'university' => $data['university'] ?? null,
                'is_verified_student' => ! $memberType->is_student,
                'status' => 'pending',
                'expired_at' => $expiresAt,
            ]);

            $transaction = Transaction::create([
                'member_id' => $member->id,
                'customer_name' => $member->name,
                'customer_email' => $member->email,
                'customer_phone' => $member->phone,
                'invoice_id' => $invoiceId,
                'status' => 'pending',
                'amount_total' => $memberType->pricing,
                'currency' => 'IDR',
                'channel' => 'online',
                'payment_method' => $data['payment_method'] ?? null,
                'expires_at' => now()->addDay(),
            ]);

            TransactionMembership::create([
                'transaction_id' => $transaction->id,
                'member_id' => $member->id,
                'member_type_id' => $memberType->id,
                'qty' => 1,
                'unit_price' => $memberType->pricing,
                'subtotal' => $memberType->pricing,
            ]);
        });

        return redirect()
            ->route('landing', ['tab' => 'pricing'])
            ->with('register_status', 'Pendaftaran berhasil. Invoice: ' . $invoiceId);
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
}
