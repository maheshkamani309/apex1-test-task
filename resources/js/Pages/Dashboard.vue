<script setup>
import { ref, onMounted } from 'vue'
import { useAuth } from '../composables/useAuth'
import AppLayout from '@/Layouts/AppLayout.vue'
import Heading from '../components/Heading.vue'
const { token } = useAuth()
onMounted(() => {
    if (!token.value) {
        window.location.href = '/login'
    }
})
</script>

<template>
    <AppLayout>
        <div class="flex flex-col gap-2 h-full">
            <Heading>Dashboard</Heading>
            <div class="text-inter text-secondary text-md">
                <p class="mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the
                    librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make
                    dummy text for Letraset's Body Type sheets. It has survived not only many decades, but also the leap
                    into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these
                    sheets and more recently with desktop publishing software including versions of Lorem Ipsum.</p>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the
                    librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make
                    dummy text for Letraset's Body Type sheets. It has survived not only many decades, but also the leap
                    into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these
                    sheets and more recently with desktop publishing software including versions of Lorem Ipsum.</p>
            </div>
        </div>
    </AppLayout>
</template>
