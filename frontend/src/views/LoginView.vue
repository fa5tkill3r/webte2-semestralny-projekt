<template>
  <v-container>
    <v-row
      justify='center'
    >

      <v-col cols='auto' md='6'>
        <v-alert
          v-model='alert.show'
          closable
          :icon='alert.icon'
          :title='alert.title'
          :text='alert.text'
          :type='alert.type'
          class='mb-3'
        ></v-alert>
        <v-card>
          <v-card-title>
            <span class='headline'>{{ $t('login') }}</span>
          </v-card-title>

          <v-card-text>
            <v-form @keyup.enter='login'>
              <v-text-field
                v-model='email.value.value'
                :error-messages='email.errorMessage.value'
                :label="$t('email')"
                required
              ></v-text-field>

              <v-text-field
                v-model='password.value.value'
                :error-messages='password.errorMessage.value'
                :label="$t('password')"
                :type='showPassword ? "text" : "password"'
                :append-icon='showPassword ? "mdi-eye" : "mdi-eye-off"'
                required
                @click:append='showPassword = !showPassword'
              ></v-text-field>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color='primary'
              :loading='loading'
              @click='login'
            >{{$t('login')}}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>

import { ref } from 'vue'
import { useAppStore } from '@/store/app'
import * as yup from 'yup'
import { useField, useForm } from 'vee-validate'
import router from '@/router'
import { t } from '@/lib/i18n'

const loading = ref(false)

const store = useAppStore()

const alert = ref({
  show: false,
  type: 'success',
  title: '',
  text: '',
  icon: '',
})

const showPassword = ref(false)

if (store.registeredNow) {
  alert.value = {
    show: true,
    type: 'success',
    title: t('Success'),
    text: t('Youhavebeenregisteredsuccessfully'),
    icon: 'mdi-check-circle-outline',
  }
  store.registeredNow = false
}


const validationSchema = yup.object().shape({
  email: yup.string().required().email(),
  password: yup.string().required(t('Thisfieldisrequired')),
})

const { handleSubmit, validate } = useForm({
  validationSchema,
})

const email = useField('email', validationSchema)
const password = useField('password', validationSchema)


const onSubmit = handleSubmit(async (values) => {
  loading.value = true
  try {
    await store.login(values.email, values.password)
    await router.push({ name: 'Home' })
  } catch (e) {
    const error = await e.response.json()
    alert.value = {
      show: true,
      type: 'error',
      title: t('Error'),
      text: error.message,
      icon: 'mdi-alert-circle-outline',
    }
  } finally {
    loading.value = false
  }
})

const login = async () => {
  const valid = await validate()
  if (valid.valid)
    onSubmit()
}
</script>

<style scoped>

</style>
