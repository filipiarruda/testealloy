<template>
  <!-- O modal só é renderizado no DOM quando a prop 'show' é verdadeira -->
  <div v-if="show" class="modal-task" style="display: flex; opacity: 1;">
    <div class="container-modal regular">
      <div class="top-modal">
        <!-- O título é dinâmico, mudando se for uma nova tarefa ou edição -->
        <h3>{{ form.id ? 'Editar tarefa' : 'Nova tarefa' }}</h3>
        <!-- O botão de fechar emite o evento 'close' -->
        <div @click="close" class="close-modal">
          <div class="icon w-embed">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M17 7L7 17M7 7L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </div>
        </div>
      </div>
      <div class="content-modal">
        <div class="form-fields w-form">
          <!-- O evento submit do formulário é prevenido e chama o método 'save' -->
          <form @submit.prevent="save" class="block-fields-form">
            <!-- Campo Título com v-model para two-way data binding -->
            <div class="input-wrap no-margin-bottom">
              <input v-model="form.nome" class="input w-input" maxlength="256" name="nome" placeholder=" " type="text" id="nome" required>
              <label for="nome" class="field-label">Título</label>
            </div>
            <!-- Campo Detalhes -->
            <div class="input-wrap no-margin-bottom">
              <input v-model="form.descricao" class="input w-input" maxlength="256" name="descricao" placeholder=" " type="text" id="descricao">
              <label for="descricao" class="field-label">Detalhes</label>
            </div>
            <!-- Campo Data Limite -->
            <div class="input-wrap no-margin-bottom">
              <input v-model="form.data_limite" class="input w-input" name="data_limite" placeholder="YYYY-MM-DD HH:MM:SS" type="text" id="data_limite">
              <label for="data_limite" class="field-label">Data Limite</label>
            </div>
          </form>
        </div>
      </div>
      <div class="bottom-modal">
        <div class="flex-block-horizontal-right-align">
          <!-- Botão para fechar o modal -->
          <div @click="close" class="button outlined rounded">
            <div>Fechar</div>
          </div>
          <!-- Botão para salvar, com texto dinâmico -->
          <div @click="save" class="button rounded">
            <div>{{ form.id ? 'Salvar' : 'Criar' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

// Define as props que o componente aceita e os eventos que ele emite
const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  task: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['close', 'save']);

// Estado inicial para o formulário de uma nova tarefa
const getDefaultForm = () => ({
  id: null,
  nome: '',
  descricao: '',
  data_limite: '',
});

// Estado reativo para o formulário, ligado aos inputs com v-model
const form = ref(getDefaultForm());

// Observa a prop 'task'. Se ela mudar, atualiza o formulário.
// Isso permite usar o mesmo modal para criar (task=null) e editar (task=Object).
watch(() => props.task, (newTask) => {
  if (newTask && newTask.id) {
    form.value = { ...newTask };
  } else {
    form.value = getDefaultForm();
  }
}, { immediate: true, deep: true });

const close = () => {
  emit('close');
};

const save = () => {
  // Emite o evento 'save' com os dados do formulário como payload
  emit('save', { ...form.value });
};
</script>

<style scoped>
/* O CSS do Webflow usa 'display: none'. Nós sobrescrevemos isso para que o Vue controle a visibilidade. */
.modal-task {
  z-index: 100;
  background-color: rgba(0, 0, 0, 0.6); /* Overlay mais escuro para melhor foco */
}

/* Garante que o efeito de "label flutuante" funcione com v-model */
.input:focus + .field-label,
.input:not(:placeholder-shown) + .field-label {
  top: -10px;
  font-size: 14px;
  z-index: 3;
  background-color: white;
  padding: 0 4px;
}
</style>
