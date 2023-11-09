<script setup lang="ts">
import { RouterView } from 'vue-router'
import { usePageStore } from './stores/page'
import NavMenu from './components/XpsNavMenu.vue'

const pageStore = usePageStore()

// get hostname from url
const host = window.location.host
console.log(window.location.host)

// check if env has api_url
let api_url = import.meta.env.VITE_XPS_API_URL ?? ('//' + host + '/wp-json/xp-studio/v1/api')
console.log(api_url)

// get from #app attribute data-wp-nonce
const wp_nonce = document.getElementById('app')?.getAttribute('data-wp-nonce') ?? ''
console.log('wp_nonce', wp_nonce)
// set wp_nonce to pageStore
pageStore.wp_nonce = wp_nonce


// load json from api_url
const loadJson = async (url: string, fd: FormData) => {
  // if exists add nonce to header X-WP-Nonce
  let headers = new Headers()
  if (pageStore.wp_nonce) {
    headers.append('X-WP-Nonce', pageStore.wp_nonce)
    // add to fd as _wpnonce
    fd.append('_wpnonce', pageStore.wp_nonce)
  }
  else {
    // params.headers.append('X-WP-Nonce-Empty', pageStore.wp_nonce)
  }

  // add timestap to fd
  fd.append('timestamp', Date.now().toString())

  let params = {
    method: 'POST',
    headers: headers,
    body: fd,
  }

  const response = await fetch(url, params)
  const json = await response.json()
  return json
}
let fd = new FormData()
fd.append('@class', 'code')
fd.append('@method', 'test')


loadJson(api_url, fd).then(data => {
  // get time if success
  pageStore.last_fetch = data?.code_test?.time ?? '';
  pageStore.table_data = data?.code_test?.table_data ?? [];
  pageStore.tree_data = data?.code_test?.tree_data ?? [];

  console.log('data', data)
})


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
          <p>© 2023 - {{ host }} - {{  pageStore.last_fetch }} - {{ pageStore.wp_nonce }}</p>
        </el-col>
      </el-row>
    </el-footer>
  </el-container>
</template>

<style scoped>
@media (min-width: 1024px) {}
</style>
