<?php


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Backend\Admin\CategoryController;
use App\Http\Controllers\Backend\Admin\TagController;
use App\Http\Controllers\Backend\Admin\SliderController;
use App\Http\Controllers\Backend\Admin\SettingController;
use App\Http\Controllers\Backend\Admin\AboutController;
use App\Http\Controllers\Backend\Admin\ContactController;
use App\Http\Controllers\backend\Admin\PostController as AdminPostController;
use App\Http\Controllers\backend\Author\PostController as AuthorPostsController;
use App\Http\Controllers\Backend\Admin\PendingPostController;
// use App\Http\Controllers\Backend\Admin\PostReasonController;
use App\Http\Controllers\Backend\Author\AuthorController;
use App\Http\Controllers\Backend\Admin\AdminDashboardController;
use App\Http\Controllers\Backend\Admin\NewsletterController;
use App\Http\Controllers\Backend\Admin\PendingCommentController;
use App\Http\Controllers\Backend\Admin\ContactFormController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Backend\Admin\VisitorsController;
use App\Http\Controllers\Backend\Admin\SubAdminController;
use App\Http\Controllers\Backend\Admin\UsersController;
use App\Http\Controllers\Backend\Admin\PostReportController;
use App\Http\Controllers\Backend\Admin\CategoryReportController;
use App\Http\Controllers\Backend\Admin\RoleController;


