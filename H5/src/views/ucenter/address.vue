<template>
    <div>
        <van-nav-bar
                title="我的地址"
                left-arrow
                :fixed="true"
                :placeholder="true"
                @click-left="prev"
        />

        <van-address-list
            v-model="chosenAddressId"
            :list="list"
            default-tag-text="默认"
            @add="onAdd"
            @edit="onEdit"
        />
    </div>
</template>

<script>
import { NavBar } from 'vant';
import { AddressList } from 'vant';
export default {
    name: 'Address',
    components: {
        [NavBar.name]: NavBar,
        [AddressList.name]: AddressList,
    },
    data() {
        return {
            chosenAddressId: '1',
            list: []
        };
    },
    created() {
        this.$http.getAddress().then(res=>{
            if(res.status){
                this.list = res.data.list;
                this.chosenAddressId = res.data.chosenAddressId;
            }
        });
    },
    methods: {
        prev() {
            this.$tools.prev();
        },
        onAdd() {
            this.$router.push("/ucenter/address/add");
        },
        onEdit(item, index) {
            this.$router.push({ name: "AddressEditor", params: { id: item.id } });
        },
    },
}
</script>

<style lang="scss" scoped>

</style>
