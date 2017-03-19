// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import FastClick from 'fastclick'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import App from './App'
import { sync } from 'vuex-router-sync'
import VueResource from 'vue-resource'
Vue.use(VueResource)

import Vuex from 'vuex'
Vue.use(Vuex)

Vue.http.options.emulateJSON = true
import { cookie } from 'vux'
var laravel_token=cookie.get('laravel_token')
// console.log(laravel_token);
Vue.http.interceptors.push((request, next) => {
  request.headers.set('Authorization', 'Bearer {'+laravel_token+'}');
  next();
});

global.APIURL ='http://sfc.xingxingdd.cn/api/'

//全局函数
Vue.prototype.getMeInfo = function () {
  var resource = this.$resource(APIURL+'users/me', {
//                    include: ''
  });
  resource.get({}).then((response) => {
      // console.log(response);
      if(response.data.errcode==0){
          console.log(response.data.data);
          return  response.data.data;
      }else {
          return false
      }
  });
}

FastClick.attach(document.body)

let store = new Vuex.Store({}) // 这里你可能已经有其他 module

store.registerModule('vux', { // 名字自己定义
  state: {
    isLoading: false
  },
  mutations: {
    updateLoadingStatus (state, payload) {
      state.isLoading = payload.isLoading
    },
  }
})
Vue.use(store)
import router from './router' // vue-router instance
sync(store, router)

// plugins
import { LocalePlugin, DevicePlugin, ToastPlugin, AlertPlugin, ConfirmPlugin, LoadingPlugin, WechatPlugin, AjaxPlugin } from 'vux'
Vue.use(DevicePlugin)
Vue.use(ToastPlugin)
Vue.use(AlertPlugin)
Vue.use(ConfirmPlugin)
Vue.use(LoadingPlugin)
Vue.use(WechatPlugin)
//Vue.use(AjaxPlugin)
Vue.use(LocalePlugin)

//然后使用vue-router的beforeEach和afterEach来更改loading状态

router.beforeEach(function (to, from, next) {
  // console.log(store.state.vux.isLoading );
  store.commit('updateLoadingStatus', {isLoading: true})
  // console.log(store.state.vux.isLoading );
  next()
})

router.afterEach(function (to) {
  store.commit('updateLoadingStatus', {isLoading: false})
})

/* eslint-disable no-new */
new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app-box')
