<template>
    <div class="container mt-5">
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div class="mb-3">
          <label for="first_name" class="form-label">First Name</label>
          <input type="text" class="form-control" v-model="first_name" required>
        </div>
        <div class="mb-3">
          <label for="last_name" class="form-label">Last Name</label>
          <input type="text" class="form-control" v-model="last_name" required>
        </div>
        <div class="mb-3">
          <label for="login" class="form-label">Login</label>
          <input type="text" class="form-control" v-model="login" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" v-model="password" required>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" v-model="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <div v-if="error" class="mt-3 text-danger">{{ error }}</div>
      </form>
    </div>
  </template>
  
  <script>
  import { API_HOST } from '@/config';
  export default {
    data() {
      return {
        first_name: '',
        last_name: '',
        login: '',
        password: '',
        confirm_password: '',
        error: ''
      };
    },
    methods: {
      async register() {
        if (this.password !== this.confirm_password) {
          this.error = 'Passwords do not match';
          return;
        }
        const response = await fetch(`${API_HOST}/register.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            first_name: this.first_name,
            last_name: this.last_name,
            login: this.login,
            password: this.password
          }),
          credentials: 'include'
        });
        const data = await response.json();
        if (data.status === 'success') {
          this.$router.push('/home');
        } else {
          this.error = data.message;
        }
      }
    }
  }
  </script>