<template>
<div style="height:100%;">
  <loading v-model="isLoading"></loading>
  <view-box ref="viewBox" body-padding-top="46px" body-padding-bottom="55px">
    <x-header slot="header" style="width:100%;position:absolute;left:0;top:0;z-index:100;"

    @on-click-title="scrollTop"
    default-back-link="/">
  </x-header>

    <transition :name="'vux-pop-' + (direction === 'forward' ? 'in' : 'out')">
      <router-view class="router-view"></router-view>
    </transition>

    <tabbar class="vux-demo-tabbar" icon-class="vux-center" slot="bottom">
      <tabbar-item :link="{path:'/'}"  badge="9">
        <span class="demo-icon-22 vux-demo-tabbar-icon-home" slot="icon" style="position:relative;top: -2px;">&#xe637;</span>
        <span slot="label">顺风车</span>
      </tabbar-item>
      <tabbar-item :link="{path:'/czfb'}"  >
        <span class="demo-icon-22" slot="icon">&#xe633;</span>
        <span slot="label">车主发布</span>
      </tabbar-item>
      <tabbar-item :link="{path:'/person'}"  show-dot>
        <span class="demo-icon-22" slot="icon">&#xe630;</span>
        <span slot="label">个人中心</span>
      </tabbar-item>
    </tabbar>
</view-box>
</div>
</template>

<script>
import { ButtonTab, ButtonTabItem, ViewBox, XHeader, Tabbar, TabbarItem, Loading } from 'vux'
import { mapState, mapActions } from 'vuex'

export default {
  name: 'app',
  components: {
    ButtonTab,
    ButtonTabItem,
    ViewBox,
    XHeader,
    Tabbar,
    TabbarItem,
    Loading
  },
  mounted(){
          // console.log(this.$store.state.vux.isLoading );
  },
  methods: {
  scrollTop () {
    this.$refs.viewBox.scrollTo(0)
  },
  },
  computed: {
    ...mapState({
      route: state => state.route,
      path: state => state.route.path,
      demoTop: state => state.vux.demoScrollTop,
      isLoading: state => state.vux.isLoading,
      direction: state => state.vux.direction
    }),
    headerTransition () {
  return this.direction === 'forward' ? 'vux-header-fade-in-right' : 'vux-header-fade-in-left'
},
  }

}
</script>

<style lang="less">
@import '~vux/src/styles/reset.less';
@import '~vux/src/styles/1px.less';
@import '~vux/src/styles/tap.less';

body {
  background-color: #fbf9fe;
}
html, body {
  height: 100%;
  width: 100%;
  overflow-x: hidden;
}

.demo-icon-22 {
  font-family: 'vux-demo';
  font-size: 22px;
  color: #888;
}
.weui-tabbar.vux-demo-tabbar {
  /** backdrop-filter: blur(10px);
  background-color: none;
  background: rgba(247, 247, 250, 0.5);**/
}
.vux-demo-tabbar .weui-bar__item_on .demo-icon-22 {
  color: #F70968;
}
.vux-demo-tabbar .weui-tabbar__item.weui-bar__item_on .weui-tabbar__label {
  color: #35495e;
}
.vux-demo-tabbar .weui-tabbar_item.weui-bar__item_on .vux-demo-tabbar-icon-home {
  color: rgb(53, 73, 94);
}
.demo-icon-22:before {
  content: attr(icon);
}
.vux-demo-tabbar-component {
  background-color: #F70968;
  color: #fff;
  border-radius: 7px;
  padding: 0 4px;
  line-height: 14px;
}
.weui-tabbar__icon + .weui-tabbar__label {
  margin-top: 0!important;
}
.vux-demo-header-box {
  z-index: 100;
  position: absolute;
  width: 100%;
  left: 0;
  top: 0;
}

@font-face {
  font-family: 'vux-demo';  /* project id 70323 */
  src: url('https://at.alicdn.com/t/font_h1fz4ogaj5cm1jor.eot');
  src: url('https://at.alicdn.com/t/font_h1fz4ogaj5cm1jor.eot?#iefix') format('embedded-opentype'),
  url('https://at.alicdn.com/t/font_h1fz4ogaj5cm1jor.woff') format('woff'),
  url('https://at.alicdn.com/t/font_h1fz4ogaj5cm1jor.ttf') format('truetype'),
  url('https://at.alicdn.com/t/font_h1fz4ogaj5cm1jor.svg#iconfont') format('svg');
}

.demo-icon {
  font-family: 'vux-demo';
  font-size:20px;
  color: #04BE02;
}

.demo-icon:before {
  content: attr(icon);
}

/**
* vue-router transition
*/
.router-view {
  width: 100%;
  animation-duration: 0.5s;
  animation-fill-mode: both;
  backface-visibility: hidden;
}
.vux-pop-out-enter-active,
.vux-pop-out-leave-active,
.vux-pop-in-enter-active,
.vux-pop-in-leave-active {
  will-change: transform;
  height: 100%;
  position: absolute;
  left: 0;
}
.vux-pop-out-enter-active {
  animation-name: popInLeft;
}
.vux-pop-out-leave-active {
  animation-name: popOutRight;
}
.vux-pop-in-enter-active {
  perspective: 1000;
  animation-name: popInRight;
}
.vux-pop-in-leave-active {
  animation-name: popOutLeft;
}
@keyframes popInLeft {
  from {
    opacity: 0;
    transform: translate3d(-100%, 0, 0);
  }
  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}
@keyframes popOutLeft {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
    transform: translate3d(-100%, 0, 0);
  }
}
@keyframes popInRight {
  from {
    opacity: 0;
    transform: translate3d(100%, 0, 0);
  }
  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}
@keyframes popOutRight {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
    transform: translate3d(100%, 0, 0);
  }
}
</style>
