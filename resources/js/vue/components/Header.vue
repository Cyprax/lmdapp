<template>
    <nav id="nav-header" class="navbar navbar-expand navbar-dark static-top" style="font-size: large">
        <div class="nav-left" v-show="winState !== 'mobile'">
            <HeaderTitle/>
            <!--<icon style="float:right"/>-->
        </div>

        <button class="btn btn-link btn-sm text-white"
            v-show="winState === 'mobile'"
            style="font-size:x-large"
            @click="openSideBar">
            <font-awesome-icon icon="bars"/>
        </button>

        <div class="d-none d-md-inline-block ml-auto mr-0 mr-md-3 my-2 my-md-0"></div>

        <ul class="navbar-nav ml-auto ml-md-0">
            <li id="dropheader"
                class="nav-item dropdown dropheader"
                style="font-size: large">

                <a id="head-profiler" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <font-awesome-icon icon="user-circle"/>
                    {{ userMatricule }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <template v-for="routerLink in headerRouteLinks">
                        <HeaderDropdownLink
                            :name="routerLink.name"
                            :key="'static'+routerLink.name"
                            :title="routerLink.meta.title"
                            :icon="routerLink.meta.icon"/>
                        <div class="dropdown-divider" :key="'divider'+routerLink.name"></div>
                    </template>
                    <!-- :key="'static'+headerLink.name"<HeaderDropdownLink to="/app/profile" icon="id-card" label="Profile"/>
                    <div class="dropdown-divider"></div>
                    <HeaderDropdownLink to="/app/notifs" icon="bell" label="Notifications"/>
                    <div class="dropdown-divider"></div>
                    <HeaderDropdownLink to="/app/about" icon="info-circle" label="A propos"/>
                    <div class="dropdown-divider"></div>-->
                    <a href="#!" class="dropdown-item" @click.prevent="logout">
                        <font-awesome-icon icon="power-off"/>
                        Déconnexion
                    </a>
                </div>
            </li>
        </ul>


        <!--<div class="nav-right">
        </div>-->
    </nav>
</template>

<script>
    import HeaderDropdownLink from "./HeaderDropdownLink.vue"
    import HeaderTitle from './HeaderTitle.vue'

    import { routeLinks, staticRouteLinks } from '../../routes/filters'

    export default {
        name: 'app-header',
        components: {
            HeaderDropdownLink,
            HeaderTitle
        },
        data: function() {
            return {
                headerRouteLinks: []
            }
        },
        computed: {
            winState() {
                return this.$store.getters['window/winState']
            },
            userMatricule() {
                return this.$store.getters['auth/profileUser'].matricule
            }
        },
        methods: {
            openSideBar() {
                this.$store.commit("window/updateWinOverlay", {
                    winOverlayState: true
                })
            },
            logout() {
                this.$store.dispatch('auth/logout', {
                    router: this.$router,
                    method: this.makeToast,
                    that: this
                });

                /*axios.post('api/auth/logout').then((rep) => {
                    context.commit('auth/logout');
                    this.$router.push('/login');
                })*/

                //this.$store.dispatch('auth/logout');
                //this.$store.commit('auth/logout');
                //this.$router.push('/login');
            },

            getHeaderRouteLinks() {
                this.headerRouteLinks = staticRouteLinks().filter( function(route) {
                    return route.name !== 'Dashboard'
                })
            },
            makeToast(title, bodyMessage, variant = 'danger', solid = true, toaster = 'b-toaster-top-right') {
                this.$bvToast.toast(bodyMessage, {
                    title: title,
                    variant: variant,
                    solid: solid,
                    toaster: toaster
                })
            }
        },
        mounted: function() {
            this.getHeaderRouteLinks();
        }
    }
</script>

<style scoped>
    #nav-header {
        position: fixed;
        top: 0;
        left: 0;
        height:3.5rem;
        width: 100%;
        background-color: #009688;
        color: #fff;
        z-index: 20;
    }
    .nav-left {
        /*padding: 1rem;*/
        width: 18.5rem !important;
    }
    .nav-right {
        margin: 0 auto;
        width: 100%;
        padding-left: 2rem;
        padding-right: 2rem;
    }
    .btn-closer {
        margin-right: 1rem;
        height: 2.25rem;
        width: 2.25rem;
    }

    .font-large {
        font-size: large
    }
    .font-x-large {
        font-weight: bold;
        font-size: x-large
    }
    .color-soft {
        color: rgb(255, 200, 0, 1)
    }
    #head-profiler {
        border: 1px solid white
    }
</style>

