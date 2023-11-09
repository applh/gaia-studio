<script setup lang="ts">
import {
  Check,
  Delete,
  Edit,
  Message,
  Search,
  Star,
} from '@element-plus/icons-vue'

defineProps<{
  msg: string
}>()

</script>

<template>
  <el-row>
    <div class="greetings">
      <h1 class="green">{{ msg }}</h1>
      <h2 class="green">CMS + Web App + Container App + WP plugin</h2>
      <p>Here you can extend your WP site with new and powerful features</p>
    </div>
  </el-row>
  <el-row>
    <el-button type="success">Success</el-button>
    <el-button :icon="Search" circle />
    <el-button type="primary" :icon="Edit" circle />
    <el-button type="success" :icon="Check" circle />
    <el-button type="info" :icon="Message" circle />
    <el-button type="warning" :icon="Star" circle />
    <el-button type="danger" :icon="Delete" circle />
  </el-row>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  position: relative;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {

  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>
