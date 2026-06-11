<script setup>
import { Link } from '@inertiajs/vue3'
import { useAuth } from '../composables/useAuth'
const { token, user, logout } = useAuth()
</script>

<template>
    <div class="min-h-screen bg-[#F5F8FF] ">
        <nav class="bg-white shadow px-6 py-4 bg-[#F8F8F8]">
            <div class="container mx-auto ">
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-bold font-inter"> To-Do </div>
                    <div class="flex items-center gap-4">
                        <button @click="async () => {
                            await logout()
                            window.location.href = '/login'
                        }" class="text-sm text-white font-medium bg-primary p-2 px-4 rounded hover:bg-red-500">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <main class="p-6">
            <div class="container mx-auto">
                <div class="flex min-h-[80vh]">
                    <div class="w-1/4 pr-6">
                        <div class="bg-primary h-full rounded-lg shadow p-4 py-8 mb-6 flex flex-col gap-4">
                            <Link
                                class="border-1 p-2 px-4 rounded border-white text-white text-xl font-inter flex gap-2 fill-white hover:fill-primary hover:bg-white hover:text-primary"
                                href="/dashboard">
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3333 8V0H24V8H13.3333ZM0 13.3333V0H10.6667V13.3333H0ZM13.3333 24V10.6667H24V24H13.3333ZM0 24V16H10.6667V24H0Z" />
                                </svg> Dashboard
                            </Link>
                            <Link
                                class="border-1 p-2 px-4 rounded border-white text-white text-xl font-inter  flex gap-2 fill-white hover:fill-primary hover:bg-white hover:text-primary"
                                href="/tasks">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill=""
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5 22H19C20.103 22 21 21.103 21 20V5C21 3.897 20.103 3 19 3H17C17 2.73478 16.8946 2.48043 16.7071 2.29289C16.5196 2.10536 16.2652 2 16 2H8C7.73478 2 7.48043 2.10536 7.29289 2.29289C7.10536 2.48043 7 2.73478 7 3H5C3.897 3 3 3.897 3 5V20C3 21.103 3.897 22 5 22ZM5 5H7V7H17V5H19V20H5V5Z"
                                        fill="" />
                                    <path
                                        d="M11 13.586L9.20697 11.793L7.79297 13.207L11 16.414L16.207 11.207L14.793 9.79297L11 13.586Z"
                                        fill="" />
                                </svg> Tasks
                            </Link>
                            <Link
                                class="border-1 p-2 px-4 rounded border-white text-white text-xl font-inter  flex gap-2 fill-white hover:fill-primary hover:bg-white hover:text-primary"
                                href="/tasks/create">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill=""
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5 22H19C20.103 22 21 21.103 21 20V5C21 3.897 20.103 3 19 3H17C17 2.73478 16.8946 2.48043 16.7071 2.29289C16.5196 2.10536 16.2652 2 16 2H8C7.73478 2 7.48043 2.10536 7.29289 2.29289C7.10536 2.48043 7 2.73478 7 3H5C3.897 3 3 3.897 3 5V20C3 21.103 3.897 22 5 22ZM5 5H7V7H17V5H19V20H5V5Z"
                                        fill="" />
                                    <path
                                        d="M11 13.586L9.20697 11.793L7.79297 13.207L11 16.414L16.207 11.207L14.793 9.79297L11 13.586Z"
                                        fill="" />
                                </svg> New Task
                            </Link>
                        </div>
                    </div>
                    <div class="w-3/4 bg-[#F5F8FF]">
                        <div class="bg-white rounded-lg shadow p-6 h-full">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>