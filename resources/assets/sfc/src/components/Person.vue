<template>
  <div>
    <group>
      <cell :title="('我的账号')" link="/account/index" is-link></cell>
    </group>
    <div v-show="type_cz">
      <group>
        <cell :title="('我的发布')" link="/account/Myfb" is-link></cell>
      </group>
    </div>
      <group>
        <cell :title="('我的预定')" link="/account/Myyd" is-link></cell>
      </group>

  </div>
</template>

<script>
import { cookie, Cell, Group } from 'vux'

export default {
  data(){
    return{
      type_cz:false,
      item:'',
    }
  },
  components: {
    Group,
    Cell
  },
  created(){
  this.getCookie();
  },
  methods: {
    onClick_account () {
      console.log('onClick_account')
      location.href='/account/index'
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
                  this.type_cz = true;
                }
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

  }
}
</script>
