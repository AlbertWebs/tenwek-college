<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Http\Controllers\Controller;
use App\Models\CohsLandingSection;
use App\Models\School;
use App\Support\Cohs\CohsLandingRepository;
use Illuminate\Http\Request;

abstract class BaseCohsAdminController extends Controller
{
    protected function cohsSchool(Request $request): School
    {
        return $request->attributes->get('cohsSchool')
            ?? School::query()->where('slug', 'cohs')->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function persistSection(School $cohs, string $key, array $payload): void
    {
        CohsLandingSection::query()->updateOrCreate(
            ['school_id' => $cohs->id, 'section_key' => $key],
            ['payload' => $payload]
        );
        CohsLandingRepository::flushCache();
    }

    /**
     * @return array<string, mixed>
     */
    protected function mergedSection(School $cohs, string $key): array
    {
        $landing = app(CohsLandingRepository::class)->forSchool($cohs);

        return is_array($landing[$key] ?? null) ? $landing[$key] : [];
    }
}
