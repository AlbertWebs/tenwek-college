<?php

namespace App\Support\Soc;

/**
 * Normalizes FAQ accordion rows between legacy arrays (config / JSON) and admin forms.
 */
final class SocFaqForm
{
    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    public static function normalizeFormRow(array $row): array
    {
        $base = self::emptyFormRow();
        $base['question'] = (string) ($row['question'] ?? '');
        $base['lead'] = (string) ($row['lead'] ?? '');
        $base['paragraphs'] = (string) ($row['paragraphs'] ?? '');
        $base['lines'] = (string) ($row['lines'] ?? '');
        $base['bullets'] = (string) ($row['bullets'] ?? '');
        $base['comp_left_header'] = (string) ($row['comp_left_header'] ?? '');
        $base['comp_right_header'] = (string) ($row['comp_right_header'] ?? '');
        $compRows = $row['comparison_rows'] ?? [];
        if (is_array($compRows) && $compRows !== []) {
            $base['comparison_rows'] = array_values(array_map(function (mixed $r): array {
                if (! is_array($r)) {
                    return ['left' => '', 'right' => ''];
                }

                return [
                    'left' => (string) ($r['left'] ?? ''),
                    'right' => (string) ($r['right'] ?? ''),
                ];
            }, $compRows));
        }

        $base['cert_title'] = (string) ($row['cert_title'] ?? '');
        $base['cert_body'] = (string) ($row['cert_body'] ?? '');
        $base['dip_title'] = (string) ($row['dip_title'] ?? '');
        $base['dip_items'] = (string) ($row['dip_items'] ?? '');

        return $base;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    public static function legacyItemToFormRow(array $item): array
    {
        $rows = [];
        foreach ($item['comparison']['rows'] ?? [] as $r) {
            if (! is_array($r)) {
                continue;
            }
            $rows[] = [
                'left' => (string) ($r['left'] ?? ''),
                'right' => (string) ($r['right'] ?? ''),
            ];
        }

        return [
            'question' => (string) ($item['question'] ?? ''),
            'lead' => (string) ($item['lead'] ?? ''),
            'paragraphs' => implode("\n\n", $item['paragraphs'] ?? []),
            'lines' => implode("\n", $item['lines'] ?? []),
            'bullets' => implode("\n", $item['bullets'] ?? []),
            'comp_left_header' => (string) ($item['comparison']['left_header'] ?? ''),
            'comp_right_header' => (string) ($item['comparison']['right_header'] ?? ''),
            'comparison_rows' => $rows !== [] ? $rows : [['left' => '', 'right' => '']],
            'cert_title' => (string) ($item['certificate']['title'] ?? ''),
            'cert_body' => (string) ($item['certificate']['body'] ?? ''),
            'dip_title' => (string) ($item['diploma']['title'] ?? ''),
            'dip_items' => implode("\n", $item['diploma']['items'] ?? []),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function emptyFormRow(): array
    {
        return [
            'sort_order' => 0,
            'question' => '',
            'lead' => '',
            'paragraphs' => '',
            'lines' => '',
            'bullets' => '',
            'comp_left_header' => '',
            'comp_right_header' => '',
            'comparison_rows' => [['left' => '', 'right' => '']],
            'cert_title' => '',
            'cert_body' => '',
            'dip_title' => '',
            'dip_items' => '',
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $rawItems
     * @return list<array<string, mixed>>
     */
    public static function normalizeItemsFromRequest(array $rawItems): array
    {
        $out = [];
        foreach ($rawItems as $raw) {
            if (! is_array($raw)) {
                continue;
            }
            $question = trim((string) ($raw['question'] ?? ''));
            if ($question === '') {
                continue;
            }
            $item = ['question' => $question];

            $lead = trim((string) ($raw['lead'] ?? ''));
            if ($lead !== '') {
                $item['lead'] = $lead;
            }

            $paragraphs = self::splitParagraphBlocks((string) ($raw['paragraphs'] ?? ''));
            if ($paragraphs !== []) {
                $item['paragraphs'] = $paragraphs;
            }

            $lines = self::nonEmptyLines((string) ($raw['lines'] ?? ''));
            if ($lines !== []) {
                $item['lines'] = $lines;
            }

            $bullets = self::nonEmptyLines((string) ($raw['bullets'] ?? ''));
            if ($bullets !== []) {
                $item['bullets'] = $bullets;
            }

            $rows = [];
            foreach ($raw['comparison_rows'] ?? [] as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $L = trim((string) ($row['left'] ?? ''));
                $R = trim((string) ($row['right'] ?? ''));
                if ($L !== '' || $R !== '') {
                    $rows[] = ['left' => $L, 'right' => $R];
                }
            }
            $lh = trim((string) ($raw['comp_left_header'] ?? ''));
            $rh = trim((string) ($raw['comp_right_header'] ?? ''));
            if ($rows !== []) {
                $item['comparison'] = [
                    'left_header' => $lh !== '' ? $lh : 'Pastors',
                    'right_header' => $rh !== '' ? $rh : 'Chaplains',
                    'rows' => $rows,
                ];
            }

            $ct = trim((string) ($raw['cert_title'] ?? ''));
            $cb = trim((string) ($raw['cert_body'] ?? ''));
            if ($ct !== '' || $cb !== '') {
                $item['certificate'] = ['title' => $ct, 'body' => $cb];
            }

            $dt = trim((string) ($raw['dip_title'] ?? ''));
            $ditems = self::nonEmptyLines((string) ($raw['dip_items'] ?? ''));
            if ($dt !== '' || $ditems !== []) {
                $item['diploma'] = ['title' => $dt, 'items' => $ditems];
            }

            $out[] = $item;
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    public static function splitParagraphBlocks(string $text): array
    {
        $parts = preg_split('/\n\s*\n/', $text) ?: [];
        $out = [];
        foreach ($parts as $part) {
            $t = trim((string) $part);
            if ($t !== '') {
                $out[] = $t;
            }
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    public static function nonEmptyLines(string $text): array
    {
        $out = [];
        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
        foreach ($lines as $line) {
            $t = trim((string) $line);
            if ($t !== '') {
                $out[] = $t;
            }
        }

        return $out;
    }

    /**
     * Validation rules for one FAQ accordion (create/update form).
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        return [
            'question' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'lead' => ['nullable', 'string', 'max:20000'],
            'paragraphs' => ['nullable', 'string', 'max:50000'],
            'lines' => ['nullable', 'string', 'max:20000'],
            'bullets' => ['nullable', 'string', 'max:50000'],
            'comp_left_header' => ['nullable', 'string', 'max:200'],
            'comp_right_header' => ['nullable', 'string', 'max:200'],
            'comparison_rows' => ['nullable', 'array'],
            'comparison_rows.*.left' => ['nullable', 'string', 'max:10000'],
            'comparison_rows.*.right' => ['nullable', 'string', 'max:10000'],
            'cert_title' => ['nullable', 'string', 'max:500'],
            'cert_body' => ['nullable', 'string', 'max:20000'],
            'dip_title' => ['nullable', 'string', 'max:500'],
            'dip_items' => ['nullable', 'string', 'max:50000'],
        ];
    }
}
