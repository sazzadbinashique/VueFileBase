import { ref } from 'vue'

const isOpen = ref(false)
const items = ref([])
const index = ref(0)

export function useLightbox() {
  function open(data, idx = 0) {
    items.value = data
    index.value = idx
    isOpen.value = true
  }

  function close() {
    isOpen.value = false
  }

  function prev() {
    if (items.value.length > 1) {
      index.value = (index.value - 1 + items.value.length) % items.value.length
    }
  }

  function next() {
    if (items.value.length > 1) {
      index.value = (index.value + 1) % items.value.length
    }
  }

  return { isOpen, items, index, open, close, prev, next }
}
