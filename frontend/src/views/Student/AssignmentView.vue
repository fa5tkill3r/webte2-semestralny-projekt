<template>
  <div v-if='assignment.id' ref='test'>
    <v-container>
      <h1>{{ assignment.set?.name }}</h1>


      <div v-for='setTask in assignment.set.set_tasks' :key='setTask.id'>
        <v-row>
          <v-col cols='auto'>
            <h2 class='text-center'>{{ setTask.task.name }}</h2>
          </v-col>
          <v-col v-if='!setTask.task.taskVariant' cols='auto'>
            <v-btn
              @click='generate(setTask.task_id)'
            >
              Generovať
            </v-btn>
          </v-col>

        </v-row>
        <div v-if='setTask.task.taskVariant'>
          <Task
            :task='setTask.task' :assignment-id='assignment.id' class='mb-10'
            @update='update' />
        </div>
        <v-divider class='my-10'></v-divider>

      </div>


    </v-container>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { ky } from '@/lib/ky'
import Task from '@/components/Task.vue'

const test = ref(null)


const props = defineProps({
  id: {
    type: String,
    required: true,
  },
})

const assignment = ref({})


const generate = async (setTaskId) => {
  const response = await ky.post(`assignment/${assignment.value.id}/${setTaskId}/generate`).json()
  assignment.value = response.assignment
}


const update = async (value, taskId) => {
  assignment.value.set.set_tasks.find((x) => x.task_id === taskId).task.taskVariant.user_solution = value
}

onMounted(async () => {
  const response = await ky.get(`assignment/${props.id}`).json()
  assignment.value = response.assignment
})
</script>

<style scoped></style>
