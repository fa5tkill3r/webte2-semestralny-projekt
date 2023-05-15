<template>
  <v-container ref='doc'>
    <div v-if='taskVariant?.task'>
      <div ref='task' class='katex text-center' />
    </div>


    <div v-if='taskVariant?.user_solution'>
      <h2>Tvoje riešenie:</h2>
      <div ref='userSolution' class='katex text-center'/>
    </div>

    <div v-if='taskVariant?.correct === false' class='text-center'>
      <h2>Správne riešenie:</h2>
      <div ref='solution' class='katex text-center' />
    </div>
  </v-container>

</template>

<script setup>
import { onMounted, ref } from 'vue'

import 'katex/dist/katex.min.css'

import renderMathInElement from 'katex/contrib/auto-render'

const props = defineProps({
  taskVariant: {
    type: Object,
    required: true,
  },
})

const task = ref(null)
const solution = ref(null)
const userSolution = ref(null)
const doc = ref(null)

const refresh = () => {
  task.value.innerHTML = props.taskVariant.task

  if (props.taskVariant.solution) {
    solution.value.innerHTML = props.taskVariant.solution
  }

  if (props.taskVariant.user_solution) {
    userSolution.value.innerHTML = "$" + props.taskVariant.user_solution + "$"
  }

  console.log(doc.value.$el.node)


  renderMathInElement(doc.value.$el.parentNode, {
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
}

onMounted(async () => {
 refresh()
})
</script>

<style scoped>

</style>
