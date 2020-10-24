<template>
    <vue-picker
        :selected-index="selectedIndex"
        @select="handleSelect"
        :data="linkageData"
        ref="picker"
        :title="title"
        @change="handleChange"
        :cancelTxt="cancelTxt"
        :confirmTxt="confirmTxt"
    >
    </vue-picker>
</template>

<script>
    import vuePicker from 'vue-bspicker'
    import {province, city, area} from './city-data.js'
    export default{
        name: "vue-city-picker",
        components: {
            vuePicker
        },
        props: {
            title: {
                type: String,
                default: '请选择'
            },
            cancelTxt: {
                type: String,
                default: '取消'
            },
            confirmTxt: {
                type: String,
                default: '确认'
            },
            selectedIndex: {
                type: Array,
                default() {
                    return [0,0,0]
                }
            },
            data: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data(){
            return {
                tempIndex: this.selectedIndex.slice()
            }
        },
        computed: {
            linkageData: function() {
                let data = this.data.length > 0 ? this.data : [province, city, area]
                const provinceList = data[0]
                const cityList = data[1].filter((item) => {
                    return item.parentId === provinceList[this.tempIndex[0]].value
                })

                let areaList = [];

                if(cityList.length > 0){
                    areaList = data[2].filter((item) => {
                        return item.parentId === cityList[this.tempIndex[1]].value
                    })
                }

                return [provinceList, cityList, areaList]
            }
        },
        watch: {
            linkageData: function() {
                this.$refs.picker.refresh()
            }
        },
        methods: {
            show: function() {
                this.$refs.picker.show()
            },
            handleSelect: function() {
                this.$emit('select', ...arguments)
            },
            handleChange: function(i, newIndex){
                if (newIndex !== this.tempIndex[i]) {
                    for (let j = 2; j > i; j--) {
                        this.tempIndex.splice(j, 1, 0)
                        this.$refs.picker.scrollTo(j, 0)
                    }
                    this.tempIndex.splice(i, 1, newIndex)
                }
            }
        }
    }
</script>