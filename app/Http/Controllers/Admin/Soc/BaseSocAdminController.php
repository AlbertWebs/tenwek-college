<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SocLandingSection;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\Request;

abstract class BaseSocAdminController extends Controller
{
    protected function socSchool(Request $request): School
    {
        return $request->attributes->get('socSchool')
            ?? School::query()->where('slug', 'soc')->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function persistSection(School $soc, string $key, array $payload): void
    {
        SocLandingSection::query()->updateOrCreate(
            ['school_id' => $soc->id, 'section_key' => $key],
            ['payload' => $payload]
        );
        SocLandingRepository::flushCache();
    }

    /**
     * @return array<string, mixed>
     */
    protected function mergedSection(School $soc, string $key): array
    {
        $landing = app(SocLandingRepository::class)->forSchool($soc);

        return $landing[$key] ?? [];
    }
}
