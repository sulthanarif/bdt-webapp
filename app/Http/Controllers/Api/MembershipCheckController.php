<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Member;
use App\Models\MemberType;
use Illuminate\Http\Request;

class MembershipCheckController extends Controller
{
    public function checkNik(Request $request)
    {
        $nik = trim($request->query('nik', ''));
        $memberTypeId = (int) $request->query('member_type_id', 0);

        if (empty($nik) || $memberTypeId <= 0) {
            return response()->json(['is_renewal' => false, 'campaign' => null]);
        }

        $memberType = MemberType::where('is_active', true)->find($memberTypeId);
        if (!$memberType) {
            return response()->json(['is_renewal' => false, 'campaign' => null]);
        }

        $isRenewal = Member::where('nik', $nik)->exists();

        $campaign = Campaign::getActiveAutoPromo('membership', $memberTypeId, $isRenewal);

        $originalPrice = $memberType->pricing;
        $durationBonus = 0;
        $discountAmount = 0;
        if ($campaign) {
            if ($campaign->discount_type === 'duration') {
                $durationBonus = $campaign->getDurationBonus($memberTypeId);
            } else {
                $discountAmount = $campaign->calculateDiscount($originalPrice, $memberTypeId);
            }
        }
        $finalPrice = max(0, $originalPrice - $discountAmount);

        return response()->json([
            'is_renewal'      => $isRenewal,
            'campaign'        => $campaign ? [
                'name'           => $campaign->name,
                'discount_type'  => $campaign->discount_type,
                'discount_value' => $campaign->discount_value,
            ] : null,
            'original_price'  => $originalPrice,
            'discount_amount' => $discountAmount,
            'duration_bonus'  => $durationBonus,
            'final_price'     => $finalPrice,
        ]);
    }

    public function checkVoucher(Request $request)
    {
        $code = strtoupper(trim($request->query('code', '')));
        $memberTypeId = (int) $request->query('member_type_id', 0);
        $nik = trim($request->query('nik', ''));

        if (empty($code) || $memberTypeId <= 0) {
            return response()->json(['valid' => false, 'error' => null]);
        }

        $memberType = MemberType::where('is_active', true)->find($memberTypeId);
        if (!$memberType) {
            return response()->json(['valid' => false, 'error' => 'Paket tidak ditemukan.']);
        }

        $isRenewal = !empty($nik) && Member::where('nik', $nik)->exists();

        $campaign = Campaign::where('code', $code)->first();
        if (!$campaign) {
            return response()->json(['valid' => false, 'error' => 'Voucher tidak ditemukan.']);
        }

        if (!$campaign->isValidFor('membership', $memberTypeId, $isRenewal)) {
            if (!$campaign->isValid()) {
                $error = 'Voucher sudah tidak berlaku atau habis digunakan.';
            } elseif ($campaign->applicable_to === 'renewal' && !$isRenewal) {
                $error = 'Voucher ini hanya untuk perpanjangan anggota.';
            } elseif ($campaign->applicable_to === 'new_member' && $isRenewal) {
                $error = 'Voucher ini hanya untuk anggota baru.';
            } else {
                $error = 'Voucher tidak valid untuk paket ini.';
            }
            return response()->json(['valid' => false, 'error' => $error]);
        }

        $originalPrice = $memberType->pricing;
        $durationBonus = 0;
        $discountAmount = 0;
        if ($campaign->discount_type === 'duration') {
            $durationBonus = $campaign->getDurationBonus($memberTypeId);
        } else {
            $discountAmount = $campaign->calculateDiscount($originalPrice, $memberTypeId);
        }
        $finalPrice = max(0, $originalPrice - $discountAmount);

        return response()->json([
            'valid'           => true,
            'campaign'        => [
                'name'           => $campaign->name,
                'discount_type'  => $campaign->discount_type,
                'discount_value' => $campaign->discount_value,
            ],
            'original_price'  => $originalPrice,
            'discount_amount' => $discountAmount,
            'duration_bonus'  => $durationBonus,
            'final_price'     => $finalPrice,
            'error'           => null,
        ]);
    }
}
