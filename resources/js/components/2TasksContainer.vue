<script setup>
import { ref } from "vue";
import { useTaskStore } from "@/stores/taskStore.js";
import { onMounted } from "vue";
import axios from "axios";
const emit = defineEmits(["edit-task"]);

const taskStore = useTaskStore();

const fetchTasks = async () => {
    try {
        const response = await axios.get("/tasks");
        taskStore.setTasks(response.data.tasks);
    } catch (error) {
        console.error("Erro ao buscar tarefas:", error);
    }
};

onMounted(() => {
    fetchTasks();
});

const editTask = (task) => {
    // Emite o evento para o componente pai (App.vue) com os dados da tarefa a ser editada.
    emit("edit-task", task);
};
</script>

<template>
    <div class="container-tasks p-6">
        <!-- Este é apenas um exemplo de como você listaria as tarefas. -->
        <!-- No futuro, você substituirá isso pelos seus componentes TaskList e TaskItem. -->
        <div
            v-for="task in taskStore.tasks"
            :key="task.id"
            class="task-item-example flex items-start justify-between bg-white rounded-lg shadow p-4 mb-3"
        >
            <input
                type="checkbox"
                class="checkbox w--redirected-checked flex items-center gap-3"
                :checked="task.concluida"
                @change="$emit('toggle-task', task)"
            />
            <span>{{ task.nome }}</span>
            <button @click="editTask(task)" class="button-example">
                Editar
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Estilos de exemplo para demonstração */
.task-item-example {
    grid-column-gap: 6px;
    grid-row-gap: 6px;
    cursor: pointer;
    flex-flow: column;
    justify-content: flex-start;
    align-items: flex-start;
    padding: 10px 20px;
    transition: all 0.2s;
    display: flex;
    position: relative;
}
.task-item-example:hover {
    background-color: var(--colors--transparent-0-06);
}
.button-example {
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.checkbox.w--redirected-checked {
    background-color: #1fb76c;
    background-size: 16px;
    border-color: #1fb76c;
    border-radius: 4px;
}
</style>
