<!--
    Source: https://docs.videojs.com/tutorial-vue.html
-->

<template>
    <div>
        <video ref="videoPlayer" class="video-js">
            <source
                src="//d2zihajmogu5jn.cloudfront.net/bipbop-advanced/bipbop_16x9_variant.m3u8"
                type="application/x-mpegURL"
            >
        </video>
    </div>
</template>


<script>
export default {
    //name: "VideoPlayer",

    props: {
        options: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            player: null
        }
    },
    mounted() {
        this.player = videojs(this.$refs.videoPlayer, this.options, function onPlayerReady() {
            console.log('onPlayerReady', this);
        })
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose()
        }
    }
}
</script>
