<script setup lang="ts">
import { usePageStore } from '../stores/page'

import type Node from 'element-plus/es/components/tree/src/model/node'
import type { DragEvents } from 'element-plus/es/components/tree/src/model/useDragNode'
import type {
  AllowDropType,
  NodeDropType,
} from 'element-plus/es/components/tree/src/tree.type'

const handleDragStart = (node: Node, ev: DragEvents) => {
  console.log('drag start', node, ev)
}
const handleDragEnter = (
  draggingNode: Node,
  dropNode: Node,
  ev: DragEvents
) => {
  console.log('tree drag enter:', dropNode.label, ev)
}
const handleDragLeave = (
  draggingNode: Node,
  dropNode: Node,
  ev: DragEvents
) => {
  console.log('tree drag leave:', dropNode.label, ev)
}
const handleDragOver = (draggingNode: Node, dropNode: Node, ev: DragEvents) => {
  console.log('tree drag over:', dropNode.label, ev)
}
const handleDragEnd = (
  draggingNode: Node,
  dropNode: Node,
  dropType: NodeDropType,
  ev: DragEvents
) => {
  console.log('tree drag end:', dropNode && dropNode.label, dropType, ev)
}

const handleDrop = (
  draggingNode: Node,
  dropNode: Node,
  dropType: NodeDropType,
  ev: DragEvents
) => {
  console.log('tree drop:', dropNode.label, dropType, ev)
}
const allowDrop = (draggingNode: Node, dropNode: Node, type: AllowDropType) => {
  if (dropNode.data.label === 'Level two 3-1') {
    return type !== 'inner'
  } else {
    return true
  }
}
const allowDrag = (draggingNode: Node) => {
  return !draggingNode.data.label.includes('Level three 3-1-1')
}

const pageStore = usePageStore()

</script>

<template>
  <el-row>
    <h1>Settings</h1>
  </el-row>
  <el-row>
    <el-col :span="12">
      <el-tree :allow-drop="allowDrop" :allow-drag="allowDrag" :data="pageStore.tree_data" draggable default-expand-all node-key="id"
        @node-drag-start="handleDragStart" @node-drag-enter="handleDragEnter" @node-drag-leave="handleDragLeave"
        @node-drag-over="handleDragOver" @node-drag-end="handleDragEnd" @node-drop="handleDrop" />
    </el-col>
    <el-col :span="12">
      <el-tree :allow-drop="allowDrop" :allow-drag="allowDrag" :data="pageStore.tree_data" draggable default-expand-all node-key="id"
        @node-drag-start="handleDragStart" @node-drag-enter="handleDragEnter" @node-drag-leave="handleDragLeave"
        @node-drag-over="handleDragOver" @node-drag-end="handleDragEnd" @node-drop="handleDrop" />
    </el-col>
  </el-row>
</template>

<style>
@media (min-width: 1024px) {}
</style>
