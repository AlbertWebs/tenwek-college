@php
    /** @var array<string, mixed> $formRow */
    $r = $formRow;
    $compSlots = 8;
    $rows = $r['comparison_rows'] ?? [['left' => '', 'right' => '']];
@endphp

<x-admin.ui.group label="Sort order" for="sort_order" name="sort_order" hint="Lower numbers appear first on the public page.">
    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $r['sort_order'] ?? 0) }}" class="admin-input" min="0" max="65535">
</x-admin.ui.group>

<x-admin.ui.group label="Question (accordion title)" for="question" name="question">
    <input type="text" name="question" id="question" value="{{ old('question', $r['question'] ?? '') }}" required class="admin-input" placeholder="e.g. How long is the course?">
</x-admin.ui.group>

<x-admin.ui.group label="Optional lead sentence" for="lead" name="lead" hint="Shown directly under the title when the accordion is open.">
    <textarea name="lead" id="lead" rows="2" class="admin-textarea">{{ old('lead', $r['lead'] ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Main answer (paragraphs)" for="paragraphs" name="paragraphs" hint="Separate paragraphs with a blank line.">
    <textarea name="paragraphs" id="paragraphs" rows="8" class="admin-textarea">{{ old('paragraphs', $r['paragraphs'] ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Short lines (optional)" for="lines" name="lines" hint="One fact per line.">
    <textarea name="lines" id="lines" rows="3" class="admin-textarea">{{ old('lines', $r['lines'] ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Bullet list (optional)" for="bullets" name="bullets" hint="One bullet per line.">
    <textarea name="bullets" id="bullets" rows="4" class="admin-textarea">{{ old('bullets', $r['bullets'] ?? '') }}</textarea>
</x-admin.ui.group>

<details class="admin-field-inset rounded-[var(--admin-radius-field)]">
    <summary class="cursor-pointer text-sm font-semibold text-thc-navy">Two-column comparison table (optional)</summary>
    <p class="admin-hint mt-2">Up to {{ $compSlots }} rows; leave cells blank to skip.</p>
    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <x-admin.ui.group label="Left column header" for="comp_left_header" name="comp_left_header">
            <input type="text" name="comp_left_header" id="comp_left_header" value="{{ old('comp_left_header', $r['comp_left_header'] ?? '') }}" class="admin-input" placeholder="e.g. Pastors">
        </x-admin.ui.group>
        <x-admin.ui.group label="Right column header" for="comp_right_header" name="comp_right_header">
            <input type="text" name="comp_right_header" id="comp_right_header" value="{{ old('comp_right_header', $r['comp_right_header'] ?? '') }}" class="admin-input" placeholder="e.g. Chaplains">
        </x-admin.ui.group>
    </div>
    <ul class="mt-4 space-y-3">
        @for($idx = 0; $idx < $compSlots; $idx++)
            @php
                $crow = $rows[$idx] ?? ['left' => '', 'right' => ''];
            @endphp
            <li class="grid gap-3 rounded-lg border border-thc-navy/8 bg-white p-3 sm:grid-cols-2">
                <div>
                    <label class="admin-label text-xs font-medium normal-case">Left — row {{ $idx + 1 }}</label>
                    <textarea name="comparison_rows[{{ $idx }}][left]" rows="2" class="admin-textarea mt-1 text-sm">{{ old("comparison_rows.$idx.left", $crow['left']) }}</textarea>
                </div>
                <div>
                    <label class="admin-label text-xs font-medium normal-case">Right — row {{ $idx + 1 }}</label>
                    <textarea name="comparison_rows[{{ $idx }}][right]" rows="2" class="admin-textarea mt-1 text-sm">{{ old("comparison_rows.$idx.right", $crow['right']) }}</textarea>
                </div>
            </li>
        @endfor
    </ul>
</details>

<details class="admin-field-inset mt-4 rounded-[var(--admin-radius-field)]">
    <summary class="cursor-pointer text-sm font-semibold text-thc-navy">Certificate &amp; diploma boxes (optional)</summary>
    <div class="admin-form-stack mt-4">
        <x-admin.ui.group label="Certificate title" for="cert_title" name="cert_title">
            <input type="text" name="cert_title" id="cert_title" value="{{ old('cert_title', $r['cert_title'] ?? '') }}" class="admin-input">
        </x-admin.ui.group>
        <x-admin.ui.group label="Certificate text" for="cert_body" name="cert_body">
            <textarea name="cert_body" id="cert_body" rows="2" class="admin-textarea">{{ old('cert_body', $r['cert_body'] ?? '') }}</textarea>
        </x-admin.ui.group>
        <x-admin.ui.group label="Diploma section title" for="dip_title" name="dip_title">
            <input type="text" name="dip_title" id="dip_title" value="{{ old('dip_title', $r['dip_title'] ?? '') }}" class="admin-input">
        </x-admin.ui.group>
        <x-admin.ui.group label="Diploma points (one per line)" for="dip_items" name="dip_items">
            <textarea name="dip_items" id="dip_items" rows="4" class="admin-textarea">{{ old('dip_items', $r['dip_items'] ?? '') }}</textarea>
        </x-admin.ui.group>
    </div>
</details>
