<script setup>
import { ref } from 'vue'
import { useAuth } from '../../composables/useAuth'

const { login, loading } = useAuth()

const email = ref('')
const password = ref('')
const error = ref('')

const handleLogin = async () => {
    error.value = ''

    if (!email.value || !password.value) {
        error.value = 'Email and password are required'
        return
    }

    const result = await login(email.value, password.value)
    
    if (result.success) {
        window.location.href = '/tasks'
    } else {
        error.value = result.message
    }
}
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Sign in to your account</h2>
            </div>

            <div v-if="error" class="rounded-md bg-red-50 p-4">
                <p class="text-sm font-medium text-red-800">{{ error }}</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6 rounded-lg bg-primary p-8 shadow">
                <div>
                    <label for="email" class="block text-sm font-medium text-white">Email address</label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                        placeholder="you@example.com"
                    />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                        placeholder="••••••••"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full rounded-md bg-white px-4 py-2 text-sm font-semibold text-primary hover:bg-gray-100 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>
        </div>
    </div>
</template>
