<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\Cohs\CohsDashboardController;
use App\Http\Controllers\Admin\Cohs\CohsFormSubmissionController;
use App\Http\Controllers\Admin\Cohs\CohsJsonSectionController;
use App\Http\Controllers\Admin\Cohs\CohsLandingFormsController;
use App\Http\Controllers\Admin\Cohs\CohsMediaController;
use App\Http\Controllers\Admin\Cohs\CohsNavItemController;
use App\Http\Controllers\Admin\Cohs\CohsNewsAdminController;
use App\Http\Controllers\Admin\Cohs\CohsPageAdminController;
use App\Http\Controllers\Admin\Cohs\CohsTestimonialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadAdminController;
use App\Http\Controllers\Admin\GlobalSeoController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\Soc\SocDashboardController;
use App\Http\Controllers\Admin\Soc\SocFormSubmissionController;
use App\Http\Controllers\Admin\Soc\SocJsonSectionController;
use App\Http\Controllers\Admin\Soc\SocLandingFormsController;
use App\Http\Controllers\Admin\Soc\SocMediaController;
use App\Http\Controllers\Admin\Soc\SocNavItemController;
use App\Http\Controllers\Admin\Soc\SocNewsAdminController;
use App\Http\Controllers\Admin\Soc\SocPageAdminController;
use App\Http\Controllers\Admin\Soc\SocProgrammeGroupController;
use App\Http\Controllers\Admin\Soc\SocProgrammeItemController;
use App\Http\Controllers\Admin\Soc\SocTeamMemberController;
use App\Http\Controllers\Admin\Soc\SocTestimonialController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CohsOnCampusApplicationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicDownloadController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocRegistrationController;
use App\Models\School;
use App\Support\Cohs\CohsLandingRepository;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/robots.txt', function () {
    $content = implode("\n", [
        'User-agent: *',
        'Disallow: /admin',
        'Disallow: /login',
        '',
        'Sitemap: '.url('/sitemap.xml'),
    ]);

    return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('robots');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/search', SearchController::class)
    ->middleware('throttle:60,1')
    ->name('search');

Route::get('/downloads', [PublicDownloadController::class, 'index'])->name('downloads.index');
Route::get('/downloads/{slug}', [PublicDownloadController::class, 'show'])->name('downloads.show');
Route::get('/downloads/{slug}/file', [PublicDownloadController::class, 'file'])
    ->middleware('throttle:60,1')
    ->name('downloads.file');

Route::get('/news', [NewsPostController::class, 'index'])->name('news.index');
Route::get('/news/{post}', [NewsPostController::class, 'show'])->name('news.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:forms')
    ->name('contact.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'active_user', 'role:super_admin|soc_admin|cohs_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/downloads', [DownloadAdminController::class, 'index'])->name('downloads.index');
        Route::get('/downloads/create', [DownloadAdminController::class, 'create'])->name('downloads.create');
        Route::post('/downloads', [DownloadAdminController::class, 'store'])->name('downloads.store');
        Route::get('/downloads/{download}/file', [DownloadAdminController::class, 'file'])
            ->middleware('throttle:120,1')
            ->name('downloads.file');
        Route::get('/downloads/{download}/edit', [DownloadAdminController::class, 'edit'])->name('downloads.edit');
        Route::put('/downloads/{download}', [DownloadAdminController::class, 'update'])->name('downloads.update');
        Route::delete('/downloads/{download}', [DownloadAdminController::class, 'destroy'])->name('downloads.destroy');
    });

Route::middleware(['auth', 'active_user', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');

        Route::resource('users', UserAdminController::class)->except(['show']);
        Route::get('/settings', [SiteSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SiteSettingsController::class, 'update'])->name('settings.update');
        Route::get('/global-seo', [GlobalSeoController::class, 'edit'])->name('global-seo.edit');
        Route::put('/global-seo', [GlobalSeoController::class, 'update'])->name('global-seo.update');
    });

