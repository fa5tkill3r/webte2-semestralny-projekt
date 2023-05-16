<template>
  <v-container>
    <v-data-table
      :headers="headers"
      :items="students"
    ></v-data-table>
  </v-container>

  <v-container>
    <v-data-table :headers="headersTwo" :items="tasks" item-key="id">
      <template #header="{ props }">
        <tr>
          <th>
            <v-checkbox v-model="props.all" :indeterminate="props.indeterminate"></v-checkbox>
          </th>
          <th>{{ headersTwo[1].title }}</th>
        </tr>
      </template>
      <template #item="{ item }">
        <tr>
          <td>
            <v-checkbox :value="isSelected(item)" @input="toggleSelection(item)"></v-checkbox>
          </td>
          <td>{{ item.name }}</td>
        </tr>
      </template>
    </v-data-table>
    <v-btn @click="submitTasks">Submit</v-btn>
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

const headersTwo = ref([
  { title: '', key: 'checkbox', sortable: false },
  { title: 'Task Name', key: 'name' },
])

const tasks = ref([])
const selectedTasks = ref([])

const students = ref([])

onMounted(async () => {
  const response = await ky.get('students').json()
  students.value = response.students
  console.log(students.value)
  await fetchTasks()
})

async function fetchTasks() {
  try {
    const response = await ky.get('tasks').json()
    tasks.value = response.tasks
    console.log(tasks.value);
  } catch (error) {
    console.error(error)
  }
}

function isSelected(task) {
  return selectedTasks.value.includes(task)
}

function toggleSelection(task) {
  const index = selectedTasks.value.indexOf(task)
  if (index > -1) {
    selectedTasks.value.splice(index, 1)
  } else {
    selectedTasks.value.push(task)
  }
}

function submitTasks() {
  console.log(selectedTasks.value)
}


</script>

<style scoped>

</style>