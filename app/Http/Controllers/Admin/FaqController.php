<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $faqs = Faq::query()
            ->when($search, function ($query, string $search) {
                $query->where('question', 'like', '%' . $search . '%')
                    ->orWhere('answer', 'like', '%' . $search . '%');
            })
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.content.faqs.index', compact('faqs', 'search'));
    }

    public function create()
    {
        return view('admin.content.faqs.form', [
            'faq' => new Faq(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Faq::create($data);

        return redirect()
            ->route('admin.content.faqs.index')
            ->with('status', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.content.faqs.form', [
            'faq' => $faq,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $this->validateData($request);
        $faq->update($data);

        return redirect()
            ->route('admin.content.faqs.index')
            ->with('status', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()
            ->route('admin.content.faqs.index')
            ->with('status', 'FAQ berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255', 'not_regex:/</'],
            'answer' => ['required', 'string', 'max:2000', 'not_regex:/</'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;
        $data['question'] = $this->sanitizeText($data['question']);
        $data['answer'] = $this->sanitizeText($data['answer']);

        return $data;
    }

    private function sanitizeText(string $value): string
    {
        return trim(strip_tags($value));
    }
}
