<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Models\Faq;
use App\Models\LandingContent;

class PublicContentController extends Controller
{
    public function privacy()
    {
        return $this->renderPage('privacy', 'Privasi');
    }

    public function terms()
    {
        return $this->renderPage('terms', 'Syarat dan Ketentuan');
    }

    public function faq()
    {
        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $landingContent = LandingContent::current();
        $faqMeta = $landingContent->content['faq_page'] ?? [
            'title' => 'FAQ',
            'description' => 'Pertanyaan yang sering ditanyakan seputar Baca Di Tebet.',
        ];

        return view('public.faq', compact('faqs', 'faqMeta'));
    }

    private function renderPage(string $slug, string $title)
    {
        $page = ContentPage::ensure($slug, $title, 'Konten belum tersedia. Silakan perbarui di admin.');

        return view('public.page', compact('page'));
    }
}
