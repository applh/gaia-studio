
<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'

const activeIndex = ref('0')
const handleSelect = (key: string, keyPath: string[]) => {
  console.log(key, keyPath)
}

const menu = [
  {
    name: 'Home',
    path: '/',
  },
  {
    name: 'Settings',
    path: '/settings',
  },
  {
    name: 'About',
    path: '/about',
  },
]
</script>

<template>
    <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" :ellipsis="false" @select="handleSelect">
        <el-menu-item index="0">
            <span>GAIA Studio</span>
        </el-menu-item>
        <div class="flex-grow"></div>
        <el-menu-item v-for="(item, i) in menu" :key="item.name" :index="'' + i">
            <RouterLink :key="item.name" :to="item.path">
                {{ item.name }}
            </RouterLink>
        </el-menu-item>
        <el-sub-menu index="2">
            <template #title>Options</template>
            <el-menu-item index="2-1">item one</el-menu-item>
            <el-menu-item index="2-2">item two</el-menu-item>
            <el-menu-item index="2-3">item three</el-menu-item>
            <el-sub-menu index="2-4">
                <template #title>item four</template>
                <el-menu-item index="2-4-1">item one</el-menu-item>
                <el-menu-item index="2-4-2">item two</el-menu-item>
                <el-menu-item index="2-4-3">item three</el-menu-item>
            </el-sub-menu>
        </el-sub-menu>
    </el-menu>
</template>

<style scoped>
.flex-grow {
  flex-grow: 1;
}

</style>