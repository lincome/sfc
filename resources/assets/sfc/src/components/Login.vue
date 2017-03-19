<template>
  <div>
    <group>
      <x-input title="手机号码" name="phone_number" placeholder="请输入手机号码" v-model="phone_number" keyboard="number" is-type="china-mobile" @on-change="change"></x-input>
    </group>

    <group>
      <x-input title="密码" type="password" name="password" placeholder="请输入密码" v-model="password" :min="6" :max="12" @on-change="change"></x-input>
    </group>
    <div style="padding:15px;">
      <x-button @click.native="login"  type="primary">登录</x-button>
    </div>
  </div>
</template>

<script>
import { XInput, Group, XButton, Cell,cookie,AlertPlugin } from 'vux'

export default {
  components: {
    XInput,
    XButton,
    Group,
    Cell,
  },
  data () {
    return {
      password: '',
      phone_number: '',
      disabledValue: 'hello',
      debounceValue: '',
      maxValue: ''
    }
  },
  created(){
    if(cookie.get('phone_number')!='undefined'){
        this.phone_number=cookie.get('phone_number')
    }
  },
  methods: {
    change (val) {
      console.log(val)
    },
    onBlur (val) {
      console.log('on blur', val)
    },
    onFocus (val) {
      console.log('on focus', val)
    },
    login() {
        this.$http.post(APIURL+'users/login', {
          phone_number: this.phone_number,
          password: this.password,
        }).then(response => {
            if (response.data.errcode==0) {
              cookie.set('laravel_token', response.data.data.token);
              cookie.set('phone_number', this.phone_number);
              this.$vux.alert.show({
              title: '登录成功!',
              content: '',
              onShow () {
                console.log('Plugin: I\'m showing')

              },
              onHide () {
                console.log('Plugin: I\'m hiding')
                  //跳转
                  location.href="/"

              }
            })

            }else {
              this.$vux.alert.show({
              title: '账号密码有误!',
              content: '',
              onShow () {
                console.log('Plugin: I\'m showing')
              },
              onHide () {
                console.log('Plugin: I\'m hiding')
              }
            })
            }
      });
    },
  }
}
</script>
<style scoped>
.red {
  color: red;
}
.green {
  color: green;
}
</style>
