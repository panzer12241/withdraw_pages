<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
 */

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// ----- Auth Routes -----
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function () {
    $data = request()->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    if (auth()->attempt(['username' => $data['username'], 'password' => $data['password']])) {
        request()->session()->regenerate();
        return redirect()->route('withdraw.index');
    }

    return back()->withErrors(['username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'])->onlyInput('username');
})->name('login.post')->middleware('guest');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// ----- Withdraw Routes (require auth) -----
Route::middleware('auth')->group(function () {
    Route::get('/withdraw', function () {
        return view('withdraw.index');
    })->name('withdraw.index');

    Route::post('/withdraw', function () {
        request()->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'bank' => ['required', 'string'],
            'account_number' => ['required', 'string', 'min:10', 'max:15'],
            'account_name' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        // TODO: implement actual withdraw logic via WithdrawTranferController
        return back()->with('success', 'ส่งคำขอถอนเงิน ฿' . number_format(request('amount'), 2) . ' เรียบร้อยแล้ว กำลังดำเนินการ...');
    })->name('withdraw.store');
});
