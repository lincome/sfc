<template>
    <div>
      <group>
<cell title="搜索" is-link link="/home/SearchStatic"></cell>
</group>
        <scroller lock-x scrollbar-y use-pullup :pullup-config="pullupConfig2" height="-190" ref="demo3" @on-pullup-loading="load3" v-model="demo3Value">
            <div class="box2">
                <div v-for="vo in item">
                    <card  :footer="{title:'立即预定',link:'/ccyd/'+vo.id}">
                        <p slot="content" class="card-padding">

                            <span style="font-size: small">{{vo.start_date}},{{getName(vo.city_start)}}->{{getName(vo.city_end)}},座位：{{vo.seat}}</span></p>
                    </card>
                </div>
            </div>
        </scroller>

        <p @click="demo3Value.pullupStatus='enabled'" v-show="pullupStatus === 1">没有更多了</p>
    </div>
</template>

<script>
    import { querystring,ChinaAddressData, Search, Scroller, Divider, Spinner, Group, Cell, Card, Value2nameFilter as value2name } from 'vux'

    export default {
        components: {
            Search,
            Scroller,
            Divider,
            Spinner,
            Group,
            Cell,
            Card,

        },
        mounted(){
        var url_parameter=(querystring.parse(location.search));
        this.city_start=url_parameter.city_start
        this.city_end=url_parameter.city_end
        this.start_date=url_parameter.start_date
        // console.log(this.city_start);
        this.fetchData();

        },
        methods: {
          onSubmit (val) {
  // window.alert('on submit' + val)
  this.city_start=val
  this.fetchData()
},
onFocus () {
      console.log('on focus')
    },
          getName (value) {
    return value2name(value, ChinaAddressData)
  },
            load3 () {
                setTimeout(() => {
                    setTimeout(() => {
                        this.demo3Value.pullupStatus = 'default'
                    }, 100)
                    this.page++
                    this.fetchData();
                    console.log(this.page)
                    if (this.pullupStatus==1) { // unload plugin
                        setTimeout(() => {
                            this.demo3Value.pullupStatus = 'disabled'
                        }, 100)
                    }
                }, 100)
            },
            fetchData(){
                var resource = this.$resource(APIURL+'order/index', {
                    //                    include: ''
                });
                // get item
                resource.get({
                    page:this.page,
                    city_start:this.city_start,
                    city_end:this.city_end,
                    start_date:this.start_date,
                }).then((response) => {
                    if(response.data.errcode==0){
                        this.item = response.data.data;
                        console.log(this.page*this.pageSize);
                        console.log(this.item.length);
                        if(this.page*this.pageSize>this.item.length){
                            this.pullupStatus=1
                        }
                        console.log(this.item);
                    }else {
                        console.log('出小差了');
                    }

                });
            },
            resultClick (item1) {
                this.city_start=this.search_value
            },
            getResult (val) {
                this.results = val ? getResult(this.value) : []
            }
    },
    data () {
        return {
          addressData: ChinaAddressData,
            n3: 10,
            demo3Value: {
                pullupStatus: ''
            },
            pullupConfig2: {
                content: '上拉加载更多',
                downContent: '松开进行加载',
                upContent: '上拉加载更多',
                loadingContent: '加载中...'
            },
            item: '',
            page: 1,
            pageSize:10,
            pullupStatus:0,
            city_start:'',
            results: [],
            search_value: '福州市'
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

<style lang="less" scoped>
    @import '~vux/src/styles/1px.less';
    .box1 {
        height: 100px;
        position: relative;
        width: 1490px;
    }
    .box1-item {
        width: 200px;
        height: 100px;
        background-color: #ccc;
        display:inline-block;
        margin-left: 15px;
        float: left;
        text-align: center;
        line-height: 100px;
    }
    .box1-item:first-child {
        margin-left: 0;
    }
    .box2-wrap {
        height: 300px;
        overflow: hidden;
    }
    .rotate {
        transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
    }
    .pullup-arrow {
        display: block;
        transition: all linear 0.2s;
        -webkit-transition: all linear 0.2s;
        color: #666;
        font-size: 25px;
    }


    .card-demo-flex {
        display: flex;
    }
    .card-demo-content01 {
        padding: 10px 0;
    }
    .card-padding {
        padding: 15px;
    }
    .card-demo-flex > div {
        flex: 1;
        text-align: center;
        font-size: 12px;
    }
    .card-demo-flex span {
        color: #f74c31;
    }
</style>
