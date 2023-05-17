<template>
  <div>
    <h2 class='text-center'>{{ task.name }}</h2>

    <TaskViewer :task-variant='task.taskVariant' />

    <div class='d-flex flex-column'>

      <SolutionEditor
        v-if='!task.taskVariant?.user_solution'
        class='mb-10'
        :value='task.taskVariant?.user_solution'
        :correct='task.taskVariant?.correct'
        @update='update'
      />
    </div>
  </div>
</template>

<script setup>

import TaskViewer from '@/components/TaskViewer.vue'
import SolutionEditor from '@/components/SolutionEditor.vue'
import { ky } from '@/lib/ky'

const props = defineProps({
  task: {
    type: Object,
    required: true,
  },
  assignmentId: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['update'])

const update = async (value) => {
  const response = await ky.post(`assignment/${props.assignmentId}/${props.task.taskVariant.id}/submit`, {
    json: {
      solution: value,
    },
  }).json()
  const correct = response.assignment.set.set_tasks.find((x) => x.task_id === props.task.id).task.taskVariant.correct

  emit('update', value, props.task.id, correct)
}



</script>


<style scoped>

</style>
