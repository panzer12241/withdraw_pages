@extends('layouts.app')
@section('title', 'ถอนเงิน')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Top Navigation Bar --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">{{ config('app.name', 'MyApp') }}</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="hidden sm:block text-sm text-gray-500">
                    สวัสดี, <span class="font-medium text-gray-800">{{ auth()->user()->name ?? 'ผู้ใช้งาน' }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        ออกจากระบบ
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">ถอนเงิน</h1>
            <p class="text-sm text-gray-500 mt-1">โอนเงินออกจากบัญชีของคุณไปยังบัญชีธนาคาร</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left column: Balance + Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Balance Card --}}
                <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white p-6 shadow-md">
                    <p class="text-indigo-200 text-sm font-medium mb-1">ยอดเงินคงเหลือ</p>
                    <p class="text-4xl font-bold tracking-tight">฿50,000.00</p>
                    <div class="mt-4 pt-4 border-t border-indigo-500/50 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="text-indigo-200 text-sm">บัญชี: <span class="text-white font-medium">xxx-x-x1234-x</span></span>
                    </div>
                </div>

                {{-- Withdraw Form --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-5">รายละเอียดการถอนเงิน</h2>

                    {{-- Success alert --}}
                    @if (session('success'))
                        <div class="mb-5 flex items-start gap-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Error alert --}}
                    @if ($errors->any())
                        <div class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <ul class="text-sm text-red-600 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('withdraw.store') }}" class="space-y-5">
                        @csrf

                        {{-- Amount --}}
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">
                                จำนวนเงิน (บาท) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">฿</span>
                                <input
                                    id="amount"
                                    type="number"
                                    name="amount"
                                    value="{{ old('amount') }}"
                                    min="1"
                                    step="0.01"
                                    placeholder="0.00"
                                    required
                                    class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-900
                                           placeholder-gray-400 bg-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                           transition duration-150
                                           @error('amount') border-red-400 @enderror"
                                >
                            </div>
                            {{-- Quick amount buttons --}}
                            <div class="flex gap-2 mt-2 flex-wrap">
                                @foreach ([500, 1000, 2000, 5000] as $preset)
                                    <button type="button" onclick="setAmount({{ $preset }})"
                                        class="text-xs px-3 py-1 rounded-lg border border-gray-200 text-gray-600
                                               hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
                                        ฿{{ number_format($preset) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Bank --}}
                        <div>
                            <label for="bank" class="block text-sm font-medium text-gray-700 mb-1.5">
                                ธนาคารปลายทาง <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="bank"
                                name="bank"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-900
                                       bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition duration-150
                                       @error('bank') border-red-400 @enderror"
                            >
                                <option value="" disabled {{ old('bank') ? '' : 'selected' }}>-- เลือกธนาคาร --</option>
                                <option value="kbank"  {{ old('bank') === 'kbank'  ? 'selected' : '' }}>ธนาคารกสิกรไทย (KBANK)</option>
                                <option value="scb"    {{ old('bank') === 'scb'    ? 'selected' : '' }}>ธนาคารไทยพาณิชย์ (SCB)</option>
                                <option value="bbl"    {{ old('bank') === 'bbl'    ? 'selected' : '' }}>ธนาคารกรุงเทพ (BBL)</option>
                                <option value="ktb"    {{ old('bank') === 'ktb'    ? 'selected' : '' }}>ธนาคารกรุงไทย (KTB)</option>
                                <option value="bay"    {{ old('bank') === 'bay'    ? 'selected' : '' }}>ธนาคารกรุงศรีอยุธยา (BAY)</option>
                                <option value="ttb"    {{ old('bank') === 'ttb'    ? 'selected' : '' }}>ธนาคารทีทีบี (TTB)</option>
                                <option value="gsb"    {{ old('bank') === 'gsb'    ? 'selected' : '' }}>ธนาคารออมสิน (GSB)</option>
                                <option value="baac"   {{ old('bank') === 'baac'   ? 'selected' : '' }}>ธ.ก.ส. (BAAC)</option>
                            </select>
                        </div>

                        {{-- Account Number --}}
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1.5">
                                เลขที่บัญชีปลายทาง <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="account_number"
                                type="text"
                                name="account_number"
                                value="{{ old('account_number') }}"
                                maxlength="15"
                                placeholder="กรอกเลขที่บัญชี 10-15 หลัก"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-900
                                       placeholder-gray-400 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition duration-150
                                       @error('account_number') border-red-400 @enderror"
                            >
                        </div>

                        {{-- Account Name --}}
                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                ชื่อเจ้าของบัญชี <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="account_name"
                                type="text"
                                name="account_name"
                                value="{{ old('account_name') }}"
                                placeholder="ชื่อ-นามสกุล ตามบัญชีธนาคาร"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-900
                                       placeholder-gray-400 bg-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition duration-150
                                       @error('account_name') border-red-400 @enderror"
                            >
                        </div>

                        {{-- Note --}}
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-1.5">
                                หมายเหตุ
                                <span class="text-gray-400 font-normal">(ไม่จำเป็น)</span>
                            </label>
                            <textarea
                                id="note"
                                name="note"
                                rows="2"
                                placeholder="ระบุหมายเหตุเพิ่มเติม..."
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-900
                                       placeholder-gray-400 bg-white resize-none
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition duration-150"
                            >{{ old('note') }}</textarea>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-between text-sm mb-4">
                                <span class="text-gray-500">ค่าธรรมเนียม</span>
                                <span class="text-gray-700 font-medium">ฟรี</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">จำนวนที่จะได้รับ</span>
                                <span id="receive-amount" class="text-indigo-600 font-semibold text-base">฿0.00</span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700
                                   text-white text-sm font-semibold py-3 px-4 rounded-xl
                                   transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                                   active:scale-95 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            ยืนยันการถอนเงิน
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right column: Recent Transactions --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sticky top-24">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">ประวัติการถอนล่าสุด</h2>
                    <div class="space-y-3">

                        @php
                            $transactions = [
                                ['date' => '27 ก.พ. 2569', 'bank' => 'KBANK', 'amount' => '1,500.00', 'status' => 'success'],
                                ['date' => '25 ก.พ. 2569', 'bank' => 'SCB',   'amount' => '3,000.00', 'status' => 'success'],
                                ['date' => '20 ก.พ. 2569', 'bank' => 'BBL',   'amount' => '500.00',   'status' => 'pending'],
                                ['date' => '15 ก.พ. 2569', 'bank' => 'KTB',   'amount' => '2,200.00', 'status' => 'failed'],
                            ];
                        @endphp

                        @foreach ($transactions as $tx)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full
                                        {{ $tx['status'] === 'success' ? 'bg-green-100' : ($tx['status'] === 'pending' ? 'bg-yellow-100' : 'bg-red-100') }}
                                        flex items-center justify-center shrink-0">
                                        @if ($tx['status'] === 'success')
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif ($tx['status'] === 'pending')
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $tx['bank'] }}</p>
                                        <p class="text-xs text-gray-400">{{ $tx['date'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-800">฿{{ $tx['amount'] }}</p>
                                    <span class="text-xs
                                        {{ $tx['status'] === 'success' ? 'text-green-600' : ($tx['status'] === 'pending' ? 'text-yellow-600' : 'text-red-500') }}">
                                        {{ $tx['status'] === 'success' ? 'สำเร็จ' : ($tx['status'] === 'pending' ? 'รอดำเนินการ' : 'ไม่สำเร็จ') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    const amountInput = document.getElementById('amount');
    const receiveEl  = document.getElementById('receive-amount');

    function formatTHB(val) {
        return '฿' + parseFloat(val || 0).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function setAmount(val) {
        amountInput.value = val;
        receiveEl.textContent = formatTHB(val);
    }

    amountInput.addEventListener('input', function () {
        receiveEl.textContent = formatTHB(this.value);
    });
</script>
@endsection
