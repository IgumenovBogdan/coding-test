<template>
<div class="w-[300px] bg-sky-950 rounded-lg shadow-lg">
    <Teleport to="body">
        <generic-modal v-if="isDeleted" @close="closeDeletingModal()">
            <div class="relative">
                <div class="mt-3 sm:mt-5">
                    <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Are you sure you want to delete all tasks?</DialogTitle>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button"
                        class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        @click="deleteAllCards(props.phase_id)">Delete</button>
            </div>
        </generic-modal>
    </Teleport>
    <div class="p-4" style="height: 800px; overflow-y: auto;">
        <div class="flex items-center justify-between">
            <h2 class="text-lg text-zinc-100 font-black mb-3">{{ kanban.phases[props.phase_id].name }} ({{kanban.phases[props.phase_id].tasks_count}})</h2>
            <PlusIcon
                @click="createTask()"
                class="mb-3 h-6 w-6 text-white hover:cursor-pointer"
                aria-hidden="true" />
        </div>
        <div class="flex items-center justify-between">
            <div class="flex flex-col mb-3">
                <span class="text-sm text-zinc-100 font-black">Is completive?</span>
                <Switch
                    v-model="kanban.phases[props.phase_id].is_completive"
                    :class="kanban.phases[props.phase_id].is_completive ? 'bg-blue-600' : 'bg-gray-200'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full"
                    @update:modelValue="toggleIsCompletive(props.phase_id)"
                >
            <span
                :class="kanban.phases[props.phase_id].is_completive ? 'translate-x-6' : 'translate-x-1'"
                class="inline-block h-4 w-4 transform rounded-full bg-white transition"
            />
                </Switch>
            </div>
            <TrashIcon class="w-6 h-6 hover:cursor-pointer fill-red-500" @click="openDeletingModal()" />
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
import { ref } from 'vue'
import {
    DialogTitle,
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
    Switch
} from "@headlessui/vue";
import {CheckIcon, ChevronUpDownIcon, EyeIcon, PencilIcon, TrashIcon} from "@heroicons/vue/20/solid/index.js";

const kanban = useKanbanStore()

let isDeleted = ref(false)

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

const deleteAllCards = async (id) => {
    try {
        const response = await axios.delete('/api/phases/mass-task-deletion/' + id);
        closeDeletingModal();
        await kanban.refreshTasks();
    } catch (error) {
        console.error('There was an error deleting the tasks!', error);
    }
}

const openDeletingModal = () => {
    isDeleted.value = true;
}

const closeDeletingModal = () => {
    isDeleted.value = false;
}

</script>
