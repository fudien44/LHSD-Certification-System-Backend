import type { RouteRecordRaw } from 'vue-router/auto'

const LndRoutes: RouteRecordRaw[] = [
  {
    path: '/manage',
    name: 'Manage',
    meta: { requiresAuth: true },
    children: [
      {
        name: 'Users',
        path: 'Users',
        component: () => import('@/pages/manage/users.vue'),
      },
      {
        name: 'Programs',
        path: 'Programs',
        component: () => import('@/pages/manage/programs.vue'),
      },
      
    ],
  },
]

export default LndRoutes
