<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LandingContentRequest;
use App\Models\LandingContent;

class LandingContentController extends Controller
{
    public function edit()
    {
        $landingContent = LandingContent::current();
        $content = $this->mergeLandingDefaults($landingContent->content ?? []);

        return view('admin.content.landing.edit', [
            'content' => $content,
        ]);
    }

    public function update(LandingContentRequest $request)
    {
        $landingContent = LandingContent::current();
        $payload = $request->validated()['content'];
        $payload = $this->sanitizeContent($payload);

        $landingContent->update([
            'content' => $payload,
        ]);

        return redirect()
            ->route('admin.content.landing.edit')
            ->with('status', 'Konten landing page berhasil diperbarui.');
    }

    private function mergeLandingDefaults(array $content): array
    {
        $defaults = config('landing.content');

        return array_replace_recursive($defaults, $content);
    }

    private function sanitizeContent(array $content): array
    {
        $content = $this->mergeLandingDefaults($content);

        $content['nav']['items'] = $this->filterItems($content['nav']['items'] ?? [], function ($item) {
            return $this->hasValue($item, 'label') && $this->hasValue($item, 'target');
        });

        $content['facilities']['items'] = $this->filterItems($content['facilities']['items'] ?? [], function ($item) {
            return $this->hasValue($item, 'title') || $this->hasValue($item, 'description');
        }, function ($item) {
            $item['badges'] = $this->filterStrings($item['badges'] ?? []);
            return $item;
        });

        $content['facilities']['rental']['items'] = $this->filterStrings($content['facilities']['rental']['items'] ?? []);
        $content['contact']['address_lines'] = $this->filterStrings($content['contact']['address_lines'] ?? []);

        $content['contact']['hours'] = $this->filterItems($content['contact']['hours'] ?? [], function ($item) {
            return $this->hasValue($item, 'day') && $this->hasValue($item, 'time');
        });

        $content['agenda']['items'] = $this->filterItems($content['agenda']['items'] ?? [], function ($item) {
            return $this->hasValue($item, 'title') || $this->hasValue($item, 'description');
        }, function ($item) {
            $item['is_active'] = (bool) ($item['is_active'] ?? false);
            return $item;
        });

        return $content;
    }

    private function filterStrings(array $values): array
    {
        return array_values(array_filter(array_map(function ($value) {
            return is_string($value) ? trim($value) : '';
        }, $values), function ($value) {
            return $value !== '';
        }));
    }

    private function filterItems(array $items, callable $keep, ?callable $transform = null): array
    {
        $filtered = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            if (! $keep($item)) {
                continue;
            }
            $filtered[] = $transform ? $transform($item) : $item;
        }

        return array_values($filtered);
    }

    private function hasValue(array $item, string $key): bool
    {
        $value = $item[$key] ?? null;

        return is_string($value) && trim($value) !== '';
    }
}

