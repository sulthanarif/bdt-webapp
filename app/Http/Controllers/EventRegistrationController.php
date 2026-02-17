<?php

namespace App\Http\Controllers;

use App\Models\AgendaEvent;
use App\Models\AgendaEventRegistration;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventRegistrationController extends Controller
{
    public function create(AgendaEvent $event)
    {
        if (! $event->is_active || ! $event->use_internal_registration) {
            abort(404);
        }

        return view('public.event-register', compact('event'));
    }

    public function store(Request $request, AgendaEvent $event)
    {
        if (! $event->is_active || ! $event->use_internal_registration) {
            abort(404);
        }

        $customFields = $this->getRegistrationFields($event);

        $baseRules = [
            'name' => ['required', 'string', 'max:120', 'not_regex:/</'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40', 'not_regex:/</'],
            'is_member' => ['required', 'in:0,1'],
            'member_identifier' => ['nullable', 'string', 'max:120', 'not_regex:/</'],
        ];

        $customRules = $this->buildCustomRules($customFields);

        $data = $request->validate(array_merge($baseRules, $customRules));

        $isMember = $request->boolean('is_member');
        $memberIdentifier = trim((string) ($data['member_identifier'] ?? ''));

        if ($isMember && $memberIdentifier === '') {
            return back()
                ->withErrors(['member_identifier' => 'Mohon isi ID/Email/No HP member.'])
                ->withInput();
        }

        $memberId = null;
        if ($memberIdentifier !== '') {
            $member = Member::query()
                ->where('email', $memberIdentifier)
                ->orWhere('phone', $memberIdentifier)
                ->first();
            $memberId = $member?->id;
        }

        $answers = $this->extractAnswers($request->input('custom', []), $customFields);

        AgendaEventRegistration::create([
            'agenda_event_id' => $event->id,
            'member_id' => $memberId,
            'name' => $this->sanitizeText($data['name']),
            'email' => $data['email'] ? trim($data['email']) : null,
            'phone' => $data['phone'] ? $this->sanitizeText($data['phone']) : null,
            'is_member' => $isMember,
            'member_identifier' => $memberIdentifier !== '' ? $memberIdentifier : null,
            'event_price' => $event->price,
            'answers' => $answers,
        ]);

        return redirect()
            ->route('events.register', $event)
            ->with('status', 'Pendaftaran berhasil. Tim kami akan menghubungi Anda.');
    }

    private function sanitizeText(string $value): string
    {
        return trim(strip_tags($value));
    }

    private function getRegistrationFields(AgendaEvent $event): array
    {
        $defaults = $this->defaultRegistrationFields();
        $fields = $event->registration_fields ?? [];
        $fields = is_array($fields) ? $fields : [];

        $merged = [];
        $used = [];

        foreach (array_merge($defaults, $fields) as $field) {
            if (! is_array($field)) {
                continue;
            }
            $key = $field['key'] ?? null;
            if (! $key || in_array($key, $used, true)) {
                continue;
            }
            $used[] = $key;
            $merged[] = $field;
        }

        return $merged ?: $defaults;
    }

    private function defaultRegistrationFields(): array
    {
        return [
            ['key' => 'usia', 'label' => 'Usia', 'type' => 'text', 'required' => true, 'options' => []],
            ['key' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'required' => true, 'options' => []],
            ['key' => 'domisili', 'label' => 'Domisili', 'type' => 'text', 'required' => true, 'options' => []],
        ];
    }

    private function buildCustomRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $key = $field['key'] ?? null;
            if (! $key) {
                continue;
            }

            $type = $field['type'] ?? 'text';
            $required = (bool) ($field['required'] ?? false);
            $options = is_array($field['options'] ?? null) ? $field['options'] : [];

            if ($type === 'checkbox') {
                $rules["custom.$key"] = array_filter([
                    $required ? 'required' : 'nullable',
                    'array',
                ]);
                if ($options) {
                    $rules["custom.$key.*"] = ['string', Rule::in($options)];
                }
                continue;
            }

            $ruleSet = [$required ? 'required' : 'nullable', 'string', 'max:500'];
            if ($type === 'email') {
                $ruleSet = [$required ? 'required' : 'nullable', 'email', 'max:150'];
            } elseif ($type === 'number') {
                $ruleSet = [$required ? 'required' : 'nullable', 'numeric'];
            } elseif ($type === 'tel') {
                $ruleSet = [$required ? 'required' : 'nullable', 'string', 'max:40'];
            } elseif (in_array($type, ['select', 'radio'], true) && $options) {
                $ruleSet[] = Rule::in($options);
            }

            $rules["custom.$key"] = $ruleSet;
        }

        return $rules;
    }

    private function extractAnswers(array $input, array $fields): array
    {
        $answers = [];

        foreach ($fields as $field) {
            $key = $field['key'] ?? null;
            if (! $key) {
                continue;
            }

            $value = $input[$key] ?? null;
            if (is_array($value)) {
                $answers[$key] = array_values(array_filter(array_map(function ($item) {
                    if (! is_string($item)) {
                        return null;
                    }

                    $clean = trim(strip_tags($item));
                    return $clean === '' ? null : $clean;
                }, $value)));
                continue;
            }

            if (is_string($value)) {
                $answers[$key] = trim(strip_tags($value));
            }
        }

        return $answers;
    }
}
