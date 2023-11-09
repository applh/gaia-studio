import { ref } from 'vue'
import { defineStore } from 'pinia'

export interface User {
  oname: string
  oval: string
}

const data = [
  {
    label: 'Level one 1',
    children: [
      {
        label: 'Level two 1-1',
        children: [
          {
            label: 'Level three 1-1-1',
          },
        ],
      },
    ],
  },
  {
    label: 'Level one 2',
    children: [
      {
        label: 'Level two 2-1',
        children: [
          {
            label: 'Level three 2-1-1',
          },
        ],
      },
      {
        label: 'Level two 2-2',
        children: [
          {
            label: 'Level three 2-2-1',
          },
        ],
      },
    ],
  },
  {
    label: 'Level one 3',
    children: [
      {
        label: 'Level two 3-1',
        children: [
          {
            label: 'Level three 3-1-1',
          },
        ],
      },
      {
        label: 'Level two 3-2',
        children: [
          {
            label: 'Level three 3-2-1',
          },
        ],
      },
    ],
  },
]

export const usePageStore = defineStore('page', () => {
  const last_fetch = ref('hello world')
  const table_data = ref([
    // {
    //   date: '2016-05-03',
    //   name: 'tata',
    //   address: 'No. 001, Grove St, Los Angeles',
    // },
    // {
    //   date: '2016-05-02',
    //   name: 'tete',
    //   address: 'No. 002, Grove St, Los Angeles',
    // },
    // {
    //   date: '2016-05-04',
    //   name: 'titi',
    //   address: 'No. 003, Grove St, Los Angeles',
    // },
    // {
    //   date: '2016-05-01',
    //   name: 'toto',
    //   address: 'No. 004, Grove St, Los Angeles',
    // },
  ])
  const tree_data = ref(data)
  const wp_nonce = ref('')

  return { wp_nonce, last_fetch, table_data, tree_data }
})
