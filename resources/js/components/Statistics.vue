<template>
    <div class="max-w-7xl flex-1 mx-auto flex flex-col overflow-auto sm:px-6 lg:px-8">
        <div class="flex flex-col space-y-4">
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">Statistics for the month</h2>
                <div class="flex justify-between mb-2">
                    <div>
                        <p class="text-gray-500">Created tasks</p>
                        <p class="text-3xl font-bold">{{ tasksThisMonth }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">Statistics for the week</h2>
                <div class="flex justify-between mb-2">
                    <div>
                        <p class="text-gray-500">Completed tasks</p>
                        <p class="text-3xl font-bold">{{ tasksThisWeek }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">User statistics</h2>
                <div v-for="user in users" :key="user.id" class="mb-2">
                    <p class="text-gray-500">{{ user.name }}</p>
                    <p class="text-3xl font-bold">{{ user.tasks_count }}</p>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'

const errors = ref(null)

const tasksThisMonth = ref(null);
const tasksThisWeek = ref(null);
const users = ref(null);

const refreshStatistics = async () => {
    try {
        const response = await axios.get('/api/statistics');
        tasksThisMonth.value = response.data.lastWeekCompleted;
        tasksThisWeek.value = response.data.lastMonthCreated;
        users.value = response.data.usersTasks;
    } catch (error) {
        console.error('There was an error fetching the tasks!', error);
    }
}

onMounted(async () => {

    await refreshStatistics();

    await nextTick();

})

</script>

<style scoped>

</style>
