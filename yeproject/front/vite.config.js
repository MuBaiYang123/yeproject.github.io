import {
  defineConfig,
  loadEnv
} from 'vite'
import vue from '@vitejs/plugin-vue'



// https://vitejs.dev/config/
export default defineConfig({
  base: '', //这里
  plugins: [vue()],
  optimizeDeps: {
    include: ['axios'],
  },
})