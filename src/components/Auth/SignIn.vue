<template>
  <form @submit.prevent="submit" class="needs-validation" novalidate>
    <div class="row">
      <!-- Email Address -->
      <div
        class="input-group col-lg-12 mb-4 text-left"
        :class="{ hasError: $v.form.email.$error }"
      >
        <div class="input-group-prepend">
          <span class="input-group-text bg-white px-4 border-md border-right-0">
            <i class="fa fa-user text-muted"></i>
          </span>
        </div>
        <input
          id="email"
          type="text"
          name="email"
          placeholder="Username/Alamat Email"
          class="form-control bg-white border-left-0 border-md"
          v-model="form.email"
          @input="$v.form.email.$touch()"
          required
        />
        <div class="invalid-feedback block" v-if="$v.form.email.$error">
          Username/Email tidak boleh kosong!
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
          Password tidak boleh kosong!
        </div>
      </div>

      <div class="form-group col-lg-12 mx-auto text-right">
        <a
          class="text-muted font-weight-bold"
          href="#"
          @click.prevent="cb('reset-password')"
        >
          Lupa Password?
        </a>
      </div>

      <!-- Submit Button -->
      <div class="form-group col-lg-12 mx-auto mb-0">
        <button type="submit" class="btn btn-success btn-block py-2">
          <span v-if="!authLoading" class="font-weight-bold block p-1">
            Masuk
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
            <a
              href="#"
              @click.prevent="requestOAuth('facebook')"
              class="btn btn-primary btn-block py-2 btn-facebook"
            >
              <div
                v-if="oAuthLoading && oAuthProvider === 'facebook'"
                class="spinner-border"
                role="status"
              >
                <span class="sr-only">Loading...</span>
              </div>
              <span v-else class="font-weight-bold block p-1">
                <i class="fab fah fa-facebook mr-2"></i>
                Masuk dengan Facebook
              </span>
            </a>
          </div>
          <div class="col-lg-6">
            <a
              href="#"
              @click.prevent="requestOAuth('twitter')"
              class="btn btn-primary btn-block py-2 btn-twitter"
            >
              <div
                v-if="oAuthLoading && oAuthProvider === 'twitter'"
                class="spinner-border"
                role="status"
              >
                <span class="sr-only">Loading...</span>
              </div>
              <span v-else class="font-weight-bold block p-1">
                <i class="fab fah fa-twitter mr-2"></i>
                Masuk dengan Twitter
              </span>
            </a>
          </div>
        </div>
      </div>

      <!-- Already Registered -->
      <div class="text-center w-100">
        <p class="text-muted font-weight-bold">
          Belum punya akun?
          <a href="#" @click.prevent="cb('sign-up')" class="text-primary ml-2">
            Daftar Sekarang
          </a>
        </p>
      </div>
    </div>
  </form>
</template>
<script>
import { mapGetters } from 'vuex'
import { required, minLength, email } from 'vuelidate/lib/validators'

export default {
  props: {
    cb: {
      type: Function,
      required: true,
    },
  },
  data: () => ({
    showPasswd: false,
    window: null,
    timer: null,
    form: {
      email: '',
      password: '',
    },
  }),
  computed: {
    ...mapGetters({
      authLoading: 'authLoading',
      authResult: 'authResult',
      oAuthProvider: 'oAuthProvider',
      oAuthLoading: 'oAuthLoading',
      oAuthLoaded: 'oAuthLoaded',
      oAuthResult: 'oAuthResult',
    }),
  },
  watch: {
    authResult: function (res) {
      if (res.code !== 'success') {
        this.$noty.error(res.message)
      } else {
        this.$router.push('/wp-admin')
      }
    },
    oAuthLoaded: function (res) {
      this.popupWindow(res.data.auth_uri, '', 700, 600)
      this.timer = setInterval(this.checkWindow, 1500)
    },
    oAuthResult: function (res) {
      console.log(res)
      if (res.code !== 'success') {
        this.$noty.error(res.message)
      } else {
        this.$router.push('/wp-admin')
      }
    },
  },
  validations: {
    form: {
      email: {
        required,
      },
      password: {
        required,
      },
    },
  },
  methods: {
    submit() {
      this.$v.form.$touch()
      if (this.$v.form.$pending || this.$v.form.$error) return
      this.$store.dispatch('signIn', {
        username: this.form.email,
        password: this.form.password,
      })
    },
    requestOAuth(provider) {
      this.$store.dispatch('oAuthRequest', { provider })
    },
    popupWindow(url, title, w, h) {
      var left = screen.width / 2 - w / 2
      var top = screen.height / 2 - h / 2
      this.window = window.open(
        url,
        title,
        'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' +
          w +
          ', height=' +
          h +
          ', left=' +
          left,
      )
    },
    checkWindow() {
      try {
        const params = new URLSearchParams(this.window.location.search)
        console.log(params)

        if (params.has('oauth_verifier') && params.has('oauth_token')) {
          clearInterval(this.timer)
          this.window.close()

          var verifier = params.get('oauth_verifier')
          var token = params.get('oauth_token')

          const payload = {
            oauth_token: token,
            oauth_verifier: verifier,
            creds: this.oAuthLoaded.data.creds,
          }
          this.$store.dispatch('oAuthProcess', {
            payload,
            provider: this.oAuthProvider,
          })
        } else if (params.has('code') && params.has('state')) {
          clearInterval(this.timer)
          this.window.close()

          var code = params.get('code')
          const payload = {
            code: code,
          }
          this.$store.dispatch('oAuthProcess', {
            payload,
            provider: this.oAuthProvider,
          })
        } else if (params.has('denied') || params.has('error')) {
          clearInterval(this.timer)
          this.window.close()
          this.$store.dispatch('clearOAuthLoading')
        }
      } catch (e) {
        console.log(e)
        // clearInterval(this.timer)
        // this.window.close()
        // this.$noty.error(`Failed to sign in with ${this.oAuthProvider}`)
        // this.$store.dispatch('clearOAuthLoading')
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
