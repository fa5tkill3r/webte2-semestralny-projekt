<template>
  <v-card
    min-width='11em'
    @click='router.push({ name: "AssignmentView",
        params: { id: assignment.id }})'
  >
    <v-card-title>
      <div class='d-flex justify-space-between align-center'>
        <span>
          {{ assignment.set.name }}
          <v-chip
            v-if='new Date(assignment.set.end) > new Date()'
            :color='color'
            text-color='white'
          >
            {{ assignment.state }}
          </v-chip>
        </span>
        <span>
          {{ assignment.points ?? '-' }}/{{ assignment.max_points }}
      </span>
      </div>
    </v-card-title>
    <v-card-subtitle>

      <div class='d-flex flex-column'>
        <span>{{ formattedDate }}</span>
        <span>
           <TimeLeft :date='assignment.set.end' />
        </span>
      </div>

    </v-card-subtitle>

    <v-card-text>
      <v-list density='compact'>
        <v-list-item
          class='text-end'>
          <template #prepend>
            <div class='mr-2'>
              <v-icon>mdi-school</v-icon>
            </div>
            {{$t('Teacher')}}
          </template>
          {{ assignment.teacher.first_name }} {{ assignment.teacher.last_name }}
        </v-list-item>
        <v-list-item
          class='text-end'>
          <template #prepend>
            <div class='mr-2'>
              <v-icon>mdi-note-edit</v-icon>
            </div>
            {{$t('Ulohy')}}
          </template>
          {{ assignment.tasks_done }}/{{ assignment.tasks_count }}
        </v-list-item>
      </v-list>

    </v-card-text>
  </v-card>
</template>

<script setup>

import router from '@/router'
import { computed } from 'vue'
import TimeLeft from '@/components/TimeLeft.vue'

const props = defineProps({
  assignment: {
    type: Object,
    required: true,
  },
})

const formattedDate = computed(() => {
  return new Date(props.assignment.set.end).toLocaleString('sk')
})

const color = computed(() => {
  if (props.assignment.state === 'new') {
    return 'blue'
  }
  if (props.assignment.state === 'in_progress') {
    return 'orange'
  }
  if (props.assignment.state === 'done') {
    return 'green'
  }
  return 'gray'
})


</script>

<style scoped>

</style>
