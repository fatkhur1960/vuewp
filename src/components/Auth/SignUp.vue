<template>
  <form @submit.prevent="submit" class="needs-validation" novalidate>
    <div class="row">
      <!-- First Name -->
      <div
        class="input-group col-lg-6 mb-4 text-left"
        :class="{ hasError: $v.form.first_name.$error }"
      >
        <div class="input-group-prepend">
          <span class="input-group-text bg-white px-4 border-md border-right-0">
            <i class="fa fa-user text-muted"></i>
          </span>
        </div>
        <input
          id="firstName"
          type="text"
          name="fist_name"
          placeholder="Nama Depan"
          class="form-control bg-white border-left-0 border-md"
          v-model="form.first_name"
          @input="$v.form.first_name.$touch()"
        />
        <div class="invalid-feedback block" v-if="$v.form.first_name.$error">
          <div v-if="!$v.form.first_name.required">
            Nama depan tidak boleh kosong!
          </div>
        </div>
      </div>

      <!-- First Name -->
      <div
        class="input-group col-lg-6 mb-4 text-left"
        :class="{ hasError: $v.form.last_name.$error }"
      >
        <div class="input-group-prepend">
          <span class="input-group-text bg-white px-4 border-md border-right-0">
            <i class="fa fa-user text-muted"></i>
          </span>
        </div>
        <input
          id="lastName"
          type="text"
          name="fist_name"
          placeholder="Nama Belakang"
          class="form-control bg-white border-left-0 border-md"
          v-model="form.last_name"
          @input="$v.form.last_name.$touch()"
        />
        <div class="invalid-feedback block" v-if="$v.form.last_name.$error">
          <div v-if="!$v.form.last_name.required">
            Nama belakang tidak boleh kosong!
          </div>
        </div>
      </div>

      <!-- UserName -->
      <div
        class="input-group col-lg-12 mb-4 text-left"
        :class="{ hasError: $v.form.username.$error }"
      >
        <div class="input-group-prepend">
          <span class="input-group-text bg-white px-4 border-md border-right-0">
            <i class="fa fa-user text-muted"></i>
          </span>
        </div>
        <input
          id="fullName"
          type="text"
          name="full_name"
          placeholder="Username"
          class="form-control bg-white border-left-0 border-md"
          v-model="form.username"
          @input="$v.form.username.$touch()"
        />
        <div class="invalid-feedback block" v-if="$v.form.username.$error">
          <div v-if="!$v.form.username.required">
            Username tidak boleh kosong!
          </div>
          <div v-if="!$v.form.username.validUsername">
            Username tidak valid!
          </div>
          <div v-if="!$v.form.username.minLength">
            Username harus terdiri dari
            {{ $v.form.username.$params.minLength.min }} karakter!
          </div>
        </div>
      </div>

      <!-- Email Address -->
      <div
        class="input-group col-lg-12 mb-4 text-left"
        :class="{ hasError: $v.form.email.$error }"
      >
        <div class="input-group-prepend">
          <span class="input-group-text bg-white px-4 border-md border-right-0">
            <i class="fa fa-envelope text-muted"></i>
          </span>
        </div>
        <input
          id="email"
          type="email"
          name="email"
          placeholder="Alamat Email"
          class="form-control bg-white border-left-0 border-md"
          v-model="form.email"
          @input="$v.form.email.$touch()"
        />
        <div class="invalid-feedback block" v-if="$v.form.email.$error">
          <div v-if="!$v.form.email.required">Email tidak boleh kosong!</div>
          <div v-if="!$v.form.email.email">Email tidak valid!</div>
        </div>
      </div>

      <!-- Password -->
      <div
        class="input-group col-lg-12 mb-4 text-left"
        :class="{ hasError: $v.form.password.$error }"
      >
        <div class="input-group-prepend">
          <span class="input-group-text bg-white px-4 border-md border-right-0">
            <i class="fa fa-lock text-muted"></i>
          </span>
        </div>
        <input
          id="password"
          :type="`${showPasswd ? 'text' : 'password'}`"
          name="password"
          placeholder="Password"
          class="form-control no-border-r bg-white border-left-0 border-right-0 border-md"
          v-model="form.password"
          @input="$v.form.password.$touch()"
          required
        />
        <div class="input-group-append">
          <span class="input-group-text bg-white px-4 border-md border-left-0">
            <a href="#" @click.prevent="showPasswd = !showPasswd">
              <i :class="`fas ${showPasswd ? 'fa-eye' : 'fa-eye-slash'}`"></i>
            </a>
          </span>
        </div>
        <div class="invalid-feedback block" v-if="$v.form.password.$error">
          <div v-if="!$v.form.password.required">
            Password tidak boleh kosong!
          </div>
          <div v-if="!$v.form.password.minLength">
            Password minimal harus terdiri dari
            {{ $v.form.password.$params.minLength.min }} karakter!
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group col-lg-12 mx-auto mb-0">
        <button class="btn btn-success btn-block py-2">
          <span v-if="!authLoading" class="font-weight-bold block p-1">
            Buat akun Anda
          </span>
          <div v-else class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </button>
      </div>

      <!-- Divider Text -->
      <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
        <div class="border-bottom w-100 ml-5"></div>
        <span class="px-2 small text-muted font-weight-bold text-muted">
          OR
        </span>
        <div class="border-bottom w-100 mr-5"></div>
      </div>

      <!-- Social Login -->
      <div class="form-group col-lg-12 mx-auto">
        <div class="row">
          <div class="col-lg-6">
            <a href="#" class="btn btn-primary btn-block py-2 btn-facebook">
              <i class="fab fah fa-facebook mr-2"></i>
              <span class="font-weight-bold">
                Daftar dengan Facebook
              </span>
            </a>
          </div>
          <div class="col-lg-6">
            <a href="#" class="btn btn-primary btn-block py-2 btn-twitter">
              <i class="fab fah fa-twitter mr-2"></i>
              <span class="font-weight-bold">
                Daftar dengan Twitter
              </span>
            </a>
          </div>
        </div>
      </div>

      <!-- Already Registered -->
      <div class="text-center w-100">
        <p class="text-muted font-weight-bold">
          Sudah punya akun?
          <a href="#" @click.prevent="cb('sign-in')" class="text-primary ml-2">
            Masuk
          </a>
        </p>
      </div>
    </div>
  </form>
