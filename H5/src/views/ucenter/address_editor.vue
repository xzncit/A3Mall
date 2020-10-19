<template>
    <div class="wrap">
        <nav-bar
                title="编辑地址"
                left-arrow
                :fixed="true"
                :z-index="9999"
                :transparent="true"
                :placeholder="true"
                background-color="#b91922"
                @click-left="prev"
        />

        <div class="theForm clear">
            <div class="fields-box">
                <div>姓名</div>
                <div>
                    <input type="text" name="name" v-model="name" placeholder="收货人姓名" autocomplete="off" />
                </div>
            </div>

            <div class="fields-box">
                <div>电话</div>
                <div>
                    <input type="text" name="tel" v-model="tel" placeholder="收货人手机号" autocomplete="off" />
                </div>
            </div>

            <div class="fields-box">
                <div>地区</div>
                <div>
                    <input  type="text" @click="handleTap" :value="area_name" readonly="true" placeholder="请选择您所在地区" autocomplete="off" />
                </div>
            </div>

            <div class="fields-box">
                <div>地址</div>
                <div>
                    <input type="text" name="addressDetail" v-model="addressDetail" placeholder="请填写您所在地址" autocomplete="off" />
                </div>
            </div>

            <div class="switch-box">
                <div>默认地址</div>
                <div>
                    <van-switch v-model="is_default" />
                </div>
            </div>

            <div class="btn">
                <button @click="onSave">提 交</button>
            </div>
        </div>
        <van-popup v-model="show" closeable position="bottom" :style="{ height: '400px' }">
            <div class="area-distpicker-wrap">
                <v-distpicker type="mobile" @selected="onSelected"></v-distpicker>
            </div>
        </van-popup>
    </div>
</template>

<script>
    import NavBar from '../../components/nav-bar/nav-bar';
    import { Toast } from 'vant';
    import VDistpicker from '../../components/v-distpicker/Distpicker'
    import AreaData from "../../utils/AreaData";
    import { Switch,Popup } from 'vant';
    export default {
        name: 'AddressEditor',
        components: {
            [NavBar.name]: NavBar,
            [Switch.name]: Switch,
            [Popup.name]: Popup,
            VDistpicker
        },
        data() {
            return {
                show:false,
                districts: AreaData,
                id: 0,
                name: "",
                tel: "",
                province: "",
                county: "",
                city: "",
                areaCode: [],
                addressDetail: "",
                is_default: false,
                area_name: '',
                area: []
            };
        },
        created() {
            if(this.$router.path != '/ucenter/address/add' && this.$route.params.id != undefined){
                this.$http.getAddressData({
                    id: this.$route.params.id,
                    client_type: "2"
                }).then(res=>{
                    this.id = this.$route.params.id;
                    let arr = res.data.area_name.split(",");
                    this.name = res.data.name;
                    this.tel = res.data.tel;
                    this.province = arr[0];
                    this.county = arr[2];
                    this.city = arr[1];
                    this.addressDetail = res.data.addressDetail;
                    this.is_default = res.data.isDefault ? true : false;
                    this.area_name = res.data.area_name;
                });
            }

        },
        methods: {
            prev() {
                this.$tools.prev();
            },
            onSelected(data) {
                this.province = data.province.value;
                this.county = data.area.value;
                this.city = data.city.value;
                this.area_name = data.province.value + ',' + data.city.value + ',' + data.area.value;
                this.show = false;
            },
            handleTap(){
                this.show = !this.show;
            },
            onSave() {
                this.$http.editorAddress({
                    id: this.id,
                    client_type: "2",
                    name: this.name,
                    tel: this.tel,
                    province: this.province,
                    county: this.county,
                    city: this.city,
                    areaCode: this.areaCode,
                    addressDetail: this.addressDetail,
                    is_default: this.is_default ? 1 : 0,
                }).then((res)=>{
                    if(res.status){
                        Toast(res.info);
                        this.$storage.set("ORDER_ADDRESS_ID",res.data);
                        this.$tools.prev();
                    }else{
                        Toast(res.info);
                    }
                });
            },
        },
    }
</script>

<style lang="scss" scoped>
    .wrap{
        width: 100%;
        background-color: #fff;
    }

    .theForm{
        width: 325px;
        padding-top: 25px;
        margin: 0 auto;
        .fields-box{
            width: 100%;
            float: left;
            font-size: 15px;
            height: 45px;
            line-height: 45px;
            border: 1px solid #e0e0e0;
            border-left: 0;
            border-right: 0;
            position: relative;
            &:nth-child(1){
                border-top: 0;
                border-bottom: 0;
            }
            &:nth-child(2){
                border-bottom: 0;
            }
            &:nth-child(3){
                border-bottom: 0;
            }
            &:nth-child(4){
                border-bottom: 1px solid #e0e0e0;
            }
            div:first-child {
                position: absolute;
                top: 5px;
                width: 80px;
                height: 40px;
                line-height: 40px;
                color: #999;
                background-color: #fff;
            }
            div:last-child {
                float: right;
                width: 100%;
                color: #333;
                input {
                    text-indent: 80px;
                    width: 100%;
                    height: 45px;
                    line-height: 45px;
                    border: 0;
                }
            }
        }
        .switch-box{
            background-color: #fff;
            width: 100%;
            float: left;
            font-size: 15px;
            height: 45px;
            line-height: 45px;
            div:first-child {
                float: left;
                color: #999;
            }
            div:last-child {
                float: right;
                position: relative;
                top: 3px;
            }
        }
        .btn{
            float: left;
            margin: 12px 0;
            button {
                width: 325px;
                height: 50px;
                line-height: 50px;
                text-align: center;
                background-color: #b91922;
                border: 1px solid #b91922;
                color: #fff;
                font-size: 15px;
            }
        }
    }
    .area-distpicker-wrap{
        height: 400px;
        overflow-y: auto;
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
    }
    .area-distpicker-wrap>>>.distpicker-address-wrapper{
        color: #999;
    }
    .area-distpicker-wrap>>>.address-header{
        position: fixed;
        bottom: 400px;
        width: 100%;
        background: #000;
        color:#fff;
    }
    .area-distpicker-wrap>>>.address-header ul li{
        flex-grow: 1;
        text-align: center;
    }
    .area-distpicker-wrap>>>.address-header .active{
        color: #fff;
        border-bottom:#666 solid 8px
    }
    .area-distpicker-wrap>>>.address-container .active{
        color: #000;
    }
</style>
