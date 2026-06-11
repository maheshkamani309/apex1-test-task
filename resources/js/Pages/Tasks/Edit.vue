<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../../composables/useAuth'
import AppLayout from '../../Layouts/AppLayout.vue'
import Heading from '../../components/Heading.vue'

const { token } = useAuth()

const taskId = ref(window.location.pathname.split('/')[2])

const form = ref({
    title: '',
    description: '',
    status: 'pending',
    due_date: '',
})

const loading = ref(true)
const error = ref('')
const success = ref('')
const submitting = ref(false)

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
            form.value = { ...data.task }
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

const submit = async () => {
    error.value = ''
    submitting.value = true

    try {
        const response = await fetch(`/api/tasks/${taskId.value}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token.value}`,
            },
            body: JSON.stringify(form.value),
        })

        const data = await response.json()

        if (data.success) {
            success.value = 'Task updated successfully!'
            setTimeout(() => {
                window.location.href = `/tasks/${taskId.value}`
            }, 1000)
        } else {
            error.value = data.message || 'Failed to update task'
        }
    } catch (err) {
        error.value = 'An error occurred while updating the task'
        console.error(err)
    } finally {
        submitting.value = false
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
        <Heading>Edit Task</Heading>

        <div v-if="error" class="mb-6 rounded-md bg-red-50 p-4">
            <p class="text-sm font-medium text-red-800">{{ error }}</p>
        </div>

        <div v-if="success" class="mb-6 rounded-md bg-green-50 p-4">
            <p class="text-sm font-medium text-green-800">{{ success }}</p>
        </div>

        <div v-if="loading" class="rounded-lg bg-white p-8 text-center">
            <p class="text-gray-600">Loading task...</p>
        </div>

        <div v-else class="rounded-lg bg-white shadow">
            <form @submit.prevent="submit" class="space-y-6 px-4 py-6 sm:p-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input id="title" v-model="form.title" type="text" maxlength="255" required
                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                        placeholder="Enter task title" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                    <textarea id="description" v-model="form.description" rows="4"
                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                        placeholder="Enter task description (optional)"></textarea>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900">Status *</label>
                    <select id="status" v-model="form.status" required
                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-900">Due Date *</label>
                    <input id="due_date" v-model="form.due_date" type="date" required
                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" :disabled="submitting"
                         class="text-sm text-white font-medium bg-primary p-2 px-4 rounded hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ submitting ? 'Updating...' : 'Update Task' }}
                    </button>
                    <a :href="`/tasks/${taskId}`"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-center text-sm font-semibold text-gray-900 hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
