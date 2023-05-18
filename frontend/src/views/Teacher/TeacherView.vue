<template>
  <v-container>
    <v-card class="dashboard-card">
      <v-card-title>{{$t('Students')}}</v-card-title>
      <v-card-text>
        <v-data-table :headers="headers" :items="students"></v-data-table>
      </v-card-text>
    </v-card>

    <div class="flex-container">
      <v-card class="dashboard-card smaller-card">
        <v-card-title>{{$t('Makesetoftasks')}}</v-card-title>
        <v-card-text>
          <p>{{$t('Selectthetasksbelowtocreateataskset')}}</p>
          <div>
            <div v-for="task in tasks" :key="task.id">
              <v-checkbox
                v-model="selectedTasks"
                :label="task.name"
                :value="task.id"
                class="task-checkbox"
              ></v-checkbox>
            </div>
          </div>
          <br />
          <v-text-field
            v-model="taskName"
            :label="$t('TaskName')"
            outlined
            dense
            class="task-name"
            persistent-hint
            :hint="$t('Enteradescriptivenameforthetask')"
          ></v-text-field>
          <br />
          <v-text-field
            v-model="maxPoints"
            :label="$t('MaxPoints')"
            outlined
            dense
            type="number"
            class="max-points"
            persistent-hint
            :hint="$t('Enterthemaximumpointsforthetask')"
          ></v-text-field>
          <br />
          <div class="date-selection">
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="startDate"
                  :label="$t('StartDate')"
                  outlined
                  dense
                  type="date"
                  class="date-picker"
                  persistent-hint
                  :hint="$t('Selectthestartdateforthetask')"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="startTime"
                  :label="$t('StartTime')"
                  outlined
                  dense
                  type="time"
                  class="time-picker"
                  persistent-hint
                  :hint="$t('Selectthestarttimeforthetask')"
                ></v-text-field>
              </v-col>
            </v-row>
            <br />
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="endDate"
                  :label="$t('EndDate')"
                  outlined
                  dense
                  type="date"
                  class="date-picker"
                  persistent-hint
                  :hint="$t('Selecttheenddateforthetask')"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="endTime"
                  :label="$t('EndTime')"
                  outlined
                  dense
                  type="time"
                  class="time-picker"
                  persistent-hint
                  :hint="$t('Selecttheendtimeforthetask')"
                ></v-text-field>
              </v-col>
            </v-row>
            <br />
            <v-btn class="submit-button" @click="submit">{{$t('Submit')}}</v-btn>
          </div>
        </v-card-text>
      </v-card>

      <v-card class="dashboard-card smaller-card">
        <v-card-title>{{$t('AddnewLaTeXfile')}}</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="taskNameTextInput"
            :label="$t('TaskName')"
            outlined
            dense
            class="task-name"
            persistent-hint
            :hint="$t('Enteradescriptivenameforthetask')"
          ></v-text-field>
          <br />
          <v-textarea
            v-model="textInput"
            :label="$t('LaTeXTextInput')"
            outlined
            rows="7"
            class="text-input"
            persistent-hint
            :hint="$t('EntertheLaTeXtextforthetask')"
          ></v-textarea>
          <br />
          <v-btn class="submit-button" @click="submitText">{{$t('SubmitText')}}</v-btn>
        </v-card-text>
      </v-card>
    </div>

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
import { t } from '@/lib/i18n'

const headers = ref([
  { title: 'ID', key: 'id' },
  { title: t('FirstName'), key: 'first_name' },
  { title: t('LastName'), key: 'last_name' },
  { title: t('Email'), key: 'email' },
])

const tasks = ref([])
const students = ref([])
const selectedTasks = ref([])
const taskName = ref('')
const taskNameTextInput = ref('')
const textInput = ref('')
const startDate = ref('')
const startTime = ref('')
const endDate = ref('')
const endTime = ref('')
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

const maxPoints = ref(0)

// Update the 'submit' function
const submit = async () => {
  const selectedTaskIds = selectedTasks.value

  if (!selectedTaskIds.length) {
    showErrorSnackbar(t('Pleaseselectatleastonetask'))
    return
  }

  if (!taskName.value.trim()) {
    showErrorSnackbar(t('Pleaseenterataskname'))
    return
  }

  if (maxPoints.value < selectedTaskIds.length) {
    showErrorSnackbar(t('Maxpointsshouldbegreaterthanorequaltothenumberofselectedtasks'))
    return
  }

  if (
    startDate.value &&
    startTime.value &&
    endDate.value &&
    endTime.value &&
    startDate.value <= endDate.value
  ) {
    const startDateTime = `${startDate.value} ${startTime.value}:00`
    const endDateTime = `${endDate.value} ${endTime.value}:00`

    try {
      const response = await ky
        .post('sets', {
          json: {
            name: taskName.value,
            start: startDateTime,
            end: endDateTime,
            tasks: selectedTaskIds,
            max_points: maxPoints.value,
          },
        })
        .json()

      console.log('Set created successfully:', response.set)
      // You can perform any additional actions or display a success message here
    } catch (error) {
      console.error('Failed to create set:', error)
      // Handle the error appropriately (e.g., display an error message)
    }
  } else {
    showErrorSnackbar(t('Invaliddateortimeselected'))
  }
}

const submitText = async () => {
  if (taskNameTextInput.value && textInput.value) {
    const queryParams = new URLSearchParams()
    queryParams.append('taskname', taskNameTextInput.value)

    const response = await ky.post(`teacher/parse?${queryParams.toString()}`, {
      body: textInput.value,
    })

    if (response.ok) {
      console.log('Text submitted successfully')
    } else {
      console.log('Failed to submit text')
    }
  } else {
    showErrorSnackbar(t('Bothtasknameandtextinputarerequired'))
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
  flex: 1;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.flex-container {
  display: flex;
  gap: 20px;
}

.date-selection,
.text-input {
  margin-top: 20px;
}

.date-picker .v-input__control,
.time-picker .v-input__control,
.text-input .v-input__control {
  background-color: #f3f5f7;
  box-shadow: none;
  border-radius: 4px;
}

.date-picker .v-label,
.time-picker .v-label,
.text-input .v-label {
  color: #546e7a;
}

.date-picker input[type='date']::-webkit-calendar-picker-indicator,
.time-picker input[type='time']::-webkit-calendar-picker-indicator {
  filter: invert(45%) sepia(15%) saturate(458%) hue-rotate(164deg) brightness(96%) contrast(84%);
}

.text-input textarea {
  resize: vertical;
}

.submit-button {
  background-color: #1976d2;
  color: #ffffff;
  border-radius: 4px;
  padding: 8px 16px;
  transition: background-color 0.3s;
}

.submit-button:hover {
  background-color: #1565c0;
}

.max-points .v-input__control {
  background-color: #f3f5f7;
  box-shadow: none;
  border-radius: 4px;
}

.max-points .v-label {
  color: #546e7a;
}
</style>
