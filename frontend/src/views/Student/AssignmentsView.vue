<template>
  <v-container>
    <v-data-table
      :headers='headers'
      :items='data'
    >
      <template #item.actions='{ item }'>
        <v-btn
          :to='`/assignment/${item.value}`'
          color='primary'
        >
          {{ item.raw.finished_at ? 'Zobraziť' : 'Spusiť' }}
        </v-btn>
      </template>
      <template #item.created_at='{ item }'>
        {{ toLocalDate(item.columns.created_at) }}
      </template>
      <template #item.set.end='{ item }'>
        {{ toLocalDate(item.columns.set.end) }}
      </template>
      <template #item.status='{ item }'>
        {{ toState(item) }}
      </template>
    </v-data-table>
  </v-container>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ky } from '@/lib/ky'

const headers = ref([
  { title: 'Name', key: 'set.name' },
  { title: 'Created at', key: 'created_at' },
  { title: 'Deadline', key: 'set.end' },
  { title: 'Status', key: 'status' },
  { title: 'Actions', key: 'actions', sortable: false },
])

const data = ref([])

const toLocalDate = (date) => {
  return new Date(date).toLocaleString('sk-SK')
}

const toState = (item) => {
  if (item.raw.finished_at !== null) {
    return 'Odovzdané'
  } else {
    return 'Neodovzdané'
  }
}


onMounted(async () => {
  const response = await ky.get('assignment').json()
  data.value = response.assignments
})


</script>

<style scoped>

</style>
