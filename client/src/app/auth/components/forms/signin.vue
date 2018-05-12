<script>
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      loading: false,
      form: { email: '', password: '' },
      rules: {
        email: [
          {
            required: true,
            message: 'Please type in your email',
            trigger: 'blur',
          },
          {
            type: 'email',
            message: 'Please enter a valid email',
            trigger: 'blur',
          },
        ],
        password: [
          {
            required: true,
            message: 'Please type in your password',
            trigger: 'blur',
          },
          {
            min: 6,
            message: 'Minimum length is 6 characters or digits',
            trigger: 'blur',
          },
        ],
      },
    };
  },
  methods: {
    ...mapActions(['attemptLogin', 'setMessage']),

    submit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.loading = true;
          const { email, password } = this.form;

          this.attemptLogin({ email, password })
            .then(() => {
              this.loading = false;
              this.$router.push({ name: 'dashboard.index' });
            })
            .catch(() => {
              this.loading = false;
            });
        }
      });
    },
  },
};
</script>

<template>
  <div>
    <el-form ref="form" :rules="rules" :model="form" label-width="120px" label-position="top">

      <el-form-item prop='email'>
        <el-input v-model="form.email" placeholder='Email'></el-input>
      </el-form-item>
      
      <el-form-item prop='password'>
        <el-input v-model="form.password" type='password' placeholder='Password'></el-input>
      </el-form-item> 
      
      <el-form-item class='form-button-space'>
        <el-button :loading="loading" type="primary" class='full-width' @click="submit">Sign in</el-button>
      </el-form-item>

    </el-form>

    <div class='auth-swap'>Don't have an account?
      <router-link class='auth-redirect' :to="{ name: 'auth.signup' }">
        <el-button type="text">Sign Up</el-button>
      </router-link>
    </div>

  </div>

</template>











