<template>
  <div>
    <div v-show="out_status">
      <group>
        <cell :title="('姓名')" v-model="item.name"></cell>
        <cell :title="('手机')" v-model="item.phone_number"></cell>
        <cell :title="('邮箱')" v-model="item.email"></cell>
        <cell :title="('身份')"><span v-if="item.sf==1">车主</span><span v-else style="color: red">乘客</span></cell>
        </group>
      <div v-show="cz_status">
        <group>
          <cell :title="('车牌号')" v-model="item.plate_number"></cell>
          <cell :title="('车主状态')"><span v-if="item.status==1">已审核</span><span v-else style="color: red">未审核</span></cell>
          </group>
      </div>
      <!-- <group>
        <cell :title="('修改密码')" link="/account/index" is-link></cell>
      </group> -->
      <group>
        <cell :title="('退出')" @click.native="logout" is-link></cell>
      </group>
    </div>
    <div v-show="status">
        <group>
        <cell :title="('登录')" link="/account/Login"></cell>
        <cell :title="('忘记密码')" link="/account/ForgotPassword"></cell>
        <cell :title="('注册')" link="/account/Regedit"></cell>
        </group>
    </div>

  </div>
</template>

<script>
import { Cell, Group,cookie } from 'vux'

export default {
  components: {
    Group,
    Cell
  },
  data(){
      return {
//                msg: 'hello vue',
          item: '',
          out_status: false,
          status: true,
          cz_status: false,
      }
  },
    mounted(){
    console.log('Component.');
    this.getCookie();
    },
  methods: {
    onClick () {
      console.log('on click')
    },
    fetchData(){
        var resource = this.$resource(APIURL+'users/me', {
//                    include: ''
        });
        resource.get({}).then((response) => {
            console.log(response);
            if(response.data.errcode==0){
                //console.log(response);
                this.item = response.data.data;
                if(this.item.type==1){
                  this.cz_status = true;
                  this.item.sf=1
                }else {
                  this.item.sf=0
                }
                this.out_status = true;
                this.status = false;
            }else {
                console.log('出小差了');
            }
        });

    },
      getCookie(){
          var laravel_token=cookie.get('laravel_token')
          if(laravel_token){
              this.fetchData()
          }else{
              console.log('您还未登录!');
          }
      },
      logout() {
          this.$http.post(APIURL+'users/logout', {
          }).then(response => {
              if (response.data.errcode==0) {
              cookie.remove('laravel_token')
              this.$vux.alert.show({
                  title: '退出成功!',
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
                  title: '退出失败!',
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
