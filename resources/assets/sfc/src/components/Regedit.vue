<template>
  <div style="width: 95%;margin: 0 auto;">
    <br>

    <x-hr></x-hr>
    <div>
      <step v-model="step2" background-color='#fbf9fe' gutter="20px">
        <step-item :title="('第一步')"></step-item>
        <step-item :title="('第二步')"></step-item>
        <step-item :title="('第三步')"></step-item>
      </step>
      <div v-show="one">
         <group label-width="4em" label-margin-right="2em" label-align="left">
           <popup-picker title="选择身份" placeholder="Required" :data="list" v-model="type" value-text-align="left"></popup-picker>
        </group>
      </div>
      <div v-show="two">
        <group label-width="4em" label-margin-right="2em" label-align="left">
          <x-input title="姓名" v-model="name" placeholder="请输入姓名" ref="input01" required></x-input>
          <x-input title="手机号码" placeholder="请输入手机号码" v-model="phone_number" ref="input02" keyboard="number" is-type="china-mobile" required></x-input>
           <x-input title="邮箱" name="email" v-model="email" placeholder="请输入邮箱地址" is-type="email" ref="input03" required></x-input>
           <x-input title="密码（6位以上）" type="password" placeholder=""  v-model="password" :min="6" @on-change="change" ref="input04" required></x-input>
          <x-input title="确认密码" v-model="password2" type="password" placeholder="" :equal-with="password" ref="input05" required></x-input>
       </group>
     </div>
     <div v-show="two_cz">
        <group label-width="4em" label-margin-right="2em" label-align="left">
         <x-input title="车牌号" name="plate_number" placeholder="请输入车牌号" v-model="plate_number" type="text" ></x-input>
        </group>
    </div>
      <div v-show="three">
        恭喜你注册完成
      </div>
      <div v-show="four">
        注册失败
      </div>
      <div class="btn_wrap">
        <x-button type="primary" @click.native="nextStep">{{ ('下一步') }}</x-button>
      </div>
    </div>
  </div>
</template>

<script>
import { cookie, Step, StepItem, XButton, XHr,Group, Cell, XInput, Selector, PopupPicker, Datetime, XNumber, ChinaAddressData, XAddress } from 'vux'

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
      step2: 0,
      name: '',
      phone_number: '',
      password: '',
      password2: '',
      plate_number: '',
      email: '',
      one:true,
      two:false,
      two_cz:false,
      three:false,
      four:false,
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
  this.valid1 = this.$refs.input01.valid
  if(this.valid1===false){
    this.alertPlugin('姓名不能为空')
    return false
  }
  this.valid2 = this.$refs.input02.valid
  if(this.valid2===false){
    this.alertPlugin('手机号格式不对')
    return false
  }
  this.valid3 = this.$refs.input03.valid
  if(this.valid3===false){
    this.alertPlugin('邮箱格式不对')
    return false
  }
  this.valid4 = this.$refs.input04.valid
  if(this.valid4===false){
    this.alertPlugin('密码格式不对')
    return false
  }
  this.valid5 = this.$refs.input05.valid
  if(this.valid5===false){
    this.alertPlugin('密码格式不对')
    return false
  }
},
    nextStep () {
      this.step2 ++,
      console.log(this.step2)
      if(this.step2==1){
          //第2步
        if(this.type=='乘客'){
          this.one=false
          this.two=true
        }else if (this.type=='车主') {
          this.one=false
          this.two=true
          this.two_cz=true
        }
      }else if (this.step2==2) {
        //第3步
        var getValid1=this.getValid1()
        if(getValid1==false) {
          this.step2 --;
          return
        }else {
          this.one=false
          this.two=false
          this.two_cz=false

          this.$http.post(APIURL+'users/regedit', {
            type: this.type,
            name: this.name,
            phone_number: this.phone_number,
            password: this.password,
            email: this.email,
            plate_number: this.plate_number,
          }).then(response => {
              if (response.data.errcode==0) {
                  this.three=true
                  cookie.set('laravel_token', response.data.data.token)
                  this.$vux.alert.show({
                  title: '注册成功!',
                  content: '',
                  onShow () {
                    console.log('Plugin: I\'m showing')
                  },
                  onHide () {
                    console.log('Plugin: I\'m hiding')
                      location.href="/";

                  }
                })
              }else {
                this.$vux.alert.show({
                title: '注册失败!',
                content: response.data.data,
                onShow () {
                  console.log('Plugin: I\'m showing')
                },
                onHide () {
                  console.log('Plugin: I\'m hiding')
                }
              })
              this.four=true
              }
        });
        }

      }
    },
    alertPlugin (title) {
        this.$vux.alert.show({
            title: title,
            content: '',
            onShow () {
            console.log('Plugin: I\'m showing')
        },
        onHide () {
            console.log('Plugin: I\'m hiding now')
        }
    })
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
