<template>
  <div
    v-if='ongoing'
    class='d-flex align-center'>
    <v-icon
      :color='severity'
      class='mr-2'

    >{{ severity === 'green' ? 'mdi-clock-time-four' : 'mdi-clock-alert' }}</v-icon>
    <span>{{$t('zostava')}} {{ timeLeft }}</span>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  date: {
    type: String,
    required: true,
  },
})

const timeLeft = ref(null)

const remainingTime = (() => {
  const now = new Date()
  const endDate = new Date(props.date)
  return endDate - now
})

const ongoing = computed(() => {
  return new Date(props.date) > new Date()
})

const severity = computed(() => {
  if (remainingTime() < 1000 * 60 * 60) {
    return 'red'
  } else if (remainingTime() < 1000 * 60 * 60 * 24) {
    return 'orange'
  } else {
    return 'green'
  }
})

const timeLeftString = (() => {
  if (remainingTime() <= 0) {
    clearInterval(interval)
    return
  }

  const days = Math.floor(remainingTime() / (1000 * 60 * 60 * 24))
  const hours = Math.floor((remainingTime() % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
  const minutes = Math.floor((remainingTime() % (1000 * 60 * 60)) / (1000 * 60))
  const seconds = Math.floor((remainingTime() % (1000 * 60)) / 1000)

  if (days > 0) {
    return `${days} day${days > 1 ? 's' : ''}`
  } else if (hours > 0) {
    return `${hours}h ${minutes}min`
  } else {
    return `${minutes}min ${seconds}sec`
  }
})

const update = () => {
  timeLeft.value = timeLeftString()
}


const interval = setInterval(update, 1000)
update()

</script>


<style scoped>

</style>
