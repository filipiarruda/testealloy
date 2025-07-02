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

// Abre o modal para criar uma nova tarefa
const openCreateModal = () => {
  console.log('Evento create-task recebido!');
  selectedTask.value = null; // Garante que o formulário estará vazio
  isModalVisible.value = true;
};

// Abre o modal para editar uma tarefa existente (será usado pelo TasksContainer)
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
    const response = await axios.get('/tasks');
    taskStore.setTasks(response.data.tasks);
    closeModal();
  } catch (error) {
    console.error('Erro ao salvar tarefa:', error);
    // Aqui você pode exibir um alerta ou mensagem de erro para o usuário
  }
};
</script>

<template>
  <div class="content-tasks">
    <Appbar @create-task="openCreateModal" />
    <TasksContainer @edit-task="openEditModal" />
    <Footer />
    <TaskModal :show="isModalVisible" :task="selectedTask" @close="closeModal" @save="handleSaveTask" />
  </div>
</template>
