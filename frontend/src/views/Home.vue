<template>
  <div class="container mt-5">
    <h2>Welcome, {{ user }}</h2>
    <button class="btn btn-danger" @click="logout">Logout</button>

    <br><br>
    <h3>Create link</h3>
    <div class="input-group mb-3">
      <span class="input-group-text" id="inputGroup-sizing-default">Destination</span>
      <input type="text" class="form-control" v-model="destination" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text" id="inputGroup-sizing-default">Custom back-half (optional)</span>
      <input type="text" class="form-control" v-model="customBackHalf" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>
    <button class="btn btn-success" @click="createLink">Create</button>
    <p v-if="error" class="text-danger">{{ error }}</p>

    <!-- Таблица для отображения URL-ов -->
    <table v-if="urls.length" class="table table-striped mt-4">
      <thead>
        <tr>
          <th>URL</th>
          <th>Link</th>
          <th>Counter</th>
          <th v-if="user === 'admin'">User</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="url in urls" :key="url.short_code">
          <td>{{ url.long_url }}</td>
          <td>
            <a :href="`http://localhost/` + url.short_code" target="_blank">
              {{ `http://localhost/` + url.short_code }}
            </a>
          </td>
          <td>{{ url.transition_count }}</td>
          <td v-if="user === 'admin'">{{ url.user_login }}</td>
        </tr>
      </tbody>
    </table>
    <p v-else>No URLs found</p>
  </div>
</template>

<script>
import { isValidUrl } from '../utils';
import { API_HOST } from '@/config';

export default {
  data() {
    return {
      user: '',
      destination: '',
      customBackHalf: '',
      error: '',
      shortUrl: '',
      urls: [] // Массив для хранения URL-ов
    };
  },
  async created() {
    await this.fetchUser();
    await this.fetchUserUrls();
  },
  methods: {
    async fetchUser() {
      try {
        const response = await fetch(`${API_HOST}/user.php`, {
          method: 'GET',
          credentials: 'include'
        });
        const data = await response.json();
        if (data.status === 'success') {
          this.user = data.user;
          console.log('User:', data.user);
        } else {
          this.error = data.message;
        }
      } catch (error) {
        console.error('Error:', error);
        this.error = 'Failed to fetch user data';
      }
    },
    async fetchUserUrls() {
      try {
        const response = await fetch(`${API_HOST}/get_user_urls.php`, {
          method: 'GET',
          credentials: 'include'
        });
        const data = await response.json();
        if (data.status === 'success') {
          this.urls = data.urls;
        } else {
          this.error = data.message;
        }
      } catch (error) {
        console.error('Error:', error);
        this.error = 'Failed to fetch URLs';
      }
    },
    async logout() {
      try {
        const response = await fetch(`${API_HOST}/logout.php`, {
          method: 'GET',
          credentials: 'include'
        });
        if (response.ok) {
          this.$router.push('/login');
        } else {
          console.error('Logout failed');
        }
      } catch (error) {
        console.error('Error:', error);
      }
    },
    async createLink() {
      if (!isValidUrl(this.destination)) {
        this.error = 'Invalid URL';
        return;
      }

      try {
        const response = await fetch(`${API_HOST}/save_url.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            url: this.destination,
            customBackHalf: this.customBackHalf
          }),
          credentials: 'include'
        });

        if (!response.ok) {
          const errorData = await response.json();
          console.error('Error:', errorData);
          this.error = errorData.message || 'Failed to create short URL';
          return;
        }

        const data = await response.json();
        if (data.status === 'success') {
          this.shortUrl = data.short_url;
          this.error = '';
          this.fetchUserUrls(); // обновляем список URL-ов
        } else {
          this.error = data.message;
        }
      } catch (error) {
        console.error('Error:', error);
        this.error = 'Failed to create short URL';
      }
    }
  }
}
</script>
