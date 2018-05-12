/* eslint-disable global-require */
const Notes = r => require.ensure([], () => r(require('../components/main')), 'notes-bundle')
const Form  = r => require.ensure([], () => r(require('../components/form')), 'notes-bundle')

const meta = {
  requiresAuth: true,
}

export default [
  {
    name: 'notes.index',
    path: '/notes',
    component: Notes,
    meta,
    children: [
      {
        name: 'notes.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'notes.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
  {
    name: 'catchall',
    path: '*',
    component: Notes,
    meta,
  },
]
    