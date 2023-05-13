<template>
  <div>
    <div
      id='mf' class='mathlive'
      :style='boxShadow'></div>
  </div>
</template>

<script setup>
import { MathfieldElement } from 'mathlive'

import { computed, onMounted, ref, watch } from 'vue'

const props = defineProps({
  value: {
    type: String,
    required: false,
    default: '',
  },
  disabled: {
    type: Boolean,
    required: false,
    default: false,
  },
  correct: {
    type: Boolean,
    required: false,
    default: null,
  },
})

const emit = defineEmits(['update'])
const mf = ref(null)

// box shadow computed
const boxShadow = computed(() => {
  if (props.correct === null) {
    return ''
  }
  if (props.correct) {
    return {
      boxShadow: '0 0 8px rgba(0, 255, 0, 1)',
    }
  }
  return {
    boxShadow: '0 0 8px rgba(255, 0, 0, 1)',
  }
})

// watch disabled
watch(() => props.disabled, (disabled) => {
  mf.value.disabled = disabled
})


onMounted(() => {
  mf.value = new MathfieldElement({
    readOnly: props.disabled,
  })
  mf.value.value = props.value
  mf.value.style.width = '100%'
  mf.value.disabled = props.disabled
  mf.value.readOnly = props.disabled

  mf.value.addEventListener('change', (ev) => {
    emit('update', ev.target.value)
  })

  const mfDiv = document.getElementById('mf')
  mfDiv.appendChild(mf.value)
})

</script>

<style scoped>
.mathlive {
  font-size: 32px;
  padding: 8px;
  border-radius: 8px;
  border: 1px solid rgba(0, 0, 0, .3);
  box-shadow: 0 0 8px rgba(0, 1, 0, .2);
  min-width: 5em;
  --caret-color: red;
  --selection-background-color: lightgoldenrodyellow;
  --selection-color: darkblue;
}
</style>
