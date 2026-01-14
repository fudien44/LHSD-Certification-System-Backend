import type { RouteRecordRaw } from 'vue-router/auto'

const ApplicationRoutes: RouteRecordRaw[] = [
  {
    path: '/application',
    name: 'Application',
    meta: { requiresAuth: true },
    children: [
      // {
      //   name: 'JobPortal',
      //   path: '/application/job-portal',
      //   component: () => import('@/pages/application/jobs.vue'),
      // },
      {
        name: 'For Review',
        path: '/application/for-review',
        component: () => import('@/pages/application/review.vue'),
      },
      {
        name: 'For Schedule',
        path: '/application/for-schedule',
        component: () => import('@/pages/application/schedule.vue'),
      },
      {
        name: 'List of Applications',
        path: '/application',
        component: () => import('@/pages/application/index.vue'),
      },
      
    ],
  },
]

export default ApplicationRoutes
