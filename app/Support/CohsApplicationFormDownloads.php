<?php

namespace App\Support;

/**
 * COHS printable application forms: titles and source PDFs aligned with
 * https://tenwekhospitalcollege.ac.ke/cohs/application-forms/
 */
final class CohsApplicationFormDownloads
{
    /**
     * @return list<array{slug: string, title: string, source_url: string}>
     */
    public static function definitions(): array
    {
        return [
            [
                'slug' => 'cohs-application-form-clinical-rev-2025',
                'title' => 'APPLICATION FORM-CLINICAL- REV 2025',
                'source_url' => 'https://tenwekhospitalcollege.ac.ke/cohs/wp-content/uploads/2025/01/APPLICATION-FORM-CLINICAL-REV-2025.pdf',
            ],
            [
                'slug' => 'cohs-application-form-critical-care-nursing-rev-2025',
                'title' => 'APPLICATION FORM -Critical care Nursing.Rev 2025',
                'source_url' => 'https://tenwekhospitalcollege.ac.ke/cohs/wp-content/uploads/2025/01/APPLICATION-FORM-Critical-care-Nursing.Rev-2025-doc.pdf',
            ],
            [
                'slug' => 'cohs-application-form-hnd-cardiac-perfusion-rev-2025',
                'title' => 'APPLICATION FORM -HND Cardiac Perfusion.Rev 2025',
                'source_url' => 'https://tenwekhospitalcollege.ac.ke/cohs/wp-content/uploads/2025/01/APPLICATION-FORM-HND-Cardiac-Perfusion.Rev-2025-doc.pdf',
            ],
            [
                'slug' => 'cohs-application-form-hnd-trauma-emergency-2025',
                'title' => 'APLICATION FORM-HND TRAUMA & EMERGENCY 2025',
                'source_url' => 'https://tenwekhospitalcollege.ac.ke/cohs/wp-content/uploads/2025/02/APPLICATION-FORM-HND-Trauma-and-Emergency-.Rev-2025-doc.pdf',
            ],
            [
                'slug' => 'cohs-higher-diploma-cardiovascular-perfusion',
                'title' => 'HIGHER DIPLOMA IN CARDIOVASCULAR PERFUSION',
                'source_url' => 'https://tenwekhospitalcollege.ac.ke/cohs/wp-content/uploads/2026/01/APPLICATION-FORM-HND-Cardiac-Perfusion.Rev-Dec.-2025-doc.pdf',
            ],
            [
                'slug' => 'cohs-krchn-application-form',
                'title' => 'KENYA REGISTERED COMMUNITY HEALTH NURSING (KRCHN)',
                'source_url' => 'https://tenwekhospitalcollege.ac.ke/cohs/wp-content/uploads/2026/01/APPLICATION-FORM-NURSING-REV-Dec.-2025.pdf',
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function orderedSlugs(): array
    {
        return array_column(self::definitions(), 'slug');
    }
}
