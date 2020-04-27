<template>
    <div class="sidebar" :class="{ 'sidebar-mobile': winState === 'mobile'}">
        <nav id="sideheader" class="navbar navbar-expand navbar-dark" style="font-size: large">
            <HeaderTitle/>
            <!--<icon style="float:right"/>-->
            <button class="btn btn-link btn-sm text-white ml-auto"
                style="font-size:x-large"
                @click="closeSideBar">
                <font-awesome-icon icon="times"/>
            </button>
        </nav>
        <div class="overflow-auto">
            <SideBarProfile/>

            <b-list-group flush>
                <SideBarItemLink
                    v-for="routeLink in sideRouteLinks"
                    :to="routeLink.path"
                    :key="'dynamic'+routeLink.path"
                    :title="routeLink.meta.title"
                    :icon="routeLink.meta.icon"
                    :notExact="routeLink.meta.exact === false"
                />
            </b-list-group>
        </div>
    </div>
</template>

<script>
import SideBarProfile from './SideBarProfile.vue'
import SideBarItemLink from './SideBarItemLink.vue'
import HeaderTitle from './HeaderTitle.vue'

import { routeLinks } from '../../routes/filters'

export default {
    name: 'sidebar',
    components: {
        SideBarProfile,
        SideBarItemLink,
        HeaderTitle
    },
    data: function() {
        return {
            sideRouteLinks: []
        }
    },
    watch: {
        currentRole: function() {
            this.getSideRouteLinks();
        }
    },
    computed: {
        winState() {
            return this.$store.getters['window/winState']
        },
        currentRole() {
            return this.$store.getters['auth/currentRole']
        }
    },
    methods: {
        closeSideBar() {
            this.$store.commit("window/updateWinOverlay", {
                winOverlayState: false
            })
        },
        getSideRouteLinks() {
            this.sideRouteLinks = routeLinks(this.currentRole.label)
            /*this.sideRouteLinks.forEach(element => {
                console.log(`element: \n\tpath: ${element.path}\n\ttitle: ${element.meta.title}`)
            });*/

        }
    },
    mounted: function() {
        this.getSideRouteLinks();
    }
}
</script>

<style scoped>
#sideheader {
    height: 3.5rem;
    background-color: #009688;
}
.overflow-auto {
    height: calc(100vh - 3.5rem);
/*    height: calc(100vh - 3.5rem - 4rem);*/
}
.scrolly.foo {
    width: 100%;
    height: calc(100vh - 3.5rem);
}
.scrolly.foo .scrolly-bar:before {
    background: darkgrey;
}

/*#bottom-links {
    height: 4rem !important;
    width: 100%;
}*/


</style>
