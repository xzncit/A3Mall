<template>
    <div>
        <div v-for="(item,index) in rdata" :key="index">
            <top :rdata="item" v-if="item.widget_name=='header'"></top>
            <search :rdata="item" v-if="item.widget_name=='search'"></search>
            <slider :rdata="item" v-if="item.widget_name=='slider'"></slider>
            <notice :rdata="item" v-if="item.widget_name=='notice'"></notice>
            <blank :rdata="item" v-if="item.widget_name=='blank'"></blank>
            <navbar :rdata="item" v-if="item.widget_name=='navbar'"></navbar>
            <images :rdata="item" v-if="item.widget_name=='images'"></images>
            <textbar :rdata="item" v-if="item.widget_name=='textbar'"></textbar>
            <textareas :rdata="item" v-if="item.widget_name=='textarea'"></textareas>
            <image-group :rdata="item" v-if="item.widget_name=='image-group'"></image-group>
            <archives :rdata="item" v-if="item.widget_name=='article'"></archives>
            <archives-category :rdata="item" v-if="item.widget_name=='article-group'"></archives-category>
            <video-list :rdata="item" v-if="item.widget_name=='video'"></video-list>
            <bonus :rdata="item" v-if="item.widget_name=='bonus'"></bonus>
            <goods :rdata="item" v-if="item.widget_name=='goods'"></goods>
            <group :rdata="item" v-if="item.widget_name=='group'"></group>
            <regiment :rdata="item" v-if="item.widget_name=='regiment'"></regiment>
            <second :rdata="item" v-if="item.widget_name=='second'"></second>
        </div>
    </div>
</template>

<script>
    import search from './search';
    import top from './top';
    import slider from "./slider";
    import notice from "./notice";
    import navbar from "./navbar";
    import blank from "./blank";
    import images from "./images";
    import textbar from "./textbar";
    import textareas from "./textareas";
    import imageGroup from "./image-group"
    import ImageGroup from "./image-group";
    import archives from "./archives"
    import archivesCategory from "./archives-category"
    import ArchivesCategory from "./archives-category";
    import video from "./video-list";
    import bonus from "./bonus";
    import goods from "./goods";
    import group from "./group";
    import regiment from "./regiment";
    import second from "./second";
    export default {
        name: "mall",
        components: {
            ArchivesCategory,
            ImageGroup,
            [search.name]: search,
            [top.name]: top,
            [slider.name]: slider,
            [notice.name]: notice,
            [blank.name]: blank,
            [navbar.name]: navbar,
            [images.name]: images,
            [textbar.name]: textbar,
            [textareas.name]: textareas,
            [imageGroup.name]: imageGroup,
            [archives.name]: archives,
            [archivesCategory.name]: archivesCategory,
            [video.name]: video,
            [bonus.name]: bonus,
            [goods.name]: goods,
            [group.name]: group,
            [regiment.name]: regiment,
            [second.name]: second,
        },
        props:{
            rdata: {
                default: function() {
                    return []
                }
            }
        }
    }
</script>