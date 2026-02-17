<?php

namespace App\Http\Controllers;

use App\Models\AgendaEvent;
use App\Models\MemberType;
use App\Models\LandingContent;
use App\Models\Faq;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        $landingContent = LandingContent::current();
        $content = $this->mergeLandingDefaults($landingContent->content ?? []);

        $memberTypes = MemberType::query()
            ->with(['benefits' => function ($query) {
                $query->orderBy('order');
            }])
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function (MemberType $type) {
                $type->display_price = $this->formatPriceShort($type->pricing);
                $type->display_period = $this->formatDuration($type->duration_days);
                $type->is_featured = $this->isFeaturedLabel($type->label);
                $type->is_favorite = $this->isFavoriteLabel($type->label);
                $type->cta_link = url('/?tab=pricing&membership=' . $type->id) . '#membership-form';
                $type->accent_is_light = $this->isLightColor($type->accent_color ?: '#14b8a6');

                return $type;
            });

        $hasVisitTicket = $memberTypes->contains(function (MemberType $type) {
            return $type->is_daily || Str::contains(Str::lower($type->name), 'harian');
        });
        $showVisitTicket = ! $hasVisitTicket;

        $visitTicket = [
            'name' => 'Tiket Harian',
            'subtitle' => 'Sekali berkunjung',
            'price' => $this->formatPriceShort(35000),
            'period' => 'hr',
            'cta_link' => url('/daftar?type=visit'),
            'cta_text' => 'Daftar Sekarang',
            'benefits' => [
                ['label' => 'Baca buku sepuasnya', 'is_included' => true],
                ['label' => 'Berlaku 1 hari', 'is_included' => true],
                ['label' => 'Keanggotaan', 'is_included' => false],
                ['label' => 'Pinjam bawa pulang', 'is_included' => false],
            ],
        ];

        $selectedMembershipId = request()->integer('membership');

        $agendaEvents = AgendaEvent::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('starts_at')
            ->orderByDesc('id')
            ->get();

        $faqs = $this->ensureFaqItems();
        $faqBlocks = $faqs->map(function (Faq $item) {
            return [
                'id' => $item->id,
                'question' => $item->question,
                'blocks' => $this->parseFaqAnswer($item->answer),
            ];
        });

        return view('landing', compact(
            'memberTypes',
            'visitTicket',
            'showVisitTicket',
            'selectedMembershipId',
            'agendaEvents',
            'content',
            'faqBlocks'
        ));
    }

    private function formatPriceShort(int $price): string
    {
        $value = (int) round($price / 1000);

        return (string) $value;
    }

    private function formatDuration(int $days): string
    {
        if ($days <= 1) {
            return 'hr';
        }

        if ($days >= 365) {
            return 'thn';
        }

        if ($days % 30 === 0) {
            $months = (int) ($days / 30);
            return $months > 1 ? $months . 'bln' : 'bln';
        }

        return $days . ' hari';
    }

    private function isFeaturedLabel(?string $label): bool
    {
        return $label
            ? Str::contains(Str::upper($label), 'PALING HEMAT')
            : false;
    }

    private function isFavoriteLabel(?string $label): bool
    {
        return $label
            ? Str::contains(Str::upper($label), 'FAVORIT')
            : false;
    }

    private function isLightColor(string $hex): bool
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (strlen($hex) !== 6) {
            return false;
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $luminance = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

        return $luminance > 0.6;
    }

    private function mergeLandingDefaults(array $content): array
    {
        $defaults = config('landing.content');

        return array_replace_recursive($defaults, $content);
    }

    private function ensureFaqItems()
    {
        $existing = Faq::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        if ($existing->isNotEmpty()) {
            return $existing;
        }

        $defaults = config('landing.faq_items', []);
        foreach ($defaults as $index => $item) {
            Faq::query()->create([
                'question' => $item['question'] ?? '',
                'answer' => $item['answer'] ?? '',
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        return Faq::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    private function parseFaqAnswer(string $answer): array
    {
        $lines = preg_split('/\\r\\n|\\r|\\n/', $answer);
        $blocks = [];
        $listItems = [];

        foreach ($lines as $line) {
            $trimmed = trim($line ?? '');
            if ($trimmed === '') {
                continue;
            }

            if (str_starts_with($trimmed, '- ')) {
                $listItems[] = trim(substr($trimmed, 2));
                continue;
            }

            if (! empty($listItems)) {
                $blocks[] = [
                    'type' => 'list',
                    'items' => $listItems,
                ];
                $listItems = [];
            }

            $blocks[] = [
                'type' => 'paragraph',
                'text' => $trimmed,
            ];
        }

        if (! empty($listItems)) {
            $blocks[] = [
                'type' => 'list',
                'items' => $listItems,
            ];
        }

        return $blocks;
    }
}
