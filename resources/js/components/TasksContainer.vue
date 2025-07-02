<script setup>
import { ref } from "vue";
import { useTaskStore } from "@/stores/taskStore.js";
import { onMounted } from "vue";
import axios from "axios";
import { Trash, CheckCircle } from "lucide-vue-next";
const emit = defineEmits(["edit-task", "update-status"]);

const taskStore = useTaskStore();

onMounted(() => {
    taskStore.fetchTasks();
});

const editTask = (task) => {
    emit("edit-task", task);
};

function formatarData(dataLimite) {
    if (!dataLimite) return "Sem data definida";
    const hoje = new Date();
    const data = new Date(dataLimite);
    hoje.setHours(0, 0, 0, 0);
    data.setHours(0, 0, 0, 0);

    if (data.getTime() === hoje.getTime()) {
        return "Hoje";
    }
    const amanha = new Date(hoje);
    amanha.setDate(hoje.getDate() + 1);
    if (data.getTime() === amanha.getTime()) {
        return "Amanhã";
    }
    // Retorna no formato dd/mm
    return data.toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
    });
}
</script>

<template>
    <div
        v-for="task in taskStore.tasks"
        :key="task.id"
        class="task-item-example flex items-center justify-between bg-white rounded-lg shadow p-4 mb-3"
    >
        <!-- Checkbox à esquerda -->
        <input
            type="checkbox"
            class="mr-4 text-green-600 rounded bg-green-600 focus:ring-green-500 h-4 w-4 task-custom-checkbox"
            :checked="task.finalizado"
            @change="$emit('update-status', task)"
        />
        <div
            class="flex flex-col flex-1 cursor-pointer"
            @click="editTask(task)"
        >
            <span class="font-semibold text-gray-800">{{ task.nome }}</span>
            <span

                class="mt-1 px-3 py-1 w-fit rounded-full text-xs font-semibold"
                :class="{
                    'bg-green-100 text-green-800':
                        formatarData(task.data_limite) === 'Hoje',
                    'bg-yellow-100 text-yellow-800':
                        formatarData(task.data_limite) === 'Amanhã',
                    'bg-gray-100 text-gray-800':
                        formatarData(task.data_limite) !== 'Hoje' &&
                        formatarData(task.data_limite) !== 'Amanhã',
                }"
            >
                {{ formatarData(task.data_limite) }}
            </span>
        </div>
        <button
            class="ml-4 w-10 h-10 bg-white text-gray-900 rounded-full flex items-center justify-center hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray transition"
        >
            <Trash class="w-5 h-5 text-gray-900" />
        </button>
    </div>
</template>

<style scoped>
/* Estilos de exemplo para demonstração */
.task-item-example {
    grid-column-gap: 6px;
    grid-row-gap: 6px;
    cursor: pointer;
    flex-flow: row;
    justify-content: flex-start;
    align-items: center;
    padding: 10px 20px;
    transition: all 0.2s;
    display: flex;
    position: relative;
    height: 76px;
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
</style>
