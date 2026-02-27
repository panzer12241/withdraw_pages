<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-900">MyApp</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="hidden sm:block text-sm text-gray-500">
                        สวัสดี, <span class="font-medium text-gray-800">{{ $page.props.auth?.user?.name ?? 'ผู้ใช้งาน' }}</span>
                    </span>
                    <form @submit.prevent="logout">
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

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3';

const $page = usePage();

function logout() {
    router.post('/logout');
}
</script>
