<template>
    <AppLayout>
        <Head title="ถอนเงิน" />

        <!-- Section 1: รายการที่รออนุมัติ -->
        <section>
            <div class="flex items-center gap-2 mb-3">
                <span class="w-1 h-5 rounded-full bg-yellow-400 inline-block"></span>
                <h2 class="text-base font-semibold text-gray-800">รายการที่รออนุมัติ</h2>
                <span class="text-xs font-medium bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">
                    {{ pendingItems.length }} รายการ
                </span>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">วันที่ / เวลา</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">ชื่อบัญชี</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">ธนาคาร</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">เลขบัญชี</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">จำนวนเงิน</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template v-if="pendingItems.length > 0">
                            <tr
                                v-for="item in pendingItems"
                                :key="item.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">#{{ item.id }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ item.date }}</td>
                                <td class="px-5 py-3.5 font-medium text-gray-800">{{ item.name }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-block bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-0.5 rounded">
                                        {{ item.bank }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-gray-600">{{ item.account }}</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-gray-900">
                                    ฿{{ formatMoney(item.amount) }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse inline-block"></span>
                                        รออนุมัติ
                                    </span>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-400 text-sm">ไม่มีรายการที่รออนุมัติ</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="5" class="px-5 py-3 text-xs font-semibold text-gray-500 text-right">รวมทั้งหมด</td>
                            <td class="px-5 py-3 text-right font-bold text-gray-900">
                                ฿{{ formatMoney(pendingTotal) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
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
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">#</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">วันที่ / เวลา</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">ชื่อบัญชี</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">ธนาคาร</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">เลขบัญชี</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">จำนวนเงิน</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-blue-600 uppercase tracking-wide">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template v-if="autoItems.length > 0">
                            <tr
                                v-for="item in autoItems"
                                :key="item.id"
                                class="hover:bg-blue-50/40 transition"
                            >
                                <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">#{{ item.id }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ item.date }}</td>
                                <td class="px-5 py-3.5 font-medium text-gray-800">{{ item.name }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded">
                                        {{ item.bank }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-gray-600">{{ item.account }}</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-gray-900">
                                    ฿{{ formatMoney(item.amount) }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse inline-block"></span>
                                        รอออโต้
                                    </span>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-400 text-sm">ไม่มีรายการที่รอระบบออโต้</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-blue-50 border-t border-blue-100">
                        <tr>
                            <td colspan="5" class="px-5 py-3 text-xs font-semibold text-blue-600 text-right">รวมทั้งหมด</td>
                            <td class="px-5 py-3 text-right font-bold text-gray-900">
                                ฿{{ formatMoney(autoTotal) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    pendingItems: {
        type: Array,
        default: () => [],
    },
    autoItems: {
        type: Array,
        default: () => [],
    },
});

const pendingTotal = computed(() =>
    props.pendingItems.reduce((sum, item) => sum + parseFloat(item.amount), 0)
);

const autoTotal = computed(() =>
    props.autoItems.reduce((sum, item) => sum + parseFloat(item.amount), 0)
);

function formatMoney(value) {
    return Number(value).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>
