<template>
  <div v-if='assignment.id' ref='test'>
    <v-container>
      <div class='d-flex flex-row align-center'>
        <h1 class='mr-3'>{{ assignment.set?.name }}</h1>
        <v-chip
          v-if='passed'
          color='red'
          text-color='white'
        >
          Ukončené
        </v-chip>
      </div>


      <div v-for='setTask in assignment.set.set_tasks' :key='setTask.id'>
        <v-row>
          <v-col cols='auto'>
            <h2 class='text-center'>{{ setTask.task.name }}</h2>
          </v-col>
          <v-col v-if='!setTask.task.taskVariant && !passed' cols='auto'>
            <v-btn
              @click='generate(setTask.task_id)'
            >
              Generovať
            </v-btn>
          </v-col>
          <v-spacer/>
          <span class='points' :class='{ correct: setTask?.task?.taskVariant?.correct, wrong: setTask?.task?.taskVariant?.correct === false }'>
            {{ points(setTask) }} / {{ setTask.max_points }}
          </span>

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
import { computed, onMounted, ref } from 'vue'
import { ky } from '@/lib/ky'
import Task from '@/components/Task.vue'

const test = ref(null)


const props = defineProps({
  id: {
    type: String,
    required: true,
  },
})

const points = (setTask) => {
  if (setTask?.task?.taskVariant?.correct === undefined)
    return '-'
  if (setTask?.task?.taskVariant?.correct === null)
    return '-'
  if (setTask.task.taskVariant?.correct)
    return setTask.max_points
  return 0
}

const assignment = ref({})

const passed = computed(() => {
  return new Date(assignment.value.set.end) < new Date()
})


const generate = async (setTaskId) => {
  const response = await ky.post(`assignment/${assignment.value.id}/${setTaskId}/generate`).json()
  assignment.value = response.assignment
}


const update = async (value, taskId, correct) => {
  const taskVariant = assignment.value.set.set_tasks.find((x) => x.task_id === taskId).task.taskVariant
  taskVariant.user_solution = value
  taskVariant.correct = correct
}

onMounted(async () => {
  const response = await ky.get(`assignment/${props.id}`).json()
  assignment.value = response.assignment
})
</script>

<style scoped>
.points {
  font-size: 1.5rem;
  font-weight: bold;
}

.correct {
  color: green;
}

.wrong {
  color: red;
}

</style>
