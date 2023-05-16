<template>
  <v-container>
    <v-data-table
      :headers="headers"
      :items="students"
    ></v-data-table>
  </v-container>
</template>



<script setup>
import { onMounted, ref } from 'vue'
import { ky } from '@/lib/ky'

const headers = ref([
  { title: 'First Name', key: 'first_name' },
  { title: 'Last Name', key: 'last_name' },
  { title: 'Email', key: 'email' },
])

const students = ref([])

onMounted(async () => {
  const response = await ky.get('students').json()
  students.value = response.students
  console.log(students.value)
})
</script>

<style scoped>

</style>
