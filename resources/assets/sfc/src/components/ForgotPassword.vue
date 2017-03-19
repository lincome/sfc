<template>
  <div style="width: 95%;margin: 0 auto;">
    <br>
    忘记密码
    <div>
          <group label-width="4em" label-margin-right="2em" label-align="left">
            <x-input title="邮箱" name="email" v-model="email" placeholder="请输入邮箱地址" is-type="email" ref="input03" required></x-input>
          </group>
      <div class="btn_wrap">
        <x-button type="primary" @click.native="nextStep">{{ ('发送邮件') }}</x-button>
      </div>
    </div>
  </div>
</template>

<script>
import { Step, StepItem, XButton, XHr,Group, Cell, XInput, Selector, PopupPicker, Datetime, XNumber, ChinaAddressData, XAddress } from 'vux'

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
  methods: {
    change (val) {
      console.log(val)
      },
      getValid1 () {
        this.valid3 = this.$refs.input03.valid
        if(this.valid3===false){
          alert('邮箱格式不对')
          return false
        }
        return true
      },
    nextStep () {
    var validate=this.getValid1()
    if(validate===true){
      this.password_email()
    }

    },
    password_email() {
        this.$http.post(APIURL+'users/password/email', {
          email: this.email,
        }).then(response => {
          console.log(response);
          if (response.data.errcode==0) {
            this.$vux.alert.show({
            title: '发送成功!',
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
            title: '发送失败!',
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
