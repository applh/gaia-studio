<script setup lang="ts">
import { RouterView } from 'vue-router'
import Menu from './components/Menu.vue'

</script>

<template>
  <el-container>
    <el-header>
      <el-row>
        <el-col :span="24">
          <Menu />
        </el-col>
      </el-row>
    </el-header>
    <el-main>
      <RouterView />
    </el-main>
    <el-footer>
      <el-row>
        <el-col :span="24">
          <Menu />
        </el-col>
      </el-row>
    </el-footer>
  </el-container>
</template>

<style scoped>

@media (min-width: 1024px) {}
</style>
