<template>
  <form @submit.prevent="submit" class="needs-validation" novalidate>
    <div class="row">
      <!-- Email Address -->
      <div
        class="input-group col-lg-12 mb-4 text-left"
        :class="{ hasError: $v.form.user_login.$error }"
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
          v-model="form.user_login"
          @input="$v.form.user_login.$touch()"
          required
        />
        <div class="invalid-feedback block" v-if="$v.form.user_login.$error">
          <div v-if="!$v.form.user_login.required">Email tidak boleh kosong!</div>
          <div v-if="!$v.form.user_login.email">Email tidak valid!</div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group col-lg-12 mx-auto mb-0">
        <button type="submit" class="btn btn-success btn-block py-2">
          <span v-if="!authLoading" class="font-weight-bold block p-1">
            Kirim
          </span>
          <div v-else class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </button>
      </div>
    </div>
  </form>
</template>
<script>
import { required, email } from 'vuelidate/lib/validators'
import { mapGetters } from 'vuex'

export default {
  props: {
    cb: {
      type: Function,
      required: true,
    },
  },
  data: () => ({
    form: {
      user_login: '',
    },
  }),
  validations: {
    form: {
      user_login: {
        required,
        email,
      },
    },
  },
  methods: {
    submit() {
      this.$v.form.$touch()
      if (this.$v.form.$pending || this.$v.form.$error) return
      this.$store.dispatch('resetPassword', {
        payload: this.form,
      })
    },
  },
  computed: {
    ...mapGetters({
      authLoading: 'authLoading',
      resetPasswdResult: 'resetPasswdResult',
    }),
  },
  watch: {
    resetPasswdResult: function (res) {
      if (res.code !== 200) {
        this.$noty.error(res.message)
      } else {
        this.$noty.success(res.message)
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

.hasError .form-control,
.hasError .input-group-text,
.hasError .custom-select {
  border-color: #dc3545;
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
</style>
