<template>
    <div>
        <van-nav-bar
            title="编辑地址"
            left-arrow
            :fixed="true"
            :placeholder="true"
            @click-left="prev"
        />

        <van-address-edit
            :area-list="areaList"
            :address-info="addressInfo"
            show-delete
            show-set-default
            :area-columns-placeholder="['请选择', '请选择', '请选择']"
            @save="onSave"
            @delete="onDelete"
        />
    </div>
</template>

<script>
import { NavBar } from 'vant';
import { AddressEdit } from 'vant';
import AreaList from '../../utils/Area';
import { Toast } from 'vant';
export default {
    name: 'AddressEditor',
    components: {
        [NavBar.name]: NavBar,
        [AddressEdit.name]: AddressEdit,
    },
    data() {
        return {
            areaList:AreaList,
            addressInfo: {
                addressDetail: "",
                areaCode: "",
                isDefault: true,
                name: "",
                tel: "",
            },
            addressId: 0,
        };
    },
    created() {
        if(this.$router.path != '/ucenter/address/add' && this.$route.params.id != undefined){
            this.$request.get("/ucenter/address",{
                id: this.$route.params.id,
            }).then(res=>{
                this.addressId = this.$route.params.id;
                this.addressInfo = res.data;
            });
        }

    },
    methods: {
        prev() {
            this.$tools.prev();
        },
        onSave(content) {
            Toast('save');
            this.$http.editorAddress({
                id: this.addressId,
                name: content.name,
                tel: content.tel,
                province: content.province,
                county: content.county,
                city: content.city,
                areaCode: content.areaCode,
                addressDetail: content.addressDetail,
                is_default: content.isDefault ? 1 : 0,
            }).then((res)=>{
                if(res.status){
                    this.addressId = res.data;
                    Toast(res.info);
                    this.$storage.set("ORDER_ADDRESS_ID",res.data);
                    this.$tools.prev();
                }else{
                    Toast(res.info);
                }
            });
        },
        onDelete() {
            if(this.addressId){
                this.$http.editorAddressDelete({
                    id: this.addressId
                });
            }

            Toast('删除成功');
            setTimeout(()=>{
                this.$router.push({ path: "/ucenter/address" });
            },1000);
        },
    },
}
</script>

<style lang="scss" scoped>

</style>
