<template>
    <div>
        <el-form :rules="rules" ref="form" :model="form" label-width="120px" label-position="top">
          <el-form-item prop='username'>
            <el-input v-model="form.username" placeholder="Username"></el-input>
          </el-form-item> 
          <el-form-item prop='email'>
            <el-input v-model="form.email" type='email' placeholder="Email"></el-input>
          </el-form-item>           
          <el-form-item prop='password'>
            <el-input v-if='show_password == false' v-model="form.password" type='password' placeholder="Password"></el-input>
            <el-input v-if='show_password == true' v-model="form.password" type='text' placeholder="Password"></el-input>
          </el-form-item> 
                      <el-checkbox class='checkbox-wrapper full-width' label="Show Password" name="type" v-model='show_password'></el-checkbox>

          <el-form-item>
            <el-button class='full-width' type="primary" @click="submit">Sign up</el-button>
          </el-form-item>
        </el-form>
        
        <div class='auth-swap'>Already have an account?
          <router-link class='auth-redirect' :to="{ name: 'auth.signin' }">
            <el-button type="text">Sign In</el-button>
          </router-link>
        </div>
    </div>

</template>

<script>
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      show_password: false,
      form: {
        username: '',
        email: '',
        password: '',
      },
      rules: {
        username: [
          {
            required: true,
            message: 'Please type in your username',
            trigger: 'blur',
          },
        ],
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
    ...mapActions(['attemptRegister', 'setMessage']),
    submit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.loading = true;
          const { username, email, password } = this.form;

          this.attemptRegister({ username, email, password })
            .then(() => {
              this.loading = false;
              this.$router.push({ name: 'notes.index' });
              this.setMessage({ type: 'success', message: 'Welcome!' });
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