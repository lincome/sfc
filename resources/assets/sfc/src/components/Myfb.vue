<template>
    <div>我的发布

        <scroller lock-x scrollbar-y use-pullup :pullup-config="pullupConfig2" height="-160" ref="demo3" @on-pullup-loading="load3" v-model="demo3Value">
            <div class="box2">
                <div v-for="vo in item">
                  <group>
                     <cell :link="{path:'/MyPassenger/'+vo.id}" is-link>{{vo.start_date}},{{getName(vo.city_start)}}->{{getName(vo.city_end)}}</cell>
                   </group>
                </div>
            </div>
        </scroller>

        <p @click="demo3Value.pullupStatus='enabled'" v-show="pullupStatus === 1">没有更多了</p>
    </div>
</template>

<script>
    import { ChinaAddressData,Search, Scroller, Divider, Spinner, Group, Cell, Card, Value2nameFilter as value2name } from 'vux'

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
        console.log(APIURL);
        this.fetchData();

        },
        methods: {
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
                var resource = this.$resource(APIURL+'users/czfb_lists', {
                    //                    include: ''
                });
                // get item
                resource.get({
                    page:this.page,
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
                window.alert('you click the result item: ' + JSON.stringify(item1))
            },
            getResult (val) {
                this.results = val ? getResult(this.value) : []
            }
    },
    data () {
        return {
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
            results: [],
            value: 'test'
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
