<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Breeze-профиль (редактирование своего)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Книги
    Route::resource('books', BookController::class);
    Route::post('books/{book}/bookmark', [BookController::class, 'bookmark'])
        ->name('books.bookmark');

    // Мои друзья (важно: ВЫШЕ /users/{user}, иначе Laravel перепутает)
    Route::get('/friends', [UserController::class, 'friends'])->name('users.friends');

    // Список всех пользователей
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Публичный профиль и его подстраницы
    Route::get('/users/{user}/books',     [UserProfileController::class, 'books'])->name('user.books');
    Route::get('/users/{user}/followers', [UserProfileController::class, 'followers'])->name('user.followers');
    Route::get('/users/{user}/following', [UserProfileController::class, 'following'])->name('user.following');
    Route::get('/users/{user}',           [UserProfileController::class, 'show'])->name('user.profile');

    // Подписки
    Route::post('/users/{user}/follow',     [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/users/{user}/follow',   [FollowController::class, 'destroy'])->name('follow.destroy');
    Route::delete('/users/{user}/unfriend', [FollowController::class, 'unfriend'])->name('follow.unfriend');

    // Отзывы
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/books/{book}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Комментарии
    Route::post('/reviews/{review}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Лайки
    Route::post('/reviews/{review}/like', [LikeController::class, 'toggleReview'])->name('likes.review');
    Route::post('/comments/{comment}/like', [LikeController::class, 'toggleComment'])->name('likes.comment');

    // Уведомления
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/open', [NotificationController::class, 'open'])->name('notifications.open');
});

require __DIR__ . '/auth.php';