// use App\Http\Controllers\Frontend\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });







Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');





Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Route::middleware(['auth', 'role:admin|manager|reader'])->group(function () {

    // Route::middleware(['auth', 'redirect.non.authors'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('backend.dashboard');

        // Categories
        Route::get('categories', [CategoryController::class, 'index'])->name('backend.admin.categories.index');
        Route::post('categories/store', [CategoryController::class, 'store'])->name('backend.admin.categories.store');
        Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('backend.admin.categories.edit');
        Route::delete('categories/delete/{id}', [CategoryController::class, 'delete'])->name('backend.admin.categories.delete');

        //  Posts
        Route::get('posts', [AdminPostController::class, 'index'])->name('backend.admin.posts.index');
        Route::post('posts/store', [AdminPostController::class, 'store'])->name('backend.admin.posts.store');
        Route::get('posts/{id}/edit', [AdminPostController::class, 'edit'])->name('backend.admin.posts.edit');
        Route::delete('posts/delete/{id}', [AdminPostController::class, 'delete'])->name('backend.admin.posts.delete');
        Route::get('post/{slug}/{id}', [AdminPostController::class, 'show'])->name('backend.admin.post.show');

        // Pending Post
        Route::get('pending-posts', [PendingPostController::class, 'index'])->name('backend.admin.pendingposts.index');
        Route::post('pending-posts/store', [PendingPostController::class, 'store'])->name('backend.admin.pendingposts.store');
        Route::get('pending-posts/{id}/edit', [PendingPostController::class, 'edit'])->name('backend.admin.pendingposts.edit');
        Route::delete('pendingposts/delete/{id}', [PendingPostController::class, 'delete'])->name('backend.admin.pendingposts.delete');

        Route::post('pending-posts/approve/{id}', [PendingPostController::class, 'approve'])->name('backend.admin.pendingposts.approve');
        Route::get('/pending-posts/count', [AuthorPostsController::class, 'pendingPostsCount']);

        Route::post('pendingposts/deny/{id}', [PendingPostController::class, 'denyPost'])->name('backend.admin.pendingposts.deny');

        // Tags
        Route::get('tags', [TagController::class, 'index'])->name('backend.admin.tags.index');
        Route::post('tags/store', [TagController::class, 'store'])->name('backend.admin.tags.store');
        Route::get('tags/{id}/edit', [TagController::class, 'edit'])->name('backend.admin.tags.edit');
        Route::delete('tags/delete/{id}', [TagController::class, 'delete'])->name('backend.admin.tags.delete');

        // Website Slider
        Route::get('sliders', [SliderController::class, 'index'])->name('backend.admin.sliders.index');
        Route::post('sliders/store', [SliderController::class, 'store'])->name('backend.admin.sliders.store');
        Route::get('sliders/{id}/edit', [SliderController::class, 'edit'])->name('backend.admin.sliders.edit');
        Route::post('sliders/update/{id}', [SliderController::class, 'update'])->name('backend.admin.sliders.update');
        Route::delete('sliders/delete/{id}', [SliderController::class, 'delete'])->name('backend.admin.sliders.delete');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('backend.admin.settings.index');
        Route::get('settings/edit/{id}', [SettingController::class, 'edit'])->name('backend.admin.settings.edit');
        Route::put('settings/update/{id}', [SettingController::class, 'update'])->name('backend.admin.settings.update');
        Route::get('settings/data', [SettingController::class, 'getSettings'])->name('backend.admin.settings.data');

        // About
        Route::get('/abouts', [AboutController::class, 'index'])->name('backend.admin.abouts.index');
        Route::get('abouts/edit/{id}', [AboutController::class, 'edit'])->name('backend.admin.abouts.edit');
        Route::put('abouts/update/{id}', [AboutController::class, 'update'])->name('backend.admin.abouts.update');
        Route::get('abouts/data', [AboutController::class, 'getSettings'])->name('backend.admin.abouts.data');

        // Contact slider
        Route::get('contacts', [ContactController::class, 'index'])->name('backend.admin.contacts.index');
        Route::post('contacts/store', [ContactController::class, 'store'])->name('backend.admin.contacts.store');
        Route::get('contacts/{id}/edit', [ContactController::class, 'edit'])->name('backend.admin.contacts.edit');
        Route::post('contacts/update/{id}', [ContactController::class, 'update'])->name('backend.admin.contacts.update');
        Route::delete('contacts/delete/{id}', [ContactController::class, 'delete'])->name('backend.admin.contacts.delete');

        // Newslatters
        Route::get('/newsletters', [NewsletterController::class, 'index'])->name('backend.admin.newsletters.index');
        Route::delete('/newsletters/{id}', [NewsletterController::class, 'destroy'])->name('backend.admin.newsletters.destroy');

        // contact form
        Route::get('/contactform', [ContactFormController::class, 'index'])->name('backend.admin.contacts.indexform');
        Route::delete('/contactform/{id}', [ContactFormController::class, 'destroy'])->name('backend.admin.contacts.destroy');

        // Comments
        Route::get('/comments', [PendingCommentController::class, 'index'])->name('backend.admin.comments.index');
        Route::post('comments/approve/{id}', [PendingCommentController::class, 'approve'])->name('backend.admin.comments.approve');
        Route::delete('/comments/{id}', [PendingCommentController::class, 'destroy'])->name('backend.admin.comments.destroy');

        // Visitor
        Route::get('/visitors', [VisitorsController::class, 'index'])->name('backend.admin.visitors.index');
        Route::delete('/visitors/{id}', [VisitorsController::class, 'destroy'])->name('backend.admin.visitors.destroy');

        Route::get('sub_admins', [SubAdminController::class, 'index'])->name('backend.admin.sub_admins.index');
        Route::post('sub_admins/store', [SubAdminController::class, 'store'])->name('backend.admin.sub_admins.store');
        Route::get('sub_admins/{id}/edit', [SubAdminController::class, 'edit'])->name('backend.admin.sub_admins.edit');
        Route::delete('sub_admins/delete/{id}', [SubAdminController::class, 'delete'])->name('backend.admin.sub_admins.delete');

        // Admin Users
        Route::get('users', [UsersController::class, 'index'])->name('backend.admin.users.index');
        Route::post('users/store', [UsersController::class, 'store'])->name('backend.admin.users.store');
        Route::get('users/{id}/edit', [UsersController::class, 'edit'])->name('backend.admin.users.edit');
        Route::delete('users/delete/{id}', [UsersController::class, 'delete'])->name('backend.admin.users.delete');

        // Reports
        Route::get('/posts/post-report', [PostReportController::class, 'index'])->name('backend.admin.posts.report');
        Route::get('/posts-report/export-pdf', [PostReportController::class, 'exportPDF'])->name('backend.admin.posts.export.pdf');

        Route::get('/categories/category-report', [CategoryReportController::class, 'index'])->name('backend.admin.categories.report');
        Route::get('/admin/categories/export-pdf', [CategoryReportController::class, 'exportPDF'])->name('backend.admin.categories.export_pdf');

        Route::get('/categories/top-viewed-posts', [CategoryReportController::class, 'topViewedPostsReport'])
            ->name('backend.admin.top_viewed_posts');
        Route::get('/categories/top-viewed-posts/export-pdf', [CategoryReportController::class, 'exportTopViewedPostsPDF'])
            ->name('backend.admin.categories.top_viewed_posts.export_pdf');

        // Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('backend.admin.roles.index');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('backend.admin.roles.store');
        Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('backend.admin.roles.edit');
        Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('backend.admin.roles.update');
        Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('backend.admin.roles.destroy');
    });
});


