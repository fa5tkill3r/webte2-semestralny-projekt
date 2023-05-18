<template>
  <v-container>
    <v-row
      justify='center'
    >
      <v-col cols='auto' md='6'>
        <v-card>
          <v-card-title>
            <span class='headline'>{{ $t('register') }}</span>
          </v-card-title>

          <v-card-text>
            <v-form
              ref='form'
              :readonly='loading'
              @submit.prevent='onSubmit'
              @keyup.enter='onSubmit'>
              <v-row>
                <v-col>
                  <v-text-field
                    v-model='firstName.value.value'
                    :error-messages='firstName.errorMessage.value'
                    :label="$t('firstName')"
                  ></v-text-field>
                </v-col>
                <v-col>
                  <v-text-field
                    v-model='lastName.value.value'
                    :error-messages='lastName.errorMessage.value'
                    :label="$t('lastName')"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-text-field
                v-model='email.value.value'
                :error-messages='email.errorMessage.value'
                label='Email'
              ></v-text-field>

              <v-text-field
                v-model='password.value.value'
                :error-messages='password.errorMessage.value'
                :label="$t('password')"
                :type='showPassword ? "text" : "password"'
                :append-icon='showPassword ? "mdi-eye" : "mdi-eye-off"'
                @click:append='showPassword = !showPassword'
              ></v-text-field>

              <v-text-field
                v-model='passwordConfirmation.value.value'
                :error-messages='passwordConfirmation.errorMessage.value'
                :label="$t('passwordAgain')"
                :type='showPassword ? "text" : "password"'
                :append-icon='showPassword ? "mdi-eye" : "mdi-eye-off"'
                @click:append='showPassword = !showPassword'
              ></v-text-field>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color='primary'
              type='submit'
              :loading='loading'
              @click='submit'
            >{{ $t('register') }}
            </v-btn>

          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>

import { useField, useForm } from 'vee-validate'
import * as yup from 'yup'
import { ref } from 'vue'
import { ky } from '@/lib/ky'
import router from '@/router'
import { useAppStore } from '@/store/app'
import { t } from '@/lib/i18n'

const form = ref(null)
const store = useAppStore()
const loading = ref(false)

const validationSchema = yup.object().shape({
  firstName: yup.string().required(t('Thisfieldisrequired')),
  lastName: yup.string().required(t('Thisfieldisrequired')),
  email: yup.string().required().email(),
  password: yup.string().required(t('Thisfieldisrequired')),
  passwordConfirmation: yup.string()
    .oneOf([yup.ref('password')], t('Passwordsmustmatch'))
    .required(t('Thisfieldisrequired')),
})

const { handleSubmit, validate } = useForm({
  validationSchema,
})

const firstName = useField('firstName', validationSchema)
const lastName = useField('lastName', validationSchema)
const email = useField('email', validationSchema)
const password = useField('password', validationSchema)
const passwordConfirmation = useField('passwordConfirmation', validationSchema)


const onSubmit = handleSubmit(async (values) => {
  try {
    loading.value = true
    await ky.post('auth/register', {
      json:
        {
          firstName: values.firstName,
          lastName: values.lastName,
          email: values.email,
          password: values.password,
        },
    }).json()

    store.registeredNow = true
    await router.push({ name: 'Login' })
  } catch (e) {
    console.log(e)
  } finally {
    loading.value = false
  }
})

const showPassword = ref(false)

const submit = async () => {
  const valid = await validate()
  if (valid.valid)
    onSubmit()
}


</script>

<style scoped>

</style>
