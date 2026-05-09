<?php

namespace App\Providers;

use App\Models\School;
use App\Models\SiteAdminSetting;
use App\Support\Admin\AdminNav;
use App\Support\Cohs\CohsLandingRepository;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('forms', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));

        Gate::before(function ($user, string $ability) {
            if ($user === null) {
                return null;
            }

            return $user->hasRole('super_admin') ? true : null;
        });

        Paginator::defaultView('pagination.admin');

        $this->applyStoredSiteSettings();

        View::composer('components.layouts.admin', function ($view): void {
            $view->with('adminNavGroups', AdminNav::groups(request()));
        });

        View::composer([
            'schools.soc.*',
            'components.layouts.partials.soc-header',
            'components.schools.soc.about-sidebar',
        ], function ($view): void {
            $school = $view->offsetGet('school');
            if ($school instanceof School && $school->slug === 'soc') {
                $view->with('socLanding', app(SocLandingRepository::class)->forSchool($school));
            }
        });

        View::composer([
            'schools.cohs.*',
            'components.layouts.partials.cohs-header',
        ], function ($view): void {
            $school = $view->offsetGet('school');
            if ($school instanceof School && $school->slug === 'cohs') {
                $view->with('cohsLanding', app(CohsLandingRepository::class)->forSchool($school));
            }
        });
    }

    /**
     * Merge institution-wide settings from the database over config defaults (super-admin UI).
     */
    protected function applyStoredSiteSettings(): void
    {
        try {
            if (! Schema::hasTable('site_admin_settings')) {
                config(['site_global_seo' => []]);

                return;
            }
        } catch (\Throwable) {
            config(['site_global_seo' => []]);

            return;
        }

        $row = SiteAdminSetting::query()->first();
        $general = is_array($row?->general) ? $row->general : [];
        $globalSeo = is_array($row?->global_seo) ? $row->global_seo : [];

        $updates = [];
        if (! empty($general['site_name'])) {
            $updates['tenwek.name'] = $general['site_name'];
        }
        if (array_key_exists('tagline', $general) && is_string($general['tagline']) && $general['tagline'] !== '') {
            $updates['tenwek.tagline'] = $general['tagline'];
        }
        if (! empty($general['institution_legal'])) {
            $updates['tenwek.institution_legal'] = $general['institution_legal'];
        }
        if (! empty($general['email_public'])) {
            $updates['tenwek.email_public'] = $general['email_public'];
        }
        if (! empty($general['phone'])) {
            $updates['tenwek.phone'] = $general['phone'];
        }

        foreach ($updates as $key => $value) {
            config([$key => $value]);
        }

        config(['site_global_seo' => $globalSeo]);
    }
}
