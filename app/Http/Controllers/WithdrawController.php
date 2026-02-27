<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawRequest;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WithdrawController extends Controller
{
    public function index(): View
    {
        $getPrefixs = $this->getPrefixs();

        foreach ($getPrefixs as $key => $getPrefix) {
            $withdraws[] = Withdraw::on($getPrefix['connection'])
                ->whereBetween('created_at', [Carbon::now()->subDays(2)->startOfDay(), Carbon::now()->endOfDay()])
                ->whereIn('prefix', $getPrefix['prefixs'])
                ->whereNotIn('status', [
                    'สำเร็จ',
                    'ยกเลิก',
                ])
                ->get();
        }

        return view('withdraw.index', compact('withdraws'));
    }

    public function store(WithdrawRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // TODO: implement actual withdraw logic (e.g. via job/service)

        return back()->with(
            'success',
            'ส่งคำขอถอนเงิน ฿' . number_format($validated['amount'], 2) . ' เรียบร้อยแล้ว กำลังดำเนินการ...'
        );
    }
}
