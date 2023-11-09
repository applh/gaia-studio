import { ref } from 'vue'
import { defineStore } from 'pinia'

export interface User {
  date: string
  name: string
  address: string
}

export const usePageStore = defineStore('page', () => {
  const last_fetch = ref('hello world')
  const table_data = ref([
    {
      date: '2016-05-03',
      name: 'tata',
      address: 'No. 001, Grove St, Los Angeles',
    },
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
  return { last_fetch, table_data }
})