Route::middleware(['auth', 'active_user', 'role:super_admin|soc_admin', 'manages_soc'])
    ->prefix('admin/soc')
    ->name('admin.soc.')
    ->group(function (): void {
        Route::get('/', [SocDashboardController::class, 'index'])->name('dashboard');

        Route::get('/hero', [SocLandingFormsController::class, 'editHero'])->name('hero.edit');
        Route::put('/hero', [SocLandingFormsController::class, 'updateHero'])->name('hero.update');
        Route::get('/about', [SocLandingFormsController::class, 'editAbout'])->name('about.edit');
        Route::put('/about', [SocLandingFormsController::class, 'updateAbout'])->name('about.update');
        Route::get('/mission-vision', [SocLandingFormsController::class, 'editMissionVision'])->name('mission-vision.edit');
        Route::put('/mission-vision', [SocLandingFormsController::class, 'updateMissionVision'])->name('mission-vision.update');
        Route::get('/motto', [SocLandingFormsController::class, 'editMotto'])->name('motto.edit');
        Route::put('/motto', [SocLandingFormsController::class, 'updateMotto'])->name('motto.update');
        Route::get('/contact', [SocLandingFormsController::class, 'editContact'])->name('contact.edit');
        Route::put('/contact', [SocLandingFormsController::class, 'updateContact'])->name('contact.update');
        Route::get('/seo', [SocLandingFormsController::class, 'editSeo'])->name('seo.edit');
        Route::put('/seo', [SocLandingFormsController::class, 'updateSeo'])->name('seo.update');
        Route::get('/top-bar', [SocLandingFormsController::class, 'editTopBar'])->name('top-bar.edit');
        Route::put('/top-bar', [SocLandingFormsController::class, 'updateTopBar'])->name('top-bar.update');

        Route::get('/sections/{section}/json', [SocJsonSectionController::class, 'edit'])->name('json.edit');
        Route::put('/sections/{section}/json', [SocJsonSectionController::class, 'update'])->name('json.update');

        Route::resource('testimonials', SocTestimonialController::class)->except(['show']);
        Route::resource('navigation', SocNavItemController::class)->except(['show']);
        Route::resource('team', SocTeamMemberController::class)->except(['show']);

        Route::resource('programme-groups', SocProgrammeGroupController::class)->except(['show']);
        Route::resource('programme-groups.items', SocProgrammeItemController::class)->except(['show']);

        Route::get('/media', [SocMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [SocMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{medium}', [SocMediaController::class, 'destroy'])->name('media.destroy');

        Route::get('/pages', [SocPageAdminController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [SocPageAdminController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [SocPageAdminController::class, 'update'])->name('pages.update');

        Route::resource('news', SocNewsAdminController::class)->except(['show']);

        Route::get('/submissions', [SocFormSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [SocFormSubmissionController::class, 'show'])->name('submissions.show');
        Route::patch('/submissions/{submission}', [SocFormSubmissionController::class, 'update'])->name('submissions.update');
    });

Route::middleware(['auth', 'active_user', 'role:super_admin|cohs_admin', 'manages_cohs'])
    ->prefix('admin/cohs')
    ->name('admin.cohs.')
    ->group(function (): void {
        Route::get('/', [CohsDashboardController::class, 'index'])->name('dashboard');

        Route::get('/hero', [CohsLandingFormsController::class, 'editHero'])->name('hero.edit');
        Route::put('/hero', [CohsLandingFormsController::class, 'updateHero'])->name('hero.update');
        Route::get('/welcome', [CohsLandingFormsController::class, 'editWelcome'])->name('welcome.edit');
        Route::put('/welcome', [CohsLandingFormsController::class, 'updateWelcome'])->name('welcome.update');
        Route::get('/programmes-band', [CohsLandingFormsController::class, 'editProgrammesBand'])->name('programmes-band.edit');
        Route::put('/programmes-band', [CohsLandingFormsController::class, 'updateProgrammesBand'])->name('programmes-band.update');
        Route::get('/testimonials-band', [CohsLandingFormsController::class, 'editTestimonialsBand'])->name('testimonials-band.edit');
        Route::put('/testimonials-band', [CohsLandingFormsController::class, 'updateTestimonialsBand'])->name('testimonials-band.update');
        Route::get('/about-us', [CohsLandingFormsController::class, 'editAboutUs'])->name('about-us.edit');
        Route::put('/about-us', [CohsLandingFormsController::class, 'updateAboutUs'])->name('about-us.update');
        Route::get('/social-life', [CohsLandingFormsController::class, 'editSocialLife'])->name('social-life.edit');
        Route::put('/social-life', [CohsLandingFormsController::class, 'updateSocialLife'])->name('social-life.update');
        Route::get('/facilities', [CohsLandingFormsController::class, 'editFacilities'])->name('facilities.edit');
        Route::put('/facilities', [CohsLandingFormsController::class, 'updateFacilities'])->name('facilities.update');
        Route::get('/contact', [CohsLandingFormsController::class, 'editContactPage'])->name('contact.edit');
        Route::put('/contact', [CohsLandingFormsController::class, 'updateContactPage'])->name('contact.update');
        Route::get('/seo', [CohsLandingFormsController::class, 'editSeo'])->name('seo.edit');
        Route::put('/seo', [CohsLandingFormsController::class, 'updateSeo'])->name('seo.update');
        Route::get('/top-bar', [CohsLandingFormsController::class, 'editTopBar'])->name('top-bar.edit');
        Route::put('/top-bar', [CohsLandingFormsController::class, 'updateTopBar'])->name('top-bar.update');

        Route::get('/sections/{section}/json', [CohsJsonSectionController::class, 'edit'])->name('json.edit');
        Route::put('/sections/{section}/json', [CohsJsonSectionController::class, 'update'])->name('json.update');

        Route::resource('testimonials', CohsTestimonialController::class)->except(['show']);
        Route::resource('navigation', CohsNavItemController::class)->except(['show']);

        Route::get('/media', [CohsMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [CohsMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{medium}', [CohsMediaController::class, 'destroy'])->name('media.destroy');

        Route::get('/pages', [CohsPageAdminController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [CohsPageAdminController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [CohsPageAdminController::class, 'update'])->name('pages.update');

        Route::resource('news', CohsNewsAdminController::class)->except(['show']);

        Route::get('/submissions', [CohsFormSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [CohsFormSubmissionController::class, 'show'])->name('submissions.show');
        Route::patch('/submissions/{submission}', [CohsFormSubmissionController::class, 'update'])->name('submissions.update');
    });

Route::get('/soc/register', [SocRegistrationController::class, 'show'])->name('soc.register');
Route::post('/soc/register', [SocRegistrationController::class, 'store'])
    ->middleware('throttle:forms')
    ->name('soc.register.store');

Route::get('/cohs/on-campus-application', [CohsOnCampusApplicationController::class, 'show'])->name('cohs.on-campus-application');
Route::post('/cohs/on-campus-application', [CohsOnCampusApplicationController::class, 'store'])
    ->middleware('throttle:forms')
    ->name('cohs.on-campus-application.store');

Route::get('/cohs/off-campus-application', function () {
    $school = School::query()->where('slug', 'cohs')->first();
    abort_unless($school, 404);
    $landing = app(CohsLandingRepository::class)->forSchool($school);
    $url = $landing['off_campus_application_url'] ?? null;
    abort_unless(filled($url), 404);

    return redirect()->away((string) $url);
})->name('cohs.off-campus-application.redirect');

Route::get('/{school}', [SchoolController::class, 'show'])
    ->where('school', 'soc|cohs')
    ->name('schools.show');

Route::get('/{school}/{pageSlug}', [PageController::class, 'showSchool'])
    ->where('school', 'soc|cohs')
    ->where('pageSlug', '[a-z0-9\-]+')
    ->name('schools.pages.show');

Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('pages.show');
