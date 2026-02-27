<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use Carbon\Carbon;
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
}