Route::prefix('author')->group(function () {
    Route::middleware(['auth', 'role:author'])->group(function () {

        Route::get('/dashboard', [AuthorController::class, 'index'])->name('dashboard');
        Route::get('posts', [AuthorPostsController::class, 'index'])->name('backend.author.posts.index');

        Route::post('posts/store', [AuthorPostsController::class, 'store'])->name('backend.author.posts.store');
        Route::get('posts/{id}/edit', [AuthorPostsController::class, 'edit'])->name('backend.author.posts.edit');
        Route::delete('posts/delete/{id}', [AuthorPostsController::class, 'delete'])->name('backend.author.posts.delete');

        Route::get('post/{slug}/{id}', [AuthorPostsController::class, 'show'])->name('backend.author.post.show');

        Route::get('posts/getReason', [AuthorPostsController::class, 'getReason'])->name('backend.author.posts.getReason');

        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.markAsRead');
        Route::post('/posts/{postId}/reject', [PendingPostController::class, 'rejectPost'])->name('posts.reject');
        Route::get('/posts/{post}/edit', [PendingPostController::class, 'show'])->name('posts.show');
    });
});
Route::middleware(['auth', 'role:subscriber'])->group(function () {
    Route::get('/subscriber', [SubscriberController::class, 'index'])->name('subscriber.dashboard');
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');






Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');





Auth::routes();

Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact')->middleware('log.visitor');
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about')->middleware('log.visitor');
Route::get('/blog', [HomeController::class, 'blog'])->name(name: 'frontend.blog')->middleware('log.visitor');

Route::get('/', [HomeController::class, 'home'])->middleware('log.visitor');


Route::post('/subscribe-newsletter', [HomeController::class, 'subscribeNewsletter'])->name('subscribe.newsletter');
Route::post('/contact-submit', [HomeController::class, 'submitContactForm'])->name('contact.submit');
Route::get('/contact-sliders', [HomeController::class, 'contactSliders'])->name('contact.sliders');

Route::get('/header', [HomeController::class, 'settingsPage'])->name('frontend.layouts.header');

Route::get('/post', [HomeController::class, 'showFourthLastPost'])->name('home');

Route::get('/post/{slug}/{id}', [HomeController::class, 'show'])->name(name: 'post.show')->middleware('log.visitor');

Route::get('/tags/{slug}/posts', [TagController::class, 'showPosts'])->name('tag.posts')->middleware('log.visitor');

Route::get('/blog/{slug}/{id}', [HomeController::class, 'show'])->name('blog.show');


Route::post('/comments/store', [CommentController::class, 'store'])->name('comments.store');





Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('backend.admin.dashboard');

    Route::get('posts', [AdminPostController::class, 'index'])
        ->middleware('permission:view posts')->name('backend.admin.posts.index');

    Route::post('posts/store', [AdminPostController::class, 'store'])
        ->middleware('permission:create posts')->name('backend.admin.posts.store');

    Route::get('posts/{id}/edit', [AdminPostController::class, 'edit'])
        ->middleware('permission:edit posts')->name('backend.admin.posts.edit');

    Route::delete('posts/delete/{id}', [AdminPostController::class, 'delete'])
        ->middleware('permission:delete posts')->name('backend.admin.posts.delete');
});
