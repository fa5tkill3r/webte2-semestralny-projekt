<template>
  <v-container>
    <div id='task' class='katex text-center' />

    <div v-if='taskVariant.solution' class='text-center'>
      <h2>{{$t('solution')}}</h2>
      <div id='solution' class='katex text-center' />
    </div>
  </v-container>

</template>

<script setup>
import { onMounted } from 'vue'

import 'katex/dist/katex.min.css'

import renderMathInElement from 'katex/contrib/auto-render'

const props = defineProps({
  taskVariant: {
    type: Object,
    required: true,
  },
})

onMounted(async () => {
  console.log(props.taskVariant)
  const task = document.getElementById('task')
  task.innerHTML = props.taskVariant.task

  if (props.taskVariant.solution) {
    const solution = document.getElementById('solution')
    solution.innerHTML = props.taskVariant.solution
  }


  renderMathInElement(document.body, {
    delimiters: [
      { left: '$$', right: '$$', display: true },
      { left: '$', right: '$', display: false },
      { left: '\\(', right: '\\)', display: false },
      { left: '\\[', right: '\\]', display: true },
      { left: '\\begin{aligned}', right: '\\end{aligned}', display: true },
      { left: '\\begin{equation*}', right: '\\end{equation*}', display: true },
      { left: '\\', right: '\\', display: true },
    ],
    trust: true,
  })

})
</script>

<style scoped>

</style>
