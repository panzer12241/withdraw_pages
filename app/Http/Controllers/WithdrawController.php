<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class WithdrawController extends Controller
{
    public function index(): Response
    {
        // TODO: Replace mock data with real DB queries
        // e.g. WithdrawTransaction::where('status', 'pending')->get()
        $pendingItems = [
            ['id' => 1001, 'date' => '27 ก.พ. 2569 09:12', 'name' => 'สมชาย ใจดี', 'bank' => 'KBANK', 'account' => '012-3-45678-9', 'amount' => 2500.00],
            ['id' => 1002, 'date' => '27 ก.พ. 2569 10:05', 'name' => 'นภา รักสงบ', 'bank' => 'SCB', 'account' => '123-4-56789-0', 'amount' => 1000.00],
            ['id' => 1003, 'date' => '27 ก.พ. 2569 11:30', 'name' => 'วิชัย มีทรัพย์', 'bank' => 'BBL', 'account' => '234-5-67890-1', 'amount' => 5000.00],
        ];

        $autoItems = [
            ['id' => 1004, 'date' => '27 ก.พ. 2569 08:00', 'name' => 'ประภา สุขสันต์', 'bank' => 'KTB', 'account' => '345-6-78901-2', 'amount' => 3200.00],
            ['id' => 1005, 'date' => '27 ก.พ. 2569 08:45', 'name' => 'อนันต์ ดีเลิศ', 'bank' => 'BAY', 'account' => '456-7-89012-3', 'amount' => 750.00],
        ];

        return Inertia::render('Withdraw/Index', [
            'pendingItems' => $pendingItems,
            'autoItems' => $autoItems,
        ]);
    }
}
