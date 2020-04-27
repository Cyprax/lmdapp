<template>
    <div>
        <AppWindowEvent/>

        <Header/>
        <AppOverlayer v-show="winState === 'mobile' && winOverlayState"/>

        <div class="wrapper bg-wrapping" :style="{ 'padding-left': paddingLeft + 'rem' }">

            <SideBar v-show="winSideState"/>

            <div id="content-wrapper">
                <!--<b-breadcrumb :items="breadcrumb"></b-breadcrumb>-->
                <keep-alive>
                    <router-view></router-view>
                </keep-alive>
            </div>

            <Footer/>

        </div>
    </div>
</template>

<script>
import AppWindowEvent from '../components/AppWindowEvent.vue'
import AppOverlayer from '../components/AppOverlayer.vue'
import Header from '../components/Header.vue'
import SideBar from '../components/SideBar.vue'
import Footer from '../components/Footer.vue'

export default {
    name: 'appLayout',
    components: {
        AppWindowEvent,
        AppOverlayer,
        Header,
        SideBar,
        Footer
    },
    computed: {
        winState() {
            return this.$store.getters['window/winState']
        },
        winOverlayState() {
            return this.$store.getters['window/winOverlayState']
        },
        winSideState() {
            return this.$store.getters['window/winSideState']
        },
        paddingLeft() {
            return this.$store.getters['window/winState'] === "mobile" ? 0 : 18.5
        },
        breadcrumb() {
            return this.$route.meta.breadcrumb
        }
    },
    methods: {
    },
    async beforeCreate() {
        await this.$store.dispatch('auth/updateProfileUser')
    }
}
</script>

<style scoped>

#nav-header {
    height:3.5rem;
    background-color: #009688;
    color: white;
    width: 100%;
    z-index: 20;
    position: fixed;
    top: 0;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

#overlayer {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: rgba(0,0,0,.5);
    z-index: 39;
}

#optionbar {
    position: fixed;
    bottom: 0;
    width: 100%;
}


.wrapper {
    width: 100%;
    min-height: 100vh;
    height: 100%;
    padding-left: 0;
}

.sidebar {
    overflow: visible;
    height: 100vh;
    left: 0;
    position: fixed;
    top: 0;
    width: 18.5rem;
    z-index: 1;
    background-color: white;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);
}
.sidebar.sidebar-mobile {
    z-index: 40;
}

#content-wrapper {
    overflow-x: hidden;
    width: 100%;
    min-height: calc(100vh - 3rem);
    margin: 0 auto;
    padding: 5rem 0rem 0rem;
    background: transparent;
}

section.footer {
    bottom: 0;
    height: 3rem;
}

.bg-wrapping {
    background-color: #f3f3f3;
}

</style>
