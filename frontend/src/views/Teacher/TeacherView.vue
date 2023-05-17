<template>
  <v-container>
    <v-card class="dashboard-card">
      <v-card-title>Students</v-card-title>
      <v-card-text>
        <v-data-table :headers="headers" :items="students"></v-data-table>
      </v-card-text>
    </v-card>

    <v-card class="dashboard-card smaller-card">
      <v-card-title>Task and Date Selection</v-card-title>
      <v-card-text>
        <div>
          <div v-for="task in tasks" :key="task.id">
            <v-checkbox v-model="selectedTasks" :label="task.name" :value="task.id"></v-checkbox>
          </div>
        </div>
        <div class="date-selection">
          <v-text-field
            v-model="startDate"
            label="Start Date"
            outlined
            dense
            type="date"
            class="date-picker"
          ></v-text-field>
          <br />
          <v-text-field
            v-model="endDate"
            label="End Date"
            outlined
            dense
            type="date"
            class="date-picker"
          ></v-text-field>
          <br />
          <v-btn @click="submit" class="submit-button">Submit</v-btn>
        </div>
      </v-card-text>
    </v-card>

    <v-snackbar
      v-model="errorSnackbar"
      :color="snackbarColor"
      :timeout="snackbarTimeout"
      top
      multi-line
    >
      {{ snackbarMessage }}
    </v-snackbar>
  </v-container>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ky } from '@/lib/ky'

const headers = ref([
  { text: 'First Name', value: 'first_name' },
  { text: 'Last Name', value: 'last_name' },
  { text: 'Email', value: 'email' },
])

const tasks = ref([])
const students = ref([])
const selectedTasks = ref([])
const startDate = ref('')
const endDate = ref('')
const errorSnackbar = ref(false)
const snackbarTimeout = ref(4000)
const snackbarColor = ref('error')
const snackbarMessage = ref('')

onMounted(async () => {
  const response = await ky.get('students').json()
  students.value = response.students

  const responseTwo = await ky.get('tasks').json()
  tasks.value = responseTwo.tasks
})

const submit = () => {
  const selectedTaskIds = selectedTasks.value
  const selectedDates = {
    startDate: startDate.value,
    endDate: endDate.value,
  }

  if (startDate.value && endDate.value && startDate.value <= endDate.value) {
    console.log('Selected task IDs:', selectedTaskIds)
    console.log('Selected dates:', selectedDates)
  } else {
    showErrorSnackbar('Invalid date range selected.')
  }
}

const showErrorSnackbar = (message) => {
  snackbarMessage.value = message
  errorSnackbar.value = true
}
</script>

<style scoped>
.dashboard-card {
  margin-bottom: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.smaller-card {
  width: 300px;
  margin-right: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.date-selection {
  margin-top: 20px;
}

.date-picker .v-input__control {
  background-color: #f3f5f7;
  box-shadow: none;
  border-radius: 4px;
}

.date-picker .v-label {
  color: #546e7a;
}

.date-picker input[type='date']::-webkit-calendar-picker-indicator {
  filter: invert(45%) sepia(15%) saturate(458%) hue-rotate(164deg) brightness(96%) contrast(84%);
}

.submit-button {
  background-color: #1976d2;
  color: #ffffff;
  border-radius: 4px;
}

.submit-button:hover {
  background-color: #1565c0;
}
</style>
