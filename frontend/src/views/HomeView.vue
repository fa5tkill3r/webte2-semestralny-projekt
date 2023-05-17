<template>
  <component :is='component' />
</template>

<script setup>

import { computed } from 'vue'
import { useAppStore } from '@/store/app'
import AssignmentsView from '@/views/Student/AssignmentsView.vue'
import TeacherView from '@/views/Teacher/TeacherView.vue'

const store = useAppStore()

const component = computed(() => {
  if (store.role === 'student') {
    return AssignmentsView
  } else {
    return TeacherView
  }
})

</script>
