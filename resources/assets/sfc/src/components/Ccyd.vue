<template>
  <div>
    <group label-width="4em" label-margin-right="2em" label-align="right">
      <cell :title="('News')">
        <marquee>
          <marquee-item v-for="i in 5" @click.native="onClick(i)">{{ ('乘客必须注册登录才能预定')}} {{i}}</marquee-item>
        </marquee>
      </cell>
      <cell :title="('出发地')">
        <div slot="value">
          <span style="color: green">{{getName(item.city_start)}}</span>
        </div>
      </cell>
      <cell :title="('目的地')">
        <div slot="value">
          <span style="color: green">{{getName(item.city_end)}}</span>
        </div>
      </cell>
      <cell :title="('出发时间')">
        <div slot="value">
          <span style="color: green">{{item.start_date}}</span>
        </div>
      </cell>
      <cell :title="('价格')">
        <div slot="value">
          <span style="color: green">{{item.price}}</span>
        </div>
      </cell>
      <cell :title="('车主备注')">
        <div slot="value">
          <span style="color: green">{{item.remark}}</span>
        </div>
      </cell>
      <x-number title="座位数" align="left" v-model="seat_num" button-style="round" :min="1" :max=item.seat></x-number>

       <x-textarea title="给车主留言" v-model="remark"></x-textarea>
    </group>
      <div style="padding:15px;">
          <x-button @click.native="showPlugin3" type="primary">立即预定</x-button>
      </div>
  </div>
</template>

<script>
import { Alert,cookie,Group, Cell, XInput, Selector, PopupPicker, Datetime, XNumber, ChinaAddressData, XAddress,XTextarea,Confirm,XButton,Marquee,MarqueeItem,Value2nameFilter as value2name } from 'vux'

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
      city_start: '',
      city_end: '',
      remark: '给车主留言',
      start_date: '',
      seat_num: 1,
      mobile:'',
      price:'',
      show: false,
        user:'',
        mobile:'',
        item:'',

    }
  },
    created(){
    this.fetchData()
    },
  methods: {
    getName (value) {
return value2name(value, ChinaAddressData)
},
        onCancel () {
         console.log('on cancel')
       },

           onHide () {
             console.log('on hide')
           },
           onShow () {
             console.log('on show')
           },
          fetchData(){

              var resource = this.$resource(APIURL+'order/getInfoByid', {
    //                    include: ''
              });
              resource.get({
                id: this.$route.params.id,
              }).then((response) => {
                  if(response.data.errcode==0){
                      console.log(response);
                      this.item = response.data.data;
                  }else {
              console.log(response);
                  }
              })
        },
      showPlugin3 () {
          console.log(cookie.get('laravel_token'))
          if(cookie.get('laravel_token')==undefined){
              this.alertPlugin('您还未登录！')
              return;
          }
          var that=this
          this.$vux.confirm.show({
              title: '确定预定?',
              content: '',
              hideOnBlur: true,
              onCancel () {},
              onConfirm (){
              that.ccyd()
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
      ccyd() {
          this.$http.post(APIURL+'orderPassanger/reserve', {
              order_id: this.$route.params.id,
              seat_num: this.seat_num,
              remark: this.remark,
          }).then(response => {
              if (response.data.errcode==0) {
              this.$vux.alert.show({
                  title: '预定成功!',
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

  }
}
</script>
