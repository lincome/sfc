<template>
  <div>
    <group label-width="4em" label-margin-right="2em" label-align="right">
      <cell :title="('News')">
        <marquee>
          <marquee-item v-for="i in 5" @click.native="onClick(i)">{{ ('车主必须登录才能发布')}} {{i}}</marquee-item>
        </marquee>
      </cell>

       <x-address title="出发地" v-model="city_start" raw-value :list="addressData" value-text-align="left"></x-address>
       <x-address title="目的地" v-model="city_end" raw-value :list="addressData" value-text-align="left"></x-address>
       <datetime title="出发时间" v-model="start_date" format="YYYY-MM-DD HH:mm" value-text-align="left" required></datetime>
       <x-input title="手机号" v-model="mobile"></x-input>
       <x-input title="价格" v-model="price"></x-input>
       <x-number title="座位" align="left" v-model="seat" button-style="round" :min="1" :max="5"></x-number>
       <x-textarea title="备注" v-model="remark"></x-textarea>
    </group>
      <div style="padding:15px;">
          <x-button @click.native="showPlugin3" type="primary">发布</x-button>
      </div>
  </div>
</template>

<script>
import { Alert,cookie,Group, Cell, XInput, Selector, PopupPicker, Datetime, XNumber, ChinaAddressData, XAddress,XTextarea,Confirm,XButton,Marquee,MarqueeItem } from 'vux'

export default {
  components: {
    Group,
    Cell,
    XInput,
    Selector,
    PopupPicker,
    XAddress,
    Datetime,
    XNumber,
    XTextarea,
    XButton,
    Confirm,
      Marquee,
      MarqueeItem,
      Alert,
  },
  data () {
    return {
      addressData: ChinaAddressData,
      city_start: ['福建省', '福州市', '仓山区'],
      city_end: ['福建省', '三明市', '大田县'],
      remark: '备注：',
      start_date: '',
      seat: 1,
      mobile:'',
      price:'100',
      show: false,
        user:'',
        mobile:'',

    }
  },
    created(){
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    this.start_date = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes();

            // this.getCookie();
            if(cookie.get('phone_number')!='undefined'){
                this.mobile=cookie.get('phone_number')
            }
    },
  methods: {
        onCancel () {
         console.log('on cancel')
       },

           onHide () {
             console.log('on hide')
           },
           onShow () {
             console.log('on show')
           },
      czfb() {
          this.$http.post(APIURL+'order/save', {
              start_date: this.start_date,
              mobile: this.mobile,
              city_start: this.city_start,
              city_end: this.city_end,
              price: this.price,
              seat: this.seat,
              remark: this.remark,
          }).then(response => {
              if (response.data.errcode==0) {
              this.$vux.alert.show({
                  title: '发布成功!',
                  content: '',
                  onShow () {
                  console.log('Plugin: I\'m showing')
              },
              onHide () {
                  console.log('Plugin: I\'m hiding')
                  //跳转
                  location.href="/";
              }
          })

          }else {
              this.$vux.alert.show({
                  title: response.data.data,
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
      showPlugin3 () {
          console.log(cookie.get('laravel_token'))
          if(cookie.get('laravel_token')==undefined){
              this.alertPlugin('您还未登录！')
              return;
          }
          var that=this
          this.$vux.confirm.show({
              title: '确定发布？',
              content: '',
              hideOnBlur: true,
              onCancel () {},
              onConfirm (){
              that.czfb()
            }
          })
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
      getCookie(){
          var laravel_token=cookie.get('laravel_token')
          if(laravel_token){
              this.fetchData()
          }else{
              console.log('您还未登录!');
          }
      },
      fetchData(){
          var resource = this.$resource(APIURL+'users/me', {
  //                    include: ''
          });
          resource.get({}).then((response) => {

              if(response.data.errcode==0){
                  console.log(response.data.data.phone_number);
                  this.mobile=response.data.data.phone_number
              }else {
                  console.log('出小差了');
              }
          });

      },

  }
}
</script>
