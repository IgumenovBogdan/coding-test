<template>
<div class="w-[300px] bg-sky-950 rounded-lg shadow-lg">
    <div class="p-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg text-zinc-100 font-black mb-3">{{ kanban.phases[props.phase_id].name }} ({{kanban.phases[props.phase_id].tasks_count}})</h2>
            <PlusIcon
                @click="createTask()"
                class="mb-3 h-6 w-6 text-white hover:cursor-pointer"
                aria-hidden="true" />
        </div>
        <div class="flex flex-col mb-3">
            <span class="text-sm text-zinc-100 font-black">Is completive?</span>
            <Switch
                v-model="kanban.phases[props.phase_id].is_completive"
                :class="kanban.phases[props.phase_id].is_completive ? 'bg-blue-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 items-center rounded-full"
                @update:modelValue="toggleIsCompletive(kanban.phases[props.phase_id].id)"
            >
            <span
                :class="kanban.phases[props.phase_id].is_completive ? 'translate-x-6' : 'translate-x-1'"
                class="inline-block h-4 w-4 transform rounded-full bg-white transition"
            />
            </Switch>
        </div>
        <task-card v-if="kanban.phases[props.phase_id].tasks.length > 0" v-for="task in kanban.phases[props.phase_id].tasks" :task="task" />

        <!-- A card to create a new task -->
        <div class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
            @click="createTask()">
            <span>Create a new task</span>
            <PlusIcon class="h-6 w-6" aria-hidden="true" />
        </div>

    </div>
</div>
</template>

<script setup>
// get the props
import { useKanbanStore } from '../stores/kanban'
import { PlusIcon } from '@heroicons/vue/20/solid'
import { Switch } from "@headlessui/vue";

const kanban = useKanbanStore()

const props = defineProps({
    phase_id: {
        type: Number,
        required: true
    },
})

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
}

const toggleIsCompletive = async (id) => {
    try {
        await axios.patch('/api/phases/completive-toggle/' + id);
    } catch (error) {
        console.log('There was an error toggling the phase!', error);
    }
}

</script>
