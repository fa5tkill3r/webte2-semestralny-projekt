<template>
  <v-container
    v-if='assignments'
  >

    <h1 class='mb-3'>{{$t('Currentassignments')}}</h1>
    <div
      v-if='assignments'
    >
      <v-row
        justify='center'
        class='mb-7'
      >
        <v-col
          v-for='assignment in currentAssignments'
          :key='assignment.id'
          cols='12'
          sm='6'
          md='4'
          lg='3'
          xl='2'
          xxl='2'
        >
          <AssignmentComponent :assignment='assignment' />
        </v-col>
      </v-row>
      <div
        v-if='currentAssignments?.length === 0'
      >
        {{$t('Youhavenocurrentassignments')}}
      </div>
    </div>


    <h1 class='mb-3'>{{$t('Pastassignments')}}</h1>
    <div
      v-if='assignments'
    >
      <v-row
        justify='center'
      >
        <v-col
          v-for='assignment in pastAssignments'
          :key='assignment.id'
          cols='12'
          sm='6'
          md='4'
          lg='3'
          xl='2'
          xxl='2'
        >
          <AssignmentComponent :assignment='assignment' />
        </v-col>
      </v-row>
      <div
        v-if='pastAssignments?.length === 0'
      >
        {{$t('Youhavenopastassignments')}}
      </div>
    </div>
  </v-container>
</template>

<script setup>
import { computed, ref } from 'vue'
import { ky } from '@/lib/ky'
import AssignmentComponent from '@/components/AssignmentComponent.vue'

const assignments = ref(null)

const currentAssignments = computed(() => {
  if (assignments?.value.length === 0 || !assignments?.value)
    return []
  return assignments?.value?.filter((x) => (x.set.end === null || new Date(x.set.end) > new Date()) && x.finished_at === null)
})

const pastAssignments = computed(() => {
  if (assignments?.value.length === 0 || !assignments?.value)
    return []
  return assignments?.value?.filter((x) => x.finished_at !== null || (new Date(x.set.end) < new Date() && x.set.end !== null))
})

ky.get('assignment').json().then((data) => {
  assignments.value = data.assignments
})


</script>

<style scoped>

</style>
