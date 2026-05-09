<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\SocProgrammeGroup;
use App\Models\SocProgrammeItem;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Console\Command;

class ImportSocProgrammesFromConfig extends Command
{
    protected $signature = 'soc:import-programmes {--force : Import even if groups already exist}';

    protected $description = 'Import SOC academic programmes from config/tenwek.php into the database CMS tables';

    public function handle(): int
    {
        $school = School::query()->where('slug', 'soc')->first();
        if (! $school) {
            $this->error('School with slug "soc" not found.');

            return self::FAILURE;
        }

        $exists = SocProgrammeGroup::query()->where('school_id', $school->id)->exists();
        if ($exists && ! $this->option('force')) {
            $this->warn('Programme groups already exist. Use --force to replace (deletes existing groups and items).');

            return self::FAILURE;
        }

        if ($exists && $this->option('force')) {
            SocProgrammeGroup::query()->where('school_id', $school->id)->delete();
            $this->info('Removed existing programme groups.');
        }

        $ap = config('tenwek.soc_landing.academic_programmes', []);
        $groups = $ap['groups'] ?? [];
        if ($groups === []) {
            $this->error('No academic_programmes.groups in config.');

            return self::FAILURE;
        }

        $gOrder = 0;
        foreach ($groups as $groupData) {
            $group = SocProgrammeGroup::query()->create([
                'school_id' => $school->id,
                'heading' => $groupData['heading'] ?? 'Programmes',
                'description' => $groupData['description'] ?? null,
                'sort_order' => $gOrder++,
            ]);
            $iOrder = 0;
            foreach ($groupData['items'] ?? [] as $itemData) {
                $slug = $itemData['slug'] ?? null;
                if (! filled($slug)) {
                    continue;
                }
                SocProgrammeItem::query()->create([
                    'school_id' => $school->id,
                    'soc_programme_group_id' => $group->id,
                    'slug' => $slug,
                    'title' => $itemData['title'] ?? $slug,
                    'badge' => $itemData['badge'] ?? null,
                    'summary' => $itemData['summary'] ?? '',
                    'sort_order' => $iOrder++,
                    'is_published' => true,
                ]);
            }
        }

        SocLandingRepository::flushCache();
        $this->info('Imported '.count($groups).' programme group(s).');

        return self::SUCCESS;
    }
}
