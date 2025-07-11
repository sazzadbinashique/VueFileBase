<template>
<Layout>
  <div class="max-w-6xl mx-auto p-6 space-y-6">
    <!-- 🔷 Card Box with Breadcrumb -->
    <div class="bg-white shadow rounded p-4">
      <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="list-reset flex items-center space-x-2">
          <li>
            <RouterLink to="/" class="text-blue-600 hover:underline">Home</RouterLink>
          </li>
          <li>/</li>
          <li class="text-gray-700 font-semibold">About</li>
        </ol>
      </nav>
    </div>

    <!-- 🔷 Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="i in 6"
        :key="i"
        class="bg-gray-50 rounded shadow p-4 hover:shadow-lg transition duration-200"
      >
        <img
          :src="`https://picsum.photos/seed/card${i}/400/240`"
          alt="Card"
          class="h-40 w-full object-cover rounded mb-3"
        />
        <h3 class="text-lg font-semibold text-gray-800">Card Title {{ i }}</h3>
        <p class="text-sm text-gray-600">
          Some short description for this card. Add more content if needed.
        </p>
      </div>
    </div>
  </div>
  </Layout>
</template>


<script setup>
import Layout from '@/layouts/Layout.vue'


</script>
