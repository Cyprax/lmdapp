<template>
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="32" height="32">
                    <h1 class="h3 text-gray-900 mb-2">Mot de passe oublié?</h1>
                    <h4 class="h6 text-gray-700 mb-2">Récupérez en toute sécurité votre compte.</h4>
                    <hr>
                </div>
                <ThemeAlert/>

                <form class="user" @submit.prevent="reset">
                    <div class="form-group mb-4">
                        <input type="text" id="email" class="form-control form-control-user" placeholder="Renseignez votre email" v-model="form.email">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" value="Login">
                        Réinitialiser le mot de passe
                    </button>
                </form>

                <hr>
                <div class="text-center">
                    <b-link to=".">Vous avez déjà un compte? Connectez-vous!</b-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ThemeAlert from '../components/ThemeAlert'

import {login} from '../../utils/auth';

export default {
    name: "loginResetView",
    components: {
        ThemeAlert
    },
    data() {
        return {
            form: {
                email: "dev.cyprax@gmail.com"
            },
            error: null
        }
    },
    methods: {
        reset() {
            /*
            this.$store.dispatch('auth/login');

            login(this.$data.form)
                .then((res) => {
                    this.$store.commit("auth/loginSuccess", res);
                    this.$router.push({path: '/'});
                })
                .catch((error) => {
                    this.$store.commit("auth/loginFailed", {error});
                });

            */
        }
    },
    computed: {
        authError() {
            return this.$store.getters['auth/authError'];
        }
    }
}
</script>

<style scoped>
.bg-login-image {
    background: url("../../../assets/images/img-reset.jpeg");
    background-position: center;
    background-size: cover;
    border-top-left-radius: .35rem;
    border-bottom-left-radius: .35rem;
}
</style>