</template>
<script>
import { mapGetters } from 'vuex'
import { required, minLength, email } from 'vuelidate/lib/validators'
import { validUsername } from '../../utils/validators'

export default {
  props: {
    cb: {
      type: Function,
      required: true,
    },
  },
  data: () => ({
    showPasswd: false,
    form: {
      email: '',
      username: '',
      password: '',
      first_name: '',
      last_name: '',
    },
  }),
  validations: {
    form: {
      username: {
        required,
        minLength: minLength(8),
        validUsername,
      },
      email: {
        required,
        email,
      },
      password: {
        required,
        minLength: minLength(8),
      },
      first_name: {
        required,
      },
      last_name: {
        required,
      },
    },
  },
  methods: {
    submit() {
      this.$v.form.$touch()
      if (this.$v.form.$pending || this.$v.form.$error) return
      this.$store.dispatch('signUp', { payload: this.form })
    },
  },
  computed: {
    ...mapGetters({
      authLoading: 'authLoading',
      registerResult: 'registerResult',
    }),
  },
  watch: {
    registerResult: function (res) {
      if (res.code !== 'success') {
        this.$noty.error(res.message)
      } else {
        this.$noty.success(res.message)
        this.cb('sign-in')
      }
    },
  },
}
</script>
<style lang="css" scoped>
/*
*
* ==========================================
* CUSTOM UTIL CLASSES
* ==========================================
*
*/
.block {
  display: block;
}

.border-md {
  border-width: 2px;
}

.btn-facebook {
  background: #405d9d;
  border: none;
}

.btn-facebook:hover,
.btn-facebook:focus {
  background: #314879;
}

.btn-twitter {
  background: #42aeec;
  border: none;
}

.btn-twitter:hover,
.btn-twitter:focus {
  background: #1799e4;
}

.form-control:not(select) {
  padding: 1.5rem 0.5rem;
}

select.form-control {
  height: 52px;
  padding-left: 0.5rem;
}

.form-control::placeholder {
  color: #ccc;
  font-weight: bold;
  font-size: 0.9rem;
}
.form-control:focus {
  box-shadow: none;
}
.input-group > .form-control:not(:last-child),
.input-group > .input-group-append:not(:last-child) > .input-group-text {
  border-top-right-radius: 0.25em;
  border-bottom-right-radius: 0.25em;
}
.no-border-r {
  border-radius: 0 !important;
}
.hasError .form-control,
.hasError .input-group-text,
.hasError .custom-select {
  border-color: #dc3545;
}
</style>
