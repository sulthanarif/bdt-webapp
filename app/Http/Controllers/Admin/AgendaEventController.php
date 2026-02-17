<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AgendaEventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->value();

        $events = AgendaEvent::query()
            ->withCount('registrations')
            ->when($search, function ($query, string $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('subtitle', 'like', '%' . $search . '%')
                    ->orWhere('category_label', 'like', '%' . $search . '%');
            })
            ->orderBy('order')
            ->orderByDesc('starts_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.events.index', compact('events', 'search'));
    }

    public function create()
    {
        return view('admin.events.form', [
            'event' => new AgendaEvent(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $event = AgendaEvent::create($data);

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('status', 'Event berhasil dibuat.');
    }

    public function edit(AgendaEvent $event)
    {
        return view('admin.events.form', [
            'event' => $event,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, AgendaEvent $event)
    {
        $data = $this->validateData($request, $event);

        $event->update($data);

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('status', 'Event berhasil diperbarui.');
    }

    public function destroy(AgendaEvent $event)
    {
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('status', 'Event berhasil dihapus.');
    }

    private function validateData(Request $request, ?AgendaEvent $event = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:160', 'not_regex:/</'],
            'subtitle' => ['nullable', 'string', 'max:200', 'not_regex:/</'],
            'category_label' => ['nullable', 'string', 'max:60', 'not_regex:/</'],
            'category_style' => ['nullable', Rule::in(['teal', 'slate'])],
            'status_label' => ['nullable', 'string', 'max:60', 'not_regex:/</'],
            'image_file' => ['nullable', 'image', 'max:3072'],
            'image_url' => ['nullable', 'string', 'max:500', 'not_regex:/</'],
            'image_alt' => ['nullable', 'string', 'max:150', 'not_regex:/</'],
            'date_label' => ['nullable', 'string', 'max:120', 'not_regex:/</'],
            'location_label' => ['nullable', 'string', 'max:160', 'not_regex:/</'],
            'description' => ['nullable', 'string', 'max:3000', 'not_regex:/</'],
            'cta_url' => ['nullable', 'string', 'max:500', 'not_regex:/</'],
            'use_internal_registration' => ['nullable', 'boolean'],
            'price' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'order' => ['nullable', 'integer', 'min:0'],
            'registration_fields' => ['nullable', 'string'],
        ];

        $data = $request->validate($rules);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_finished'] = $request->boolean('is_finished');
        $data['order'] = $data['order'] ?? 0;
        $data['category_style'] = $data['category_style'] ?? 'teal';
        $data['use_internal_registration'] = $request->boolean('use_internal_registration');
        $data['cta_label'] = null;
        $data['cta_style'] = 'teal';
        $data['status_label'] = null;

        $data['title'] = $this->sanitizeText($data['title']);
        $data['subtitle'] = $this->sanitizeText($data['subtitle'] ?? null);
        $data['category_label'] = $this->sanitizeText($data['category_label'] ?? null);
        $data['status_label'] = $this->sanitizeText($data['status_label'] ?? null);
        $data['image_alt'] = $this->sanitizeText($data['image_alt'] ?? null);
        $data['image_url'] = $this->sanitizeText($data['image_url'] ?? null);
        $data['date_label'] = null;
        $data['location_label'] = $this->sanitizeText($data['location_label'] ?? null);
        $data['description'] = $this->sanitizeText($data['description'] ?? null);
        $data['registration_fields'] = $this->normalizeRegistrationFields($request->input('registration_fields'));

        if ($request->hasFile('image_file')) {
            $data['image_path'] = $request->file('image_file')->store('agenda-events', 'public');
            $data['image_url'] = null;

            if ($event && $event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
        } else {
            $data['image_url'] = $data['image_url'] ?? ($event?->image_url);
            $data['image_path'] = $event?->image_path;
        }

        $hasImage = ! empty($data['image_url']) || ! empty($data['image_path']);
        if (! $hasImage) {
            $request->validate([
                'image_url' => ['required'],
            ], [
                'image_url.required' => 'Upload gambar atau isi URL gambar terlebih dahulu.',
            ]);
        }

        return $data;
    }

    private function sanitizeText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(strip_tags($value));
    }

    private function normalizeRegistrationFields(?string $raw): ?array
    {
        $decoded = null;
        if ($raw !== null && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
        }

        $allowedTypes = ['text', 'textarea', 'email', 'tel', 'number', 'select', 'radio', 'checkbox'];
        $fields = [];
        $usedKeys = [];

        if (is_array($decoded)) {
            foreach ($decoded as $index => $field) {
                if (! is_array($field)) {
                    continue;
                }

                $label = $this->sanitizeText($field['label'] ?? null);
                if ($label === null || $label === '') {
                    continue;
                }

                $type = $field['type'] ?? 'text';
                if (! in_array($type, $allowedTypes, true)) {
                    $type = 'text';
                }

                $required = (bool) ($field['required'] ?? false);
                $options = [];

                if (in_array($type, ['select', 'radio', 'checkbox'], true)) {
                    $rawOptions = $field['options'] ?? [];
                    if (is_string($rawOptions)) {
                        $rawOptions = array_map('trim', explode(',', $rawOptions));
                    }

                    if (is_array($rawOptions)) {
                        foreach ($rawOptions as $option) {
                            $optionValue = $this->sanitizeText(is_string($option) ? $option : null);
                            if ($optionValue !== null && $optionValue !== '') {
                                $options[] = $optionValue;
                            }
                        }
                    }

                    if ($options === []) {
                        continue;
                    }
                }

                $key = $this->normalizeFieldKey($field['key'] ?? null, $label, (int) $index, $usedKeys);
                if (in_array($key, ['usia', 'pekerjaan', 'domisili'], true)) {
                    continue;
                }
                $usedKeys[] = $key;

                $fields[] = [
                    'key' => $key,
                    'label' => $label,
                    'type' => $type,
                    'required' => $required,
                    'options' => $options,
                ];
            }
        }

        $fields = $this->ensureDefaultQuestions($fields, $usedKeys);

        return $fields ?: null;
    }

    private function normalizeFieldKey(?string $value, string $label, int $index, array $usedKeys): string
    {
        $base = $value ? $this->slugify($value) : $this->slugify($label);
        if ($base === '') {
            $base = 'field_' . ($index + 1);
        }

        $key = $base;
        $counter = 2;
        while (in_array($key, $usedKeys, true)) {
            $key = $base . '_' . $counter;
            $counter += 1;
        }

        return $key;
    }

    private function slugify(string $value): string
    {
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '_', $value) ?? '';
        $value = trim($value, '_');

        return $value;
    }

    private function ensureDefaultQuestions(array $fields, array $usedKeys): array
    {
        $defaults = [
            ['key' => 'usia', 'label' => 'Usia', 'type' => 'text', 'required' => true],
            ['key' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'required' => true],
            ['key' => 'domisili', 'label' => 'Domisili', 'type' => 'text', 'required' => true],
        ];

        foreach ($defaults as $default) {
            $exists = false;
            foreach ($fields as $field) {
                if (($field['key'] ?? '') === $default['key']) {
                    $exists = true;
                    break;
                }
            }

            if (! $exists) {
                $key = $this->normalizeFieldKey($default['key'], $default['label'], count($fields), $usedKeys);
                $usedKeys[] = $key;
                $fields[] = [
                    'key' => $key,
                    'label' => $default['label'],
                    'type' => $default['type'],
                    'required' => true,
                    'options' => [],
                ];
            }
        }

        return $fields;
    }
}
