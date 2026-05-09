<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Programme and option values for COHS on-campus online application
 * (aligned with legacy http://197.138.207.16:3036/online-application/).
 */
final class CohsHealthSciencesApplicationOptions
{
    /**
     * @return array<string, string> value => label
     */
    public static function programmes(): array
    {
        return [
            'diploma_clinical_medicine_surgery' => 'Diploma in Clinical Medicine and Surgery',
            'krchn_diploma' => 'Kenya Registered Community Health Nursing (diploma)',
            'krccn' => 'Kenya Registered Critical Care Nursing',
            'hd_clinical_medicine_perfusion' => 'Higher Diploma in Clinical Medicine and Surgery (cardiopulmonary perfusion)',
            'krten' => 'Kenya Registered Trauma and Emergency Nurse',
        ];
    }

    /**
     * @return list<string>
     */
    public static function programmeValues(): array
    {
        return array_keys(self::programmes());
    }

    /**
     * @return array<string, string>
     */
    public static function studyModes(): array
    {
        return [
            'full_time' => 'Full time',
            'odel' => 'Open Distance Learning (ODeL)',
        ];
    }

    /**
     * @return list<string>
     */
    public static function studyModeValues(): array
    {
        return array_keys(self::studyModes());
    }

    /**
     * @return array<string, string>
     */
    public static function campuses(): array
    {
        return [
            'tenwek' => 'Tenwek Campus',
            'bomet' => 'Bomet Campus',
        ];
    }

    /**
     * @return list<string>
     */
    public static function campusValues(): array
    {
        return array_keys(self::campuses());
    }

    public static function labelForProgramme(string $value): string
    {
        return self::programmes()[$value] ?? Str::title(str_replace('_', ' ', $value));
    }
}
