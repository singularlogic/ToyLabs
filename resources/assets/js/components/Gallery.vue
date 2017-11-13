<template>
    <div class="ui small images" v-viewer="{ navbar: false, title: false, toolbar: false }">
        <img class="ui rounded image" :src="image" v-for="image in images" style="cursor: pointer;" />
    </div>
 </template>

<script>
import Viewer from 'v-viewer';
Vue.use(Viewer);

export default {
    props: ['images'],
    components: { Viewer },
};
</script>

<style scoped>
.ui.small.image, .ui.small.images .image, .ui.small.images img {
    height: 100px;
}
</style>