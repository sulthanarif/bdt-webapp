<?php

namespace App\Http\Requests\Admin;

use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;

class LandingContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $noHtml = new NoHtml();

        return [
            'content' => ['required', 'array'],
            'content.meta.title' => ['required', 'string', 'max:120', $noHtml],
            'content.meta.description' => ['nullable', 'string', 'max:255', $noHtml],

            'content.nav.items' => ['array'],
            'content.nav.items.*.label' => ['nullable', 'string', 'max:60', $noHtml],
            'content.nav.items.*.target' => ['nullable', 'string', 'max:30', $noHtml],
            'content.nav.cta' => ['nullable', 'string', 'max:60', $noHtml],

            'content.hero.badge' => ['required', 'string', 'max:120', $noHtml],
            'content.hero.title_prefix' => ['required', 'string', 'max:120', $noHtml],
            'content.hero.title_highlight' => ['required', 'string', 'max:120', $noHtml],
            'content.hero.title_suffix' => ['required', 'string', 'max:120', $noHtml],
            'content.hero.description_before' => ['required', 'string', 'max:800', $noHtml],
            'content.hero.description_highlight' => ['required', 'string', 'max:200', $noHtml],
            'content.hero.description_after' => ['required', 'string', 'max:400', $noHtml],
            'content.hero.primary_cta' => ['required', 'string', 'max:60', $noHtml],
            'content.hero.secondary_cta' => ['required', 'string', 'max:60', $noHtml],

            'content.about.title' => ['required', 'string', 'max:120', $noHtml],
            'content.about.quote' => ['nullable', 'string', 'max:400', $noHtml],
            'content.about.paragraph_one' => ['required', 'string', 'max:1500', $noHtml],
            'content.about.paragraph_two_before' => ['required', 'string', 'max:1200', $noHtml],
            'content.about.paragraph_two_highlight' => ['required', 'string', 'max:400', $noHtml],
            'content.about.image' => ['required', 'string', 'max:255', $noHtml],
            'content.about.image_alt' => ['required', 'string', 'max:120', $noHtml],

            'content.facilities.title' => ['required', 'string', 'max:120', $noHtml],
            'content.facilities.description' => ['required', 'string', 'max:300', $noHtml],
            'content.facilities.items' => ['array'],
            'content.facilities.items.*.title' => ['nullable', 'string', 'max:120', $noHtml],
            'content.facilities.items.*.description' => ['nullable', 'string', 'max:400', $noHtml],
            'content.facilities.items.*.image' => ['nullable', 'string', 'max:255', $noHtml],
            'content.facilities.items.*.alt' => ['nullable', 'string', 'max:120', $noHtml],
            'content.facilities.items.*.icon' => ['nullable', 'string', 'max:60', $noHtml],
            'content.facilities.items.*.badges' => ['array'],
            'content.facilities.items.*.badges.*' => ['nullable', 'string', 'max:40', $noHtml],
            'content.facilities.rental.title' => ['required', 'string', 'max:120', $noHtml],
            'content.facilities.rental.items' => ['array'],
            'content.facilities.rental.items.*' => ['nullable', 'string', 'max:120', $noHtml],
            'content.facilities.rental.cta_label' => ['required', 'string', 'max:80', $noHtml],

            'content.pricing.title' => ['required', 'string', 'max:120', $noHtml],
            'content.pricing.description' => ['required', 'string', 'max:300', $noHtml],
            'content.pricing.faq_title' => ['required', 'string', 'max:120', $noHtml],
            'content.pricing.faq_description' => ['required', 'string', 'max:200', $noHtml],

            'content.agenda.title' => ['required', 'string', 'max:120', $noHtml],
            'content.agenda.description' => ['required', 'string', 'max:300', $noHtml],
            'content.agenda.items' => ['array'],
            'content.agenda.items.*.status_label' => ['nullable', 'string', 'max:60', $noHtml],
            'content.agenda.items.*.category' => ['nullable', 'string', 'max:80', $noHtml],
            'content.agenda.items.*.title' => ['nullable', 'string', 'max:160', $noHtml],
            'content.agenda.items.*.subtitle' => ['nullable', 'string', 'max:160', $noHtml],
            'content.agenda.items.*.date' => ['nullable', 'string', 'max:120', $noHtml],
            'content.agenda.items.*.location' => ['nullable', 'string', 'max:180', $noHtml],
            'content.agenda.items.*.description' => ['nullable', 'string', 'max:600', $noHtml],
            'content.agenda.items.*.cta_label' => ['nullable', 'string', 'max:80', $noHtml],
            'content.agenda.items.*.cta_url' => ['nullable', 'string', 'max:255', $noHtml],
            'content.agenda.items.*.image' => ['nullable', 'string', 'max:255', $noHtml],
            'content.agenda.items.*.image_alt' => ['nullable', 'string', 'max:120', $noHtml],
            'content.agenda.items.*.theme' => ['nullable', 'string', 'max:20', $noHtml],
            'content.agenda.items.*.is_active' => ['nullable', 'boolean'],

            'content.blog.title' => ['required', 'string', 'max:120', $noHtml],
            'content.blog.description' => ['required', 'string', 'max:200', $noHtml],
            'content.blog.cta_label' => ['required', 'string', 'max:80', $noHtml],

            'content.contact.title' => ['required', 'string', 'max:120', $noHtml],
            'content.contact.address_title' => ['required', 'string', 'max:80', $noHtml],
            'content.contact.address_lines' => ['array'],
            'content.contact.address_lines.*' => ['nullable', 'string', 'max:120', $noHtml],
            'content.contact.email_title' => ['required', 'string', 'max:80', $noHtml],
            'content.contact.email' => ['required', 'string', 'max:120', $noHtml],
            'content.contact.instagram_title' => ['required', 'string', 'max:80', $noHtml],
            'content.contact.instagram_handle' => ['required', 'string', 'max:80', $noHtml],
            'content.contact.hours_title' => ['required', 'string', 'max:120', $noHtml],
            'content.contact.hours' => ['array'],
            'content.contact.hours.*.day' => ['nullable', 'string', 'max:40', $noHtml],
            'content.contact.hours.*.time' => ['nullable', 'string', 'max:40', $noHtml],
            'content.contact.map_embed' => ['required', 'string', 'max:500', $noHtml],

            'content.footer.privacy_label' => ['required', 'string', 'max:60', $noHtml],
            'content.footer.terms_label' => ['required', 'string', 'max:60', $noHtml],
            'content.footer.faq_label' => ['required', 'string', 'max:60', $noHtml],

            'content.links' => ['array'],
            'content.links.*' => ['nullable', 'string', 'max:255', $noHtml],

            'content.faq_page.title' => ['required', 'string', 'max:120', $noHtml],
            'content.faq_page.description' => ['required', 'string', 'max:200', $noHtml],
        ];
    }
}

