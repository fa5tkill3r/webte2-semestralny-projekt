<template>
  <h3>{{$t('solutionDotDot')}}</h3>
  <v-row dense='dense'>
    <v-col>
      <div
        ref='mfDiv' class='mathlive'
        :style='boxShadow'></div>
    </v-col>
    <v-col cols='auto'>
      <v-btn
        v-if='!disabled'
        height='100%'
        :disabled='loading'
        :loading='loading'
        @click='update'
      >
        {{$t('confirm')}}
      </v-btn>
    </v-col>
  </v-row>
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
const mfDiv = ref(null)
const loading = ref(false)

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

watch(() => props.disabled, (disabled) => {
  mf.value.disabled = disabled
})


const update = () => {
  loading.value = true
  emit('update', mf.value.value)
}

onMounted(() => {
  mf.value = new MathfieldElement({
  })
  mf.value.value = props.value
  mf.value.style.width = '100%'
  mf.value.disabled = props.disabled
  mf.value.readOnly = props.disabled


  mfDiv.value.appendChild(mf.value)
})

</script>

<style scoped>
.mathlive {
  font-size: 32px;
  padding: 8px;
  border-radius: 8px;
  border: 1px solid rgba(0, 0, 0, .3);
  box-shadow: 0 0 8px rgba(0, 1, 0, .2);
  --caret-color: red;
  --selection-background-color: lightgoldenrodyellow;
  --selection-color: darkblue;
}
</style>
