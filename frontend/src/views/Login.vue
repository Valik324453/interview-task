
<template>
  <div class="container mt-5">
    <h2>Login</h2>
    <form @submit.prevent="loginuser">
      <div class="mb-3">
        <label for="login" class="form-label">Login</label>
        <input type="text" class="form-control" v-model="login" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" v-model="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <div v-if="error" class="mt-3 text-danger">{{ error }}</div>
    </form>
    <div class="mt-3">
      <router-link to="/register">Don't have an account? Register</router-link>
    </div>
  </div>
</template>

<script>
import { API_HOST } from '@/config';
export default {
  data() {
    return {
      login: '',
      password: '',
      error: ''
    };
  },
  methods: {
    async loginuser() {
        try {
            const loginData = JSON.stringify({
                login: this.login,
                password: this.password
            });
            console.log("Sending data:", loginData);

            const response = await fetch(`${API_HOST}/login.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: loginData,
                credentials: 'include'
            });

            console.log("Response received:", response);

            // Проверка статуса ответа
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Чтение текста ответа для логирования
            const text = await response.text();
            console.log("Raw response text:", text);

            // Попытка парсинга JSON
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error(`Failed to parse JSON: ${e.message}`);
            }
            console.log("test1");
            console.log("Data received:", data);
            

            if (data.status === 'success') {
              console.log('Login successful, redirecting to /home');
                this.$router.push('/home');
            } else {
              console.log('Login failed:', data.message);
                this.error = data.message;
            }
        } catch (error) {
            console.error('Error:', error);
            this.error = 'Failed to connect to the server';
        }
    }
}
}
</script>