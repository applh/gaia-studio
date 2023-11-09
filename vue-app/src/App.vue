<script setup lang="ts">
import { RouterView } from 'vue-router'
import NavMenu from './components/NavMenu.vue'
import { usePageStore } from './stores/page'

// get hostname from url
const host = window.location.host
console.log(window.location.host)

// check if env has api_url
let api_url = import.meta.env.VITE_XPS_API_URL ?? ('//' + host + '/wp-json/xp-studio/v1/api')
console.log(api_url)

// load json from api_url
const loadJson = async (url: string, fd: FormData) => {
  const response = await fetch(url, {
    method: 'POST',
    body: fd
  })
  const json = await response.json()
  return json
}
let fd = new FormData()
fd.append('@class', 'code')
fd.append('@method', 'test')

const pageStore = usePageStore()

loadJson(api_url, fd).then(data => {
  // get time if success
  pageStore.last_fetch = data?.code_test?.time ?? '';
  pageStore.table_data = data?.code_test?.table_data ?? [];
  console.log('data', data)
})

// define pinia store


</script>

<template>
  <el-container>
    <el-header>
      <el-row>
        <el-col :span="24">
          <NavMenu />
        </el-col>
      </el-row>
    </el-header>
    <el-main>
      <RouterView />
    </el-main>
    <el-footer>
      <el-row>
        <el-col :span="24">
          <NavMenu />
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <p>© 2021 - {{ host }} - {{  pageStore.last_fetch }}</p>
        </el-col>
      </el-row>
    </el-footer>
  </el-container>
</template>

<style scoped>
@media (min-width: 1024px) {}
</style>
