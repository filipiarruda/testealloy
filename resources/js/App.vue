<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Appbar from '@/components/Appbar.vue';
import Footer from '@/components/Footer.vue';
import TasksContainer from '@/components/TasksContainer.vue';
import TaskModal from '@/components/TaskModal.vue';
import { useTaskStore } from '@/stores/taskStore.js';

const taskStore = useTaskStore();

// Estado para controlar a visibilidade do modal
const isModalVisible = ref(false);
// Estado para guardar a tarefa que está sendo editada, ou null se for uma nova
const selectedTask = ref(null);

//Modal nova task
const openCreateModal = () => {
  selectedTask.value = null; // Garante que o formulário estará vazio
  isModalVisible.value = true;
};

//Editar
const openEditModal = (task) => {
  selectedTask.value = { ...task }; // Passa uma cópia para evitar mutação direta
  isModalVisible.value = true;
};

// Fecha o modal
const closeModal = () => {
  isModalVisible.value = false;
};

// Lida com o evento de salvar do modal
const handleSaveTask = async (taskData) => {
  try {
    if (!taskData.id) {
      // Criação
      const response = await axios.post('/tasks', taskData);
      taskStore.addTask(response.data.task); // Atualize a store com a nova tarefa
    } else {
      // Edição
      const response = await axios.put(`/tasks/${taskData.id}`, taskData);
      taskStore.updateTask(response.data.task);
    }
    await taskStore.fetchTasks(true);
    closeModal();
  } catch (error) {
    console.error('Erro ao salvar tarefa:', error);
  }
};

const handleUpdateStatus = async (task) => {
  await axios.post(`/tasks/${task.id}/status`, { ...task, finalizado: !task.finalizado });
  await taskStore.fetchTasks(true);
};
</script>

<template>
  <div class="content-tasks">
    <Appbar @create-task="openCreateModal" />
    <TasksContainer @edit-task="openEditModal" @update-status="handleUpdateStatus" />
    <Footer />
    <TaskModal :show="isModalVisible" :task="selectedTask" @close="closeModal" @save="handleSaveTask" />
  </div>
</template>
