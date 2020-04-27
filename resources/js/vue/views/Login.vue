<template>
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="32" height="32">
                    <h1 class="h3 text-gray-900 mb-2">Bienvenue!</h1>
                    <h4 class="h6 text-gray-700 mb-2">Veuillez vous connecter pour accéder à la plateforme.</h4>
                </div>
                <hr>

                <form class="user" @submit.prevent="authenticate">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" class="form-control form-control-user" placeholder="Matricule ou email" v-model="form.username" :disabled="isLoading">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" class="form-control form-control-user" placeholder="Mot de passe" v-model="form.password" :disabled="isLoading">
                    </div>
                    <div class="form-group">
                        <b-form-checkbox id="remember-me" name="remember-me" :value="true" :unchecked-value="false" v-model="form.rememberMe" :disabled="isLoading">
                            Se souvenir de moi
                        </b-form-checkbox>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" value="Login" :disabled="isLoading">
                        <b-spinner v-show="isLoading"></b-spinner>
                        Se connecter
                    </button>
                </form>

                <hr>
                <div class="text-center">
                    <b-link to="./login/reset">Mot de passe oublié?</b-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {login} from '../../utils/auth';

export default {
    name: "loginView",
    data() {
        return {
            form: {
                username: "wdavid",//role: "student"
                //username: "georges.aurore",//role: "professor"
                //username: "emmanuelle.couturier",//role: "director"
                //username: "rdurand",//role: "inspector"
                //username: "15INP00767",//role: "admin"
                password: "mdp",
                rememberMe: false,
            },
        }
    },
    computed: {
        authError() {
            return this.$store.getters['auth/authError'];
        },
        isLoading() {
            return this.$store.getters['auth/isLoading'];
        }
    },
    methods: {
        authenticate() {
            //this.loading = true
            this.$store.dispatch('auth/login', {
                data: this.$data.form,
                router: this.$router,
                method: this.makeToast,
                that: this
            });

            /*login(this.$data.form)
                .then((res) => {
                    this.$store.commit("auth/loginSuccess", res);
                    console.log(res)
                    this.makeToast(
                        "Connexion réussie",
                        "Bienvenue sur la plateforme LmdApp",
                        "success",
                    )
                    this.loading = false
                    this.$router.push({path: '/'});
                })
                .catch((error) => {
                    this.$store.commit("auth/loginFailed", {error});
                    console.log(error)
                    this.makeToast(
                        "Echec de la connexion",
                        "Message: " + error,
                        "danger",
                    )
                    this.loading = false;
                });
            *///this.loading = false;
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
}
</script>

<style scoped>
.bg-login-image {
    background: url("../../../assets/images/img-login.jpeg");
    background-position: center;
    background-size: cover;
    border-top-left-radius: .35rem;
    border-bottom-left-radius: .35rem;
}
</style>
