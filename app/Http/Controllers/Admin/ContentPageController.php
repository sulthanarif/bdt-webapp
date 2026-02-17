<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\Request;

class ContentPageController extends Controller
{
    public function edit(string $slug)
    {
        $page = $this->resolvePage($slug);
        $pageTitle = $this->pageTitle($slug);

        return view('admin.content.pages.form', compact('page', 'pageTitle'));
    }

    public function update(Request $request, string $slug)
    {
        $page = $this->resolvePage($slug);
        $data = $this->validateData($request);

        $page->update($data);

        return redirect()
            ->route('admin.content.pages.edit', $page->slug)
            ->with('status', 'Konten halaman berhasil diperbarui.');
    }

    private function resolvePage(string $slug): ContentPage
    {
        $title = $this->pageTitle($slug);
        if ($title === null) {
            abort(404);
        }

        return ContentPage::ensure($slug, $title, 'Konten belum diisi. Silakan lengkapi di admin.');
    }

    private function pageTitle(string $slug): ?string
    {
        return [
            'privacy' => 'Privasi',
            'terms' => 'Syarat dan Ketentuan',
        ][$slug] ?? null;
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150', 'not_regex:/</'],
            'body' => ['required', 'string', 'max:20000', 'not_regex:/</'],
        ]);

        $data['title'] = $this->sanitizeText($data['title']);
        $data['body'] = $this->sanitizeText($data['body']);

        return $data;
    }

    private function sanitizeText(string $value): string
    {
        return trim(strip_tags($value));
    }
}
