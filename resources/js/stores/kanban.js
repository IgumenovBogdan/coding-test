import { defineStore } from 'pinia'

export const useKanbanStore = defineStore('kanban', {
  state: () => {
    return {
        hoveredName: 'nothing',
        selectedTask: null,
        phases: [],
        users: [],
        creatingTask: false,
        creatingTaskProps: {
          name: '',
          phase_id: null,
          user_id: null,
        },
        editingTaskProps: {
            name: '',
            phase_id: null,
            user_id: null,
        },
        self: null,
    }
  },
  actions: {
    unhoverTask() {
      this.hoveredName = 'nothing'
    },
    selectTask(task) {
        this.selectedTask = task
    },
    unselectTask(task) {
        this.selectedTask = null
    },
    hasSelectedTask() {
        return this.selectedTask !== null
    },
      async refreshTasks() {
        try {
            const response = await axios.get('/api/tasks');
            const originalTasks = response.data;
            this.phases = originalTasks.reduce((acc, cur) => {
                acc[cur.id] = cur;
                return acc;
            }, {});
        } catch (error) {
            console.error('There was an error fetching the tasks!', error);
        }
      }
  },
})
