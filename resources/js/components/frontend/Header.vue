<template>

<header class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
      <RouterLink to="/">
    <img
      src="https://cdn-icons-png.flaticon.com/512/1055/1055688.png" 
      alt="Logo"
      class="h-10 w-auto"
    />
  </RouterLink>
      <nav class="space-x-4">
        <RouterLink to="/" class="hover:underline">Home</RouterLink>
        <RouterLink to="/about" class="hover:underline">About</RouterLink>
        <RouterLink to="/login" class="hover:underline">Login</RouterLink>
      </nav>
    </header>
</template>