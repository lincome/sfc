<template>
  <div>
    <br>
    <group label-width="4em" label-margin-right="2em" label-align="right">
    <!-- <search @on-submit="onSubmit" :auto-fixed="false" v-model="value2" @on-focus="onFocus"></search> -->
    <x-address title="出发地" v-model="city_start" raw-value :list="addressData" value-text-align="left"></x-address>
    <x-address title="目的地" v-model="city_end" raw-value :list="addressData" value-text-align="left"></x-address>
    <datetime title="出发时间" v-model="start_date" format="YYYY-MM-DD HH" value-text-align="left"></datetime>
  </group>
    <div style="padding:15px;">
        <x-button @click.native="showPlugin3" type="primary">搜索</x-button>
    </div>
  </div>
</template>

<script>
import { Search, Divider,Group,Datetime,XAddress,ChinaAddressData,XButton } from 'vux'
export default {
  components: {
    Search,
    Divider,
    Group,
    Datetime,
    XAddress,
    XButton,
  },
  methods: {
    resultClick (item) {
      window.alert('you click the result item: ' + JSON.stringify(item))
    },
    getResult (val) {
      this.results = val ? getResult(this.value) : []
    },
    onSubmit (val) {
      window.alert('on submit' + val)
    },
    onFocus () {
      console.log('on focus')
    },
    showPlugin3 () {
        console.log(111111111111)
        location.href="/?city_start="+this.city_start+"&city_end="+this.city_end+"&start_date="+this.start_date
    },
  },
  data () {
    return {
      addressData: ChinaAddressData,
      results: [],
      start_date: '',
      city_start: [],
      city_end: [],
    }
  }
}
function getResult (val) {
  let rs = []
  for (let i = 0; i < 8; i++) {
    rs.push({
      title: `${val} result: ${i + 1} `,
      other: i
    })
  }
  return rs
}
</script>

<style scoped>
p {
  padding: 10px 15px;
  font-size: 14px;
  color: #888;
}
</style>
