<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Support\Seo\SeoPresenter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $seo = SeoPresenter::build($request, [
            'title' => config('tenwek.name').' | '.config('tenwek.institution_legal'),
            'description' => config('tenwek.tagline'),
            'faq' => [
                [
                    'question' => 'What is Tenwek Hospital College?',
                    'answer' => 'Tenwek Hospital College is the academic arm of Tenwek Hospital, offering nursing and health sciences programmes alongside chaplaincy formation, grounded in compassionate, Christ-minded service.',
                ],
                [
                    'question' => 'Where is Tenwek Hospital College located?',
                    'answer' => 'The college is located in Bomet County, Kenya, alongside Tenwek Hospital — a Level 5 teaching and referral mission hospital.',
                ],
            ],
        ]);

        $schools = School::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('home', compact('seo', 'schools'));
    }
}
