/* eslint-disable global-require */
export const Main   = r => require.ensure([], () => r(require('../components/main')), 'auth-bundle')
export const Signin = r => require.ensure([], () => r(require('../components/forms/signin')), 'auth-bundle')
export const Signup = r => require.ensure([], () => r(require('../components/forms/signup')), 'auth-bundle')
