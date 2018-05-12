/* eslint-disable global-require */
const Dashboard = r => require.ensure([], () => r(require('../components/main')), 'dashboard-bundle')

export default [
  {
    name: 'dashboard.index',
    path: '/',
    component: Dashboard,
    meta: { requiresAuth: true },
  }
]
