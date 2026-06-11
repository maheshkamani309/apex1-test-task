<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../../composables/useAuth'
import AppLayout from '../../Layouts/AppLayout.vue'

const { token } = useAuth()

const taskId = ref(window.location.pathname.split('/')[2])
const task = ref(null)
const loading = ref(true)
const error = ref('')
const deleting = ref(false)

const statusColors = {
    pending: 'bg-yellow-100 text-yellow-800',
    in_progress: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
}

const fetchTask = async () => {
    try {
        const response = await fetch(`/api/tasks/${taskId.value}`, {
            headers: {
                'Authorization': `Bearer ${token.value}`,
                'Content-Type': 'application/json',
            }
        })
        const data = await response.json()

        if (data.success) {
            task.value = data.task
        } else {
            error.value = 'Task not found'
        }
    } catch (err) {
        error.value = 'Failed to load task'
        console.error(err)
    } finally {
        loading.value = false
    }
}

const deleteTask = async () => {
    if (!confirm('Are you sure you want to delete this task?')) return

    deleting.value = true
    try {
        const response = await fetch(`/api/tasks/${taskId.value}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token.value}`,
                'Content-Type': 'application/json',
            }
        })

        const data = await response.json()

        if (data.success) {
            window.location.href = '/tasks'
        } else {
            error.value = data.message || 'Failed to delete task'
            deleting.value = false
        }
    } catch (err) {
        error.value = 'Failed to delete task'
        console.error(err)
        deleting.value = false
    }
}

onMounted(() => {
    if (!token.value) {
        window.location.href = '/login'
        return
    }
    fetchTask()
})
</script>

<template>
    <AppLayout>
            <div v-if="error" class="mb-6 rounded-md bg-red-50 p-4">
                <p class="text-sm font-medium text-red-800">{{ error }}</p>
            </div>

            <div v-if="loading" class="rounded-lg bg-white p-8 text-center">
                <p class="text-gray-600">Loading task...</p>
            </div>

            <div v-else-if="task" class="space-y-6">
                <div class="mb-8 flex items-center justify-between">
                    <h1 class="mb-8 text-3xl text-primary font-semibold ">{{ task.title }}</h1>
                    <div class="flex gap-3">
                        <a :href="`/tasks/${taskId}/edit`"
                            class="text-sm text-white font-medium bg-primary p-2 px-4 rounded opacity-90 hover:opacity-100">
                            Edit
                        </a>
                        <button @click="deleteTask" :disabled="deleting"
                            class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                            {{ deleting ? 'Deleting...' : 'Delete' }}
                        </button>
                    </div>
                </div>

                <div class="rounded-lg bg-white shadow">
                    <div class="px-4 py-6 sm:p-6">
                        <!-- Status -->
                        <div class="mb-6 border-b pb-6">
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-2">
                                <span
                                    :class="[statusColors[task.status], 'capitalize inline-flex items-center rounded-full px-3 py-1 text-sm font-medium']">
                                    {{ task.status.replace('_', ' ') }}
                                </span>
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6 border-b pb-6">
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="mt-2 text-gray-900">
                                {{ task.description || '' }}
                            </p>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-6 border-b pb-6">
                            <h3 class="text-sm font-medium text-gray-500">Due Date</h3>
                            <p class="mt-2 text-gray-900">
                                {{ new Date(task.due_date).toLocaleDateString('en-US', {
                                    year: 'numeric', month: 'long',
                                day: 'numeric' }) }}
                            </p>
                        </div>

                        <!-- Created At -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ new Date(task.created_at).toLocaleString('en-US', {
                                    year: 'numeric', month: 'short',
                                    day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="/tasks" class="text-sm text-white font-medium bg-primary p-2 px-4 rounded opacity-90 hover:opacity-100">← Back to Tasks</a>
                </div>
            </div>
    </AppLayout>
</template>
