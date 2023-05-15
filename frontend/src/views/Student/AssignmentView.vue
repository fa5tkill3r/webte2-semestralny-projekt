<template>
  <v-container v-if="assignment">
    <h1>{{ assignment.set.name }}</h1>

    <div class="d-flex justify-space-between">
      <div>
        <v-btn v-if="tab > 0" variant="text" color="primary" :disabled="tab === 0" @click="tab--">
          {{ $t('back') }}
        </v-btn>
      </div>

      <div class="text-center">
        {{ $t('points') }} {{ assignment.task_variants[tab].max_points }}
      </div>

      <div>
        <v-btn
          v-if="tab < assignment.task_variants.length - 1"
          variant="text"
          color="primary"
          :disabled="assignment.current_task_index === assignment.task_variants.length - 1"
          @click="tab++"
        >
          {{ $t('continue') }}
        </v-btn>
        <v-btn
          v-else-if="!assignment.finished_at"
          variant="text"
          color="success"
          :disabled="assignment.current_task_index === assignment.task_variants.length - 1"
          @click="submit"
        >
          {{ $t('submit') }}
        </v-btn>
        <v-btn v-else variant="text" color="priamry" to="/assignments">
          {{ $t('close') }}
        </v-btn>
      </div>
    </div>

    <v-carousel v-model="tab" height="100%" hide-delimiters progress="primary" :show-arrows="false">
      <v-carousel-item v-for="task in assignment.task_variants" :key="task.id">
        <div>
          <h2 class="text-center">{{ task.name }}</h2>

          <TaskViewer :task-variant="task" class="mb-10" />

          <div class="d-flex flex-column align-center">
            <h3>{{ $t('solutionDotDot') }}</h3>
            <SolutionEditor
              class="mb-10"
              :value="task.user_solution"
              :disabled="assignment.finished_at !== null"
              :correct="task.correct"
              @update="update"
            />
          </div>
        </div>
      </v-carousel-item>
    </v-carousel>
  </v-container>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ky } from '@/lib/ky'
import TaskViewer from '@/components/TaskViewer.vue'
import SolutionEditor from '@/components/SolutionEditor.vue'
import router from '@/router'

const props = defineProps({
  id: {
    type: String,
    required: true,
  },
})

const assignment = ref(null)
const tab = ref(0)

const update = async (value) => {
  const response = await ky
    .post(`assignment/${props.id}/${tab.value + 1}`, {
      json: {
        solution: value,
      },
    })
    .json()
}

const submit = async () => {
  const response = await ky.post(`assignment/${props.id}/submit`).json()
}

onMounted(async () => {
  const response = await ky.get(`assignment/${props.id}`).json()
  console.log(response)
  assignment.value = response.assignment
})
</script>

<style scoped></style>
