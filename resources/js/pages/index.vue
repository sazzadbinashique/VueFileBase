<template>
  <Layout>
  <div class="flex-1 p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

  <section class="lg:col-span-3">
        <h2 class="text-2xl font-semibold mb-4">Latest Posts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="i in 12" :key="i" class="bg-white rounded shadow hover:shadow-lg transition p-2">
            <img
              :src="`https://picsum.photos/seed/post${i}/400/240`"
              alt="Card"
              class="h-40 w-full object-cover rounded-t"
            />
            <div class="p-2">
              <h3 class="text-lg font-semibold">Card Title {{ i }}</h3>
              <p class="text-sm text-gray-600">Some short description for this card.</p>
            </div>
          </div>
        </div>
      </section>
  </div>
   

  </Layout>
</template>

<script setup>
import Layout from '@/layouts/Layout.vue'


</script>
