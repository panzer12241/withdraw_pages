<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WithdrawController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('Withdraw/Index');
    }

    public function api()
    {

        $connections = $this->getPrefixs();
        $withdraws = [];

        foreach ($connections as $connection) {
            $withdraws[$connection['connection']] = Withdraw::on($connection['connection'])
                ->whereBetween('created_at', [Carbon::now()->subDays(2)->startOfDay(), Carbon::now()->endOfDay()])
                ->whereIn('prefix', $connection['prefixs'])
                ->whereNotIn('status', ['สำเร็จ', 'ยกเลิก'])
                ->select('id', 'prefix', 'amount', 'customer_data', 'status', 'message', 'created_at')
                ->get()
                ->map(fn($item) => [
                    'id' => $item->id,
                    'connection' => $connection['connection'],
                    'prefix' => $item->prefix,
                    'name' => $item->customer_data['name'] ?? null,
                    'account_number' => $item->customer_data['account_number'] ?? null,
                    'bank_name_eng' => $item->customer_data['bank_name_eng'] ?? null,
                    'bank_name_th' => $item->customer_data['bank_name_th'] ?? null,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'message' => $item->message,
                    'created_at' => $item->created_at,
                ]);
        }

        // Flatten all connection groups into one collection
        $allItems = collect($withdraws)->collapse();

        // status ที่ต้องตรวจสอบด้วยมือ → Section 1
        $pendingStatuses = ['รอตรวจสอบ', 'ผิดพลาด'];

        [$pendingItems, $autoItems] = $allItems->partition(
            fn($item) => in_array($item['status'], $pendingStatuses)
        );

        return response()->json([
            'pendingItems' => $pendingItems->values(),
            'autoItems' => $autoItems->values(),
        ]);
    }

    public function autoWithdraw(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'connection' => 'required|string',
            'prefix' => 'required|string',
        ]);

        $withdraw = Withdraw::on($request->connection)->findOrFail($request->id);
        $withdraw->update([
            'status' => 'รอโอนเงิน',
            'message' => null,
            'user_id' => Auth::id(),
            'user_data' => [
                'username' => Auth::user()->username,
                'name' => Auth::user()->name,
                'ip' => request()->ip(),
                'last_time_login' => null,
            ],
        ]);

        return response()->json(['message' => 'ส่งถอนออโต้เรียบร้อย']);
    }

    public function manualWithdraw(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'connection' => 'required|string',
            'prefix' => 'required|string',
        ]);

        $withdraw = Withdraw::on($request->connection)->findOrFail($request->id);
        $withdraw->update([
            'status' => 'สำเร็จ',
            'message' => 'โอนสำเร็จโดย กดถอนมือ',
            'tranfer_by' => 'โอนสำเร็จโดย กดถอนมือ',
            'bank_id' => null,
            'bank_data' => null,
            'user_id' => Auth::id(),
            'user_data' => [
                'username' => Auth::user()->username,
                'name' => Auth::user()->name,
                'ip' => request()->ip(),
                'last_time_login' => null,
            ],
        ]);

        return response()->json(['message' => 'ปรับสถานะสำเร็จเรียบร้อย']);
    }

    public function cancelWithdraw(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'connection' => 'required|string',
            'prefix' => 'required|string',
            'section' => 'nullable|string',
        ]);

        $withdraw = Withdraw::on($request->connection)->findOrFail($request->id);

        // ยกเลิกจาก section auto → ส่งกลับไปรอตรวจสอบ
        $newStatus = $request->section === 'auto' ? 'รอตรวจสอบ' : 'ยกเลิก';
        $withdraw->update(['status' => $newStatus]);

        $message = $request->section === 'auto'
        ? 'ส่งกลับรอตรวจสอบเรียบร้อย'
        : 'ยกเลิกรายการเรียบร้อย';

        return response()->json(['message' => $message]);
    }
}
