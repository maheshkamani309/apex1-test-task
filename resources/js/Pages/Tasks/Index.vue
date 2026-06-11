<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../../composables/useAuth'
import AppLayout from '@/Layouts/AppLayout.vue'
import Heading from '../../components/Heading.vue'

const { token } = useAuth()
const tasks = ref([])
const loading = ref(false)
const deleting = ref(null)
const error = ref('')
const currentPage = ref(1)
const pagination = ref({})
const filterStatus = ref('')
const sortOrder = ref('asc')

const statusColors = {
    pending: 'bg-yellow-100 text-yellow-800',
    in_progress: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
}

const fetchTasks = async () => {
    loading.value = true
    try {
        const params = new URLSearchParams()
        params.append('page', currentPage.value)

        if (filterStatus.value) {
            params.append('status', filterStatus.value)
        }

        if (sortOrder.value) {
            params.append('sort', sortOrder.value)
        }

        const response = await fetch(`/api/tasks?${params.toString()}`, {
            headers: {
                'Authorization': `Bearer ${token.value}`,
                'Content-Type': 'application/json',
            }
        })
        const data = await response.json()
        if (data.success) {
            tasks.value = data.tasks
            pagination.value = data.pagination
        } else {
            tasks.value = []
        }
    } catch (err) {
        error.value = 'Failed to load tasks'
        console.error(err)
    } finally {
        loading.value = false
    }
}

const resetFilters = () => {
    filterStatus.value = ''
    sortOrder.value = 'asc'
    currentPage.value = 1
    fetchTasks()
}

const handleFilterChange = () => {
    currentPage.value = 1
    fetchTasks()
}

const deleteTask = async (taskId) => {
    if (!confirm('Are you sure you want to delete this task?')) return

    deleting.value = taskId
    try {
        const response = await fetch(`/api/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token.value}`,
                'Content-Type': 'application/json',
            }
        })
        const data = await response.json()

        if (data.success) {
            await fetchTasks()
        } else {
            error.value = data.message || 'Failed to delete task'
        }
    } catch (err) {
        error.value = 'Failed to delete task'
        console.error(err)
    } finally {
        deleting.value = null
    }
}

onMounted(() => {
    if (!token.value) {
        window.location.href = '/login'
        return
    }
    fetchTasks()
})
</script>

<template>
    <AppLayout>

        <div v-if="error" class="mb-6 rounded-md bg-red-50 p-4">
            <p class="text-sm font-medium text-red-800">{{ error }}</p>
            <button @click="error = ''" class="mt-2 text-sm text-red-600 hover:text-red-800">Dismiss</button>
        </div>
        <div class="flex flex-col gap-2 h-full">
            <Heading>Tasks</Heading>
            <!-- Filters and Sorting -->
            <div class="mb-6 rounded-lg bg-white p-4 shadow">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-900">Filter by
                            Status</label>
                        <select id="status-filter" v-model="filterStatus" @change="handleFilterChange"
                            class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort-order" class="block text-sm font-medium text-gray-900">Sort by Due Date</label>
                        <select id="sort-order" v-model="sortOrder" @change="handleFilterChange"
                            class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                            <option value="asc">Earliest First</option>
                            <option value="desc">Latest First</option>
                        </select>
                    </div>

                    <button @click="resetFilters"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-50">
                        Reset Filters
                    </button>
                </div>
            </div>

            <!-- Tasks Table -->
            <div v-if="loading" class="rounded-lg bg-white p-8 text-center">
                <p class="text-gray-600">Loading tasks...</p>
            </div>

            <div v-else-if="tasks.length === 0" class="rounded-lg bg-white p-8 text-center">
                <p class="text-gray-600">No tasks found. Create one to get started!</p>
            </div>

            <div v-else class="overflow-x-auto rounded-lg bg-[#F5F8FF] shadow">
                <table class="w-full">
                    <thead class="border-b bg-primary">
                        <tr class="text-inter text-white text-left text-xs font-medium uppercase">
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Due Date</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="task in tasks" :key="task.id" class="bg-white hover:bg-white-400">
                            <td class="px-6 py-4 text-sm text-secondary">
                                <a :href="`/tasks/${task.id}`" class="font-medium">
                                    {{ task.title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span
                                    :class="[statusColors[task.status], 'inline-block rounded px-3 py-1 text-xs font-medium capitalize']">
                                    {{ task.status.replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ new Date(task.due_date).toLocaleDateString() }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-3">
                                    <a :href="`/tasks/${task.id}/edit`"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        Edit
                                    </a>
                                    <button @click="deleteTask(task.id)" :disabled="deleting === task.id"
                                        class="text-primary hover:text-red-800 font-medium disabled:opacity-50">
                                        {{ deleting === task.id ? 'Deleting...' : 'Delete' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="pagination.last_page && pagination.last_page > 1" class="mt-6 flex items-center justify-between">
                <button @click="currentPage--; fetchTasks()" :disabled="currentPage === 1"
                    class="rounded-md border border-primary px-4 py-2 text-sm font-medium  cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <span class="text-sm text-gray-600">
                    Page {{ pagination.current_page }} of {{ pagination.last_page }}
                </span>
                <button @click="currentPage++; fetchTasks()" :disabled="currentPage === pagination.last_page"
                    class="rounded-md border border-primary px-4 py-2 text-sm font-medium   cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>