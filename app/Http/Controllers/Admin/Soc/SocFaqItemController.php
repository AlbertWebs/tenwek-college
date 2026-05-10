<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\Page;
use App\Models\SocFaqItem;
use App\Models\SocLandingSection;
use App\Support\Soc\SocFaqForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocFaqItemController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $items = SocFaqItem::query()
            ->where('school_id', $soc->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $canImportLegacy = $items->isEmpty();

        return view('admin.soc.faqs.index', [
            'soc' => $soc,
            'items' => $items,
            'canImportLegacy' => $canImportLegacy,
        ]);
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);
        $formRow = SocFaqForm::emptyFormRow();

        return view('admin.soc.faqs.create', [
            'soc' => $soc,
            'formRow' => $formRow,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate(SocFaqForm::validationRules());
        $normalized = SocFaqForm::normalizeItemsFromRequest([$validated]);
        if ($normalized === []) {
            return back()->withErrors(['question' => 'Question is required.'])->withInput();
        }
        $legacy = $normalized[0];
        $question = $legacy['question'];
        unset($legacy['question']);

        SocFaqItem::query()->create([
            'school_id' => $soc->id,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'question' => $question,
            'payload' => $legacy,
        ]);

        return redirect()->route('admin.soc.faqs.index')->with('status', 'FAQ created.');
    }

    public function edit(Request $request, SocFaqItem $faq): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $faq->school_id === (int) $soc->id, 404);
        $formRow = SocFaqForm::legacyItemToFormRow($faq->toLegacyItemArray());
        $formRow['sort_order'] = $faq->sort_order;

        return view('admin.soc.faqs.edit', [
            'soc' => $soc,
            'faq' => $faq,
            'formRow' => $formRow,
        ]);
    }

    public function update(Request $request, SocFaqItem $faq): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $faq->school_id === (int) $soc->id, 404);
        $validated = $request->validate(SocFaqForm::validationRules());
        $normalized = SocFaqForm::normalizeItemsFromRequest([$validated]);
        if ($normalized === []) {
            return back()->withErrors(['question' => 'Question is required.'])->withInput();
        }
        $legacy = $normalized[0];
        $question = $legacy['question'];
        unset($legacy['question']);

        $faq->update([
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'question' => $question,
            'payload' => $legacy,
        ]);

        return redirect()->route('admin.soc.faqs.index')->with('status', 'FAQ updated.');
    }

    public function destroy(Request $request, SocFaqItem $faq): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $faq->school_id === (int) $soc->id, 404);
        $faq->delete();

        return redirect()->route('admin.soc.faqs.index')->with('status', 'FAQ deleted.');
    }

    public function editIntro(Request $request): View
    {
        $soc = $this->socSchool($request);
        $faqs = $this->mergedSection($soc, 'faqs');
        $faqsPage = Page::query()
            ->where('school_id', $soc->id)
            ->where('slug', 'faqs')
            ->first();

        return view('admin.soc.faqs.intro', [
            'soc' => $soc,
            'kicker' => old('kicker', $faqs['kicker'] ?? ''),
            'intro' => old('intro', $faqs['intro'] ?? ''),
            'faqsPage' => $faqsPage,
        ]);
    }

    public function updateIntro(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'kicker' => ['nullable', 'string', 'max:120'],
            'intro' => ['nullable', 'string', 'max:20000'],
        ]);

        $row = SocLandingSection::query()
            ->where('school_id', $soc->id)
            ->where('section_key', 'faqs')
            ->first();
        $payload = is_array($row?->payload) ? $row->payload : [];
        $payload['kicker'] = $validated['kicker'] ?? '';
        $payload['intro'] = $validated['intro'] ?? '';
        unset($payload['items']);
        $this->persistSection($soc, 'faqs', $payload);

        return redirect()->route('admin.soc.faqs.intro.edit')->with('status', 'FAQ page intro saved.');
    }

    public function importLegacy(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        if (SocFaqItem::query()->where('school_id', $soc->id)->exists()) {
            return redirect()->route('admin.soc.faqs.index')->with('status', 'FAQs already exist in the database.');
        }

        $sectionRow = SocLandingSection::query()
            ->where('school_id', $soc->id)
            ->where('section_key', 'faqs')
            ->first();
        $items = [];
        if (is_array($sectionRow?->payload['items'] ?? null)) {
            $items = $sectionRow->payload['items'];
        }
        if ($items === []) {
            $items = config('tenwek.soc_landing.faqs.items', []);
        }
        if (! is_array($items) || $items === []) {
            return redirect()->route('admin.soc.faqs.index')->with('status', 'No legacy FAQ items found to import.');
        }

        $imported = 0;
        foreach (array_values($items) as $i => $data) {
            if (! is_array($data)) {
                continue;
            }
            $question = trim((string) ($data['question'] ?? ''));
            if ($question === '') {
                continue;
            }
            $legacy = $data;
            unset($legacy['question']);
            SocFaqItem::query()->create([
                'school_id' => $soc->id,
                'sort_order' => $i,
                'question' => $question,
                'payload' => $legacy !== [] ? $legacy : null,
            ]);
            $imported++;
        }

        $payload = is_array($sectionRow?->payload) ? $sectionRow->payload : [];
        unset($payload['items']);
        $this->persistSection($soc, 'faqs', $payload);

        return redirect()->route('admin.soc.faqs.index')->with('status', "Imported {$imported} FAQ(s) from config / saved JSON.");
    }
}
