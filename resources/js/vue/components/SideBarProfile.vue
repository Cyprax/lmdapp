<template>
    <div class="sideprofile bg-styled text-white">
        <form>
            <div class="form-group">
                <label>Statut</label>
                <select class="form-control custom-select" v-model="currentRole">
                    <option v-for="role in roles" :key="role.id" :value="role">{{ role.description }}</option>
                </select>
            </div>
            <!--<div class="form-group">
                <label>Filiere</label>
                <select class="form-control custom-select" v-model="filiereSelected">
                    <option v-for="filiere in dataFilieres" :key="filiere.id" :value="filiere">{{ filiere.label }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Classe</label>
                <select class="form-control custom-select" v-model="classeSelected">
                    <option v-for="classe in dataClasses" :key="classe.id" :value="classe">{{ classe.label }}</option>
                </select>
            </div>-->
        </form>
    </div>
</template>

<script>
import { accessRole } from '../../routes/filters'
export default {
    name: 'sidebarProfile',
    components: {
    },
    data: function() {
        return {/*
            dataClasses: [
                { id: 1, label: "ING STIC 2" },
                { id: 2, label: "ING INFO 2" },
            ],
            dataFilieres: [
                { id: 1, label: "ING STIC"}
            ],*/
            roleSelected: this.currentRole,
            /*classeSelected: { id: 1, label: "ING STIC 2" },
            filiereSelected: { id: 1, label: "ING STIC"}*/
        }
    },
    watch: {
        currentRole: function(role) {
            if ( !accessRole(role.label, this.$route.meta.roles) ) {
                console.log('Unauthorized')
                this.$router.push('/')
            }
        }
    },
    computed: {
        roles() {
            return this.$store.getters['auth/roles']
        },
        currentRole: {
            get: function() {
                return this.$store.getters['auth/currentRole']
            },
            set: function(newRole) {
                this.$store.dispatch("auth/updateCurrentRole", newRole)
            }
        },
    },
    mounted: function() {
        this.$nextTick( function() {
            this.roleSelected = this.currentRole
        })
    },
    async beforeCreate() {
        await this.$store.dispatch('auth/updateRoles')
    }
}
</script>

<style>
.sideprofile {
    padding: 0.5rem;
    /*border-left: thick solid #E95420;
    border-left: thick solid #E95420;*/

    /*
    background: radial-gradient(black 15%, transparent 16%) 0 0,
                radial-gradient(black 15%, transparent 16%) 8px 8px,
                radial-gradient(rgba(12,255,255,.1) 15%, transparent 20%) 0 1px,
                radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 8px 9px;
    background-color:rgb(70, 70, 70);
    background-size:16px 16px;
    */

}

</style>

<style scoped>
.form-group {
    margin-bottom: 0.5rem;
    text-align: right
}
label {
    font-style: italic;
    text-shadow: 3px 3px 6px black;
    margin-bottom: 0;
    font-weight: bold;
}
</style>
