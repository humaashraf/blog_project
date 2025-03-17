<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryReportController extends Controller
{
    public function index(Request $request)
{
    $query = Category::query();

    if ($request->has('type')) {
        $query->where('type', $request->type);
    }

    // Date Filter Apply
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    $categories = $query->get();

    return view('backend.admin.categories.category_report', compact('categories'));
}


    public function exportPDF(Request $request)
    {
        $query = Category::query();


        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->get(); // Filtered categories

        $pdf = PDF::loadView('backend.admin.categories.category_pdf', compact('categories'));

        return $pdf->download('categories_report.pdf');
    }

    // public function topViewedPostsReport()
    // {
    //     $categories = Category::with(['posts' => function($query) {
    //         $query->withCount('visitors')->orderByDesc('visitors_count');
    //     }])->get();

    //     return view('backend.admin.categories.top_viewed_posts', compact('categories'));
    // }
//     public function topViewedPostsReport()
// {
//     $categories = Category::with(['posts' => function($query) {
//         $query->orderByDesc('visitor_count'); // Visitor count column ke mutabiq sorting
//     }])->get();

//     return view('backend.admin.categories.top_viewed_posts', compact('categories'));
// }
public function topViewedPostsReport()
{
    $categories = Category::with(['posts' => function($query) {
        $query->where('is_approved', 1)
        ->whereNotNull('published_at')->withCount('visitors')
              ->orderByDesc('visitors_count');
    }])->get();

    return view('backend.admin.categories.top_viewed_posts', compact('categories'));
}

public function exportTopViewedPostsPDF()
{
    $categories = Category::with(['posts' => function($query) {
        $query->where('is_approved', 1)
        ->whereNotNull('published_at')->withCount('visitors')->orderByDesc('visitors_count');
    }])->get();

    $pdf = Pdf::loadView('backend.admin.categories.top_viewed_posts_pdf', compact('categories'));

    return $pdf->download('top_viewed_posts.pdf');
}


}
