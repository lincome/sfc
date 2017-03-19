import Vue from 'vue'
import Router from 'vue-router'
import Hello from 'components/Hello'
import Home from 'components/Home'
import Czfb from 'components/Czfb'
import Person from 'components/Person'
import Account from 'components/Account'
import Myfb from 'components/Myfb'
import Myyd from 'components/Myyd'
import Login from 'components/Login'
import Regedit from 'components/Regedit'
import PasswordReset from 'components/PasswordReset'
import ForgotPassword from 'components/ForgotPassword'
import Ccyd from 'components/Ccyd'
import MyPassenger from 'components/MyPassenger'
import SearchStatic from 'components/SearchStatic'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/czfb',
      name: 'Czfb',
      component: Czfb
    },
    {
      path: '/person',
      name: 'Person',
      component: Person
    },
    {
      path: '/account/index',
      name: 'Account',
      component: Account
    },
    {
      path: '/account/Login',
      name: 'Login',
      component: Login
    },
    {
      path: '/account/Regedit',
      name: 'Regedit',
      component: Regedit
    },
    {
      path: '/account/Myfb',
      name: 'Myfb',
      component: Myfb
    },
    {
      path: '/account/Myyd',
      name: 'Myyd',
      component: Myyd
    },
    {
      path: '/account/PasswordReset',
      name: 'PasswordReset',
      component: PasswordReset
    },
    {
      path: '/account/ForgotPassword',
      name: 'ForgotPassword',
      component: ForgotPassword
    },
    {
      path: '/ccyd/:id',
      name: 'Ccyd',
      component: Ccyd
    },
    {
      path: '/MyPassenger/:id',
      name: 'MyPassenger',
      component: MyPassenger
    },
    {
      path: '/home/SearchStatic',
      name: 'SearchStatic',
      component: SearchStatic
    },
  ]
})
