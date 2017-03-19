<template>
  <div style="width: 95%;margin: 0 auto;">
    <br>
    重置密码
    <div>
        <group label-width="4em" label-margin-right="2em" label-align="left">
          <x-input title="密码（6位以上）" type="password" placeholder=""  v-model="password" :min="6" @on-change="change" ref="input04" required></x-input>
          <x-input title="确认密码" v-model="password2" type="password" placeholder="" :equal-with="password" ref="input05" required></x-input>
       </group>

      <div class="btn_wrap">
        <x-button type="primary" @click.native="nextStep">{{ ('确认') }}</x-button>
      </div>
    </div>
  </div>
</template>

<script>
import { querystring, cookie, Step, StepItem, XButton, XHr,Group, Cell, XInput, Selector, PopupPicker, Datetime, XNumber, ChinaAddressData, XAddress } from 'vux'

export default {
  components: {
    Step,
    StepItem,
    XButton,
    XHr,
    Group,
    Cell,
    XInput,
    Selector,
    PopupPicker,
    XAddress,
    Datetime,
    XNumber
  },
  data () {
    return {
      step1: 1,
      step2: 0,
      username: '',
      phone_number: '',
      password: '',
      password2: '',
      plate_number: '',
      email: '',
      one:true,
      two:false,
      two_cz:false,
      three:false,
      type: ['乘客'],
      list: [['乘客', '车主']],
       valid1: false,
       valid2: false,
       valid3: false,
       valid4: false,
       valid5: false,
    }
  },
  created(){
    console.log(this.$route.query.token)
    cookie.set('laravel_token', this.$route.query.token)
  },
  methods: {
    change (val) {
  console.log(val)
},
getValid1 () {
  this.valid4 = this.$refs.input04.valid
  if(this.valid4===false){
    alert('密码格式不对')
    return false
  }
  this.valid5 = this.$refs.input05.valid
  if(this.valid5===false){
    alert('密码格式不对')
    return false
  }
  return true
},
    nextStep () {
      var validate=this.getValid1()
      if(validate===true){
        this.password_reset()
      }
    },
    password_reset() {
        this.$http.post(APIURL+'users/password/reset', {
          password: this.password,
        }).then(response => {
            if (response.data.errcode==0) {
              this.$vux.alert.show({
              title: '修改成功!',
              content: '',
              onShow () {
                console.log('Plugin: I\'m showing')
              },
              onHide () {
                console.log('Plugin: I\'m hiding')
                  //跳转
                  location.href="/#/account/Login";
              }
            })

            }else {
              this.$vux.alert.show({
              title: '修改失败!',
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

<style lang="less">
.btn_wrap {
  padding: 0 1rem;
  margin-top: 2rem;
}
</style>
