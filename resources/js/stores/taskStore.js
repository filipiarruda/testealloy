import { defineStore } from 'pinia';

export const useTaskStore = defineStore('task', {
  state: () => ({
    tasks: [],
    lastUpdated: null,
  }),
  actions: {
    setTasks(tasks) {
      this.tasks = tasks;
      this.lastUpdated = Date.now();
    },
    async fetchTasks(force = false) {
      // Cache de 1 minuto
      if (!force && this.lastFetch && Date.now() - this.lastFetch < 60000) {
        return; // Usa o cache
      }
      const response = await axios.get('/tasks');
      this.setTasks(response.data.tasks);
    },
    addTask(task) {
      this.tasks.push(task);
    },
    updateTask(updatedTask) {
      const index = this.tasks.findIndex(t => t.id === updatedTask.id);
      if (index !== -1) this.tasks[index] = updatedTask;
    },
    removeTask(id) {
      this.tasks = this.tasks.filter(t => t.id !== id);
    },
  },
});
