<template>
  <section>
    <div class="cards-1">
      <div class="container">
        <div class="row py-5 mt-4 align-items-center">
          <!-- For Demo Purpose -->
          <div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
            <img
              src="https://i.imgur.com/uNGdWHi.png"
              alt=""
              class="img-fluid mb-3 d-none d-md-block"
            />
            <h1>{{ title }}</h1>
          </div>

          <!-- Registeration Form -->
          <div class="col-md-7 col-lg-6 ml-auto">
            <LoginForm v-if="action === 'sign-in'" :cb="switchForm" />
            <RegisterForm v-else-if="action === 'sign-up'" :cb="switchForm" />
            <ResetForm v-else :cb="switchForm" />
          </div>
        </div>
      </div>
    </div>
    <!-- Breadcrumbs -->
    <div class="ex-basic-1">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="breadcrumbs">
              <router-link to="/">Beranda</router-link>
              <i class="fa fa-angle-double-right"></i>
              <span>{{ title }}</span>
            </div>
            <!-- end of breadcrumbs -->
          </div>
          <!-- end of col -->
        </div>
        <!-- end of row -->
      </div>
      <!-- end of container -->
    </div>
    <!-- end of ex-basic-1 -->
    <!-- end of breadcrumbs -->
  </section>
</template>
<script>
import LoginForm from './SignIn'
import ResetForm from './ResetPassword'
import RegisterForm from './SignUp'

export default {
  components: { LoginForm, RegisterForm, ResetForm },

  data: () => ({
    action: 'sign-in',
    title: 'Masuk',
    actions: ['sign-in', 'sign-up', 'reset-password'],
  }),

  methods: {
    switchForm(val) {
      this.$router.replace({
        name: 'Auth',
        params: { action: val },
      })
    },
  },
  beforeMount() {
    const action = this.$route.params.action
    if (this.actions.includes(action)) {
      this.action = action
      this.title =
        this.action === 'sign-in'
          ? 'Masuk'
          : this.action === 'sign-up'
          ? 'Buat Akun'
          : 'Reset Password'
    } else {
      this.$router.replace({
        name: 'Error404',
      })
    }
  },
}
</script>
