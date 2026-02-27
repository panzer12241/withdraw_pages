<template>
    <AppLayout>
        <Head title="ถอนเงิน" />

        <!-- Error banner -->
        <div v-if="error" class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-9.5a1 1 0 10-2 0V10a1 1 0 102 0V8.5zm-1 5a1.25 1.25 0 110-2.5 1.25 1.25 0 010 2.5z" clip-rule="evenodd"/>
            </svg>
            {{ error }}
            <button @click="fetchData" class="ml-auto underline hover:no-underline">ลองใหม่</button>
        </div>

        <!-- Loading skeleton -->
        <template v-if="loading">
            <div v-for="n in 2" :key="n" class="animate-pulse bg-white rounded-2xl border border-gray-200 h-48 mb-6"></div>
        </template>

        <template v-else>

        <!-- Section 1: รายการที่รออนุมัติ (สถานะ: รอการตรวจสอบ / ผิดพลาด) -->
        <section>
            <div class="flex items-center gap-2 mb-3">
                <span class="w-1 h-5 rounded-full bg-yellow-400 inline-block"></span>
                <h2 class="text-base font-semibold text-gray-800">รายการที่รออนุมัติ</h2>
                <span class="text-xs font-medium bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">
                    {{ pendingItems.length }} รายการ
                </span>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">#</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">วันที่ / เวลา</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Prefix</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Connection</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">บัญชี</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">จำนวนเงิน</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">สถานะ</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">หมายเหตุ</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template v-if="pendingItems.length > 0">
                            <tr v-for="item in pendingItems" :key="item.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 text-gray-400 font-mono text-xs whitespace-nowrap">#{{ item.id }}</td>
                                <td class="px-4 py-3.5 text-gray-600 whitespace-nowrap">{{ formatDate(item.created_at) }}</td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded">{{ item.prefix }}</span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="inline-block bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-0.5 rounded uppercase">{{ item.connection }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-medium text-gray-800 whitespace-nowrap">{{ item.name }}</div>
                                    <div class="mt-0.5 flex items-center gap-1.5">
                                        <span class="inline-block bg-gray-100 text-gray-700 text-xs font-semibold px-1.5 py-0.5 rounded">{{ item.bank_name_eng }}</span>
                                        <span class="text-xs text-gray-400">{{ item.bank_name_th }}</span>
                                    </div>
                                    <div class="mt-0.5 font-mono text-xs text-gray-500">{{ item.account_number }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-900 whitespace-nowrap">฿{{ formatMoney(item.amount) }}</td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <span v-if="item.status === 'ผิดพลาด'"
                                        class="inline-flex items-center gap-1 text-xs font-medium bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                        ผิดพลาด
                                    </span>
                                    <span v-else
                                        class="inline-flex items-center gap-1 text-xs font-medium bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse inline-block"></span>
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-gray-600 min-w-[200px]">
                                    <span v-if="item.message">{{ item.message }}</span>
                                    <span v-else class="text-gray-300">—</span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <button @click="copyToClipboard(item)" :title="'คัดลอกข้อมูล'"
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition">
                                            <svg v-if="copiedId !== item.id" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg v-else class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button @click="handleAutoWithdraw(item)"
                                            :disabled="actionLoading === item.id"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            ถอนออโต้
                                        </button>
                                        <button @click="handleManualWithdraw(item)"
                                            :disabled="actionLoading === item.id"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            ปรับสถานะสำเร็จ
                                        </button>
                                        <button @click="handleCancel(item)"
                                            :disabled="actionLoading === item.id"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            ยกเลิก
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="9" class="px-5 py-8 text-center text-gray-400 text-sm">ไม่มีรายการที่รออนุมัติ</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-xs font-semibold text-gray-500 text-right">รวมทั้งหมด</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">฿{{ formatMoney(pendingTotal) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </section>

        <!-- Section 2: รายการที่รอการถอนเงินจากระบบออโต้ -->
        <section>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-blue-400 inline-block"></span>
                    <h2 class="text-base font-semibold text-gray-800">รายการที่รอการถอนเงินจากระบบออโต้</h2>
                    <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                        {{ autoItems.length }} รายการ
                    </span>
                </div>
                <span class="flex items-center gap-1.5 text-xs text-blue-600 font-medium">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    กำลังประมวลผล
                </span>
            </div>

            <div class="bg-white rounded-2xl border border-blue-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">#</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">วันที่ / เวลา</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">Prefix</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">Connection</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">บัญชี</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">จำนวนเงิน</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">คัดลอก</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">สถานะ</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide whitespace-nowrap">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template v-if="autoItems.length > 0">
                            <tr v-for="item in autoItems" :key="item.id" class="hover:bg-blue-50/40 transition">
                                <td class="px-4 py-3.5 text-gray-400 font-mono text-xs whitespace-nowrap">#{{ item.id }}</td>
                                <td class="px-4 py-3.5 text-gray-600 whitespace-nowrap">{{ formatDate(item.created_at) }}</td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded">{{ item.prefix }}</span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="inline-block bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-0.5 rounded uppercase">{{ item.connection }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-medium text-gray-800 whitespace-nowrap">{{ item.name }}</div>
                                    <div class="mt-0.5 flex items-center gap-1.5">
                                        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-1.5 py-0.5 rounded">{{ item.bank_name_eng }}</span>
                                        <span class="text-xs text-gray-400">{{ item.bank_name_th }}</span>
                                    </div>
                                    <div class="mt-0.5 font-mono text-xs text-gray-500">{{ item.account_number }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-900 whitespace-nowrap">฿{{ formatMoney(item.amount) }}</td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <button @click="copyToClipboard(item)" :title="'คัดลอกข้อมูล'"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-blue-400 hover:bg-blue-100 hover:text-blue-700 transition">
                                        <svg v-if="copiedId !== item.id" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <svg v-else class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse inline-block"></span>
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <button @click="handleCancel(item, 'auto')"
                                        :disabled="actionLoading === item.id"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        ยกเลิก
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="9" class="px-5 py-8 text-center text-gray-400 text-sm">ไม่มีรายการที่รอระบบออโต้</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-blue-50 border-t border-blue-100">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-xs font-semibold text-blue-600 text-right">รวมทั้งหมด</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">฿{{ formatMoney(autoTotal) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </section>
        </template><!-- end v-else -->

    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'; // computed used for totals
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from 'sweetalert2';

// ───── Data ─────
const pendingItems = ref([]);
const autoItems    = ref([]);
const loading      = ref(true);
const error        = ref(null);
const actionLoading = ref(null);
const copiedId     = ref(null);
let   pollTimer    = null;

const POLL_INTERVAL = 30_000;

async function fetchData() {
    try {
        const response = await axios.get('/api/withdraw/data');
        // If redirected to login page (session expired), response is HTML not JSON
        if (typeof response.data !== 'object' || response.data === null) {
            window.location.href = '/login';
            return;
        }
        pendingItems.value = response.data.pendingItems ?? [];
        autoItems.value    = response.data.autoItems    ?? [];
        error.value        = null;
    } catch (e) {
        if (e.response?.status === 401 || e.response?.status === 419) {
            window.location.href = '/login';
            return;
        }
        error.value = 'โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchData();
    pollTimer = setInterval(fetchData, POLL_INTERVAL);
});

onUnmounted(() => clearInterval(pollTimer));

// ───── Actions ─────
function copyToClipboard(item) {
    const lines = [
        `Connection: ${item.connection ?? ''}`,
        `Prefix: ${item.prefix ?? ''}`,
        `สถานะ: ${item.status ?? ''}`,
        `หมายเหตุ: ${item.message ?? ''}`,
        `ธนาคาร: ${item.bank_name_eng ?? ''} ${item.bank_name_th ?? ''}`.trim(),
        `เลขบัญชี: ${item.account_number ?? ''}`,
        `ชื่อบัญชี: ${item.name ?? ''}`,
        `จำนวนเงิน: ${formatMoney(item.amount)}`,
    ];
    navigator.clipboard.writeText(lines.join('\n')).then(() => {
        copiedId.value = item.id;
        setTimeout(() => { copiedId.value = null; }, 2000);
    });
}

async function handleAutoWithdraw(item) {
    const result = await Swal.fire({
        title: 'ยืนยันถอนออโต้',
        html: `<div class="text-sm text-left space-y-1">
            <div><span class="font-semibold">#${item.id}</span> &nbsp;${item.prefix} / ${item.connection}</div>
            <div>${item.name}</div>
            <div class="font-semibold text-blue-600">฿${formatMoney(item.amount)}</div>
        </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ถอนออโต้',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#2563eb',
    });
    if (!result.isConfirmed) return;
    actionLoading.value = item.id;
    try {
        await axios.post('/api/withdraw/auto', { id: item.id, connection: item.connection, prefix: item.prefix });
        await fetchData();
        Swal.fire({ title: 'ส่งถอนออโต้เรียบร้อย', icon: 'success', timer: 1500, showConfirmButton: false });
    } catch (e) {
        Swal.fire({ title: 'เกิดข้อผิดพลาด', text: e.response?.data?.message || '', icon: 'error' });
    } finally {
        actionLoading.value = null;
    }
}

async function handleManualWithdraw(item) {
    const result = await Swal.fire({
        title: 'ยืนยันปรับสถานะสำเร็จ',
        html: `<div class="text-sm text-left space-y-1">
            <div><span class="font-semibold">#${item.id}</span> &nbsp;${item.prefix} / ${item.connection}</div>
            <div>${item.name}</div>
            <div class="font-semibold text-green-600">฿${formatMoney(item.amount)}</div>
        </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ปรับสถานะสำเร็จ',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#16a34a',
    });
    if (!result.isConfirmed) return;
    actionLoading.value = item.id;
    try {
        await axios.post('/api/withdraw/manual', { id: item.id, connection: item.connection, prefix: item.prefix });
        await fetchData();
        Swal.fire({ title: 'ปรับสถานะสำเร็จเรียบร้อย', icon: 'success', timer: 1500, showConfirmButton: false });
    } catch (e) {
        Swal.fire({ title: 'เกิดข้อผิดพลาด', text: e.response?.data?.message || '', icon: 'error' });
    } finally {
        actionLoading.value = null;
    }
}

async function handleCancel(item, section = 'pending') {
    const isPending = section !== 'auto';
    const result = await Swal.fire({
        title: isPending ? 'ยืนยันยกเลิกรายการ' : 'ยืนยันยกเลิก (ส่งกลับรอตรวจสอบ)',
        html: `<div class="text-sm text-left space-y-1">
            <div><span class="font-semibold">#${item.id}</span> &nbsp;${item.prefix} / ${item.connection}</div>
            <div>${item.name}</div>
            <div class="font-semibold text-red-600">฿${formatMoney(item.amount)}</div>
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: isPending ? 'ยกเลิกรายการ' : 'ส่งกลับรอตรวจสอบ',
        cancelButtonText: 'ปิด',
        confirmButtonColor: '#dc2626',
    });
    if (!result.isConfirmed) return;
    actionLoading.value = item.id;
    try {
        await axios.post('/api/withdraw/cancel', { id: item.id, connection: item.connection, prefix: item.prefix, section });
        await fetchData();
        Swal.fire({ title: isPending ? 'ยกเลิกรายการเรียบร้อย' : 'ส่งกลับรอตรวจสอบเรียบร้อย', icon: 'success', timer: 1500, showConfirmButton: false });
    } catch (e) {
        Swal.fire({ title: 'เกิดข้อผิดพลาด', text: e.response?.data?.message || '', icon: 'error' });
    } finally {
        actionLoading.value = null;
    }
}

// ───── Totals (of ALL items, not just current page) ─────
const pendingTotal = computed(() =>
    pendingItems.value.reduce((sum, item) => sum + (parseFloat(item?.amount) || 0), 0)
);
const autoTotal = computed(() =>
    autoItems.value.reduce((sum, item) => sum + (parseFloat(item?.amount) || 0), 0)
);

// ───── Helpers ─────
function formatMoney(value) {
    const num = parseFloat(value);
    if (isNaN(num)) return '0.00';
    return num.toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString('th-TH', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: false,
        timeZone: 'Asia/Bangkok',
    });
}
</script>
