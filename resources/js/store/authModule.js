import { getCurrentUser, login as signin, logout as signout, getRoles } from "../utils/auth";
const user = getCurrentUser();

export default {
    namespaced: true,

    state: {
        //User
        profileUser: {},
        currentUser: user,
        isLoggedIn: !!user,
        isLoading: false,
        authError: null,

        //Roles
        roles: [],
        currentRole: {
            label: 'student'
        }, //?????????????????????
    },

    getters: {
        //User
        profileUser(state) {
            return state.profileUser;
        },
        isLoading(state) {
            return state.isLoading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.authError;
        },

        //Roles
        roles(state) {
            return state.roles;
        },
        hasRoles(state) {
            return state.roles && state.roles.length > 0
        },
        currentRole(state) {
            return state.currentRole;
        },
        currentRoleName(state) {
            return state.currentRole.label ? state.currentRole.label : null;
        },

    },

    mutations: {
        //User
        updateProfileUser(state, payload) {
            state.profileUser = payload
        },
        login(state) {
            state.isLoading = true;
            state.authError = null;
        },
        loginSuccess(state, payload) {
            state.authError = null;
            state.isLoggedIn = true;
            state.isLoading = false;
            state.currentUser = Object.assign({}, payload.user, {
                token: payload.token
            });

            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.isLoading = false;
            state.authError = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        },

        //Roles
        updateRoles(state, payload) {
            state.roles = payload
        },
        staticCurrentRole(state) {
            let role = state.currentRole
            state.currentRole = null
            state.currentRole = role
        },
        updateCurrentRole(state, payload) {
            state.currentRole = payload
        },
   },

    actions: {
        //User
        updateProfileUser(context) {
            axios.get('/api/user')
                .then((response) => {
                    context.commit("updateProfileUser", response.data)
                })
        },
        logout(context, {router, method, that}) {
            signout().then((rep) => {
                context.commit('logout');
                router.push({ path: '/login' });
                that.$bvToast.toast(
                    "Vous avez été déconnecté avec sucès", {
                        title: "Déconnexion réussie",
                        variant: "success"
                    }
                )
                console.log(method)
            }).catch((error) => {
                that.$bvToast.toast(
                    "Nous avons rencontré une erreur en essayant de vous déconnecter!",
                    {
                        title: "Echec de déconnexion",
                        variant: "warning"
                    }
                )
            })
        },
        login(context, {data, router, method, that}) {
            context.commit("login");

            signin(data).then((res) => {
                context.commit("loginSuccess", res);
                router.push({ path: '/' });

                that.$bvToast.toast(
                    "Bienvenue sur la plateforme LmdApp", {
                        title: "Connexion réussie",
                        variant: "success"
                    }
                )
            }).catch((error) => {
                console.log(error)
                console.log(router)
                context.commit("loginFailed", { error });
                that.$bvToast.toast(
                    "Echec de la connexion", {
                        title: "Message: " + error,
                        variant: "danger"
                    }
                )
            });
        },

        //Roles
        updateRoles(context, getters) {
            axios.get('api/user/roles')
                .then((response) => {
                    console.log("roles:" + response.data + " and count: " + response.data.length)
                    context.commit("updateRoles", response.data)
                    /*if (!getters.roles.includes(getters.currentRole)) {
                        let firstRole = response.data.length > 0 ? response.data[0] : null
                        context.commit("updateCurrentRole", firstRole)
                    }*/
                    let firstRole = response.data.length > 0 ? response.data[0] : null
                    context.commit("updateCurrentRole", firstRole)
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        updateCurrentRole(context, payload) {
            if (!payload || !payload.label) {
                context.commit("updateCurrentRole", null)
            } else {
                axios.post('api/user/hasRole', payload) //payload: {label: 'role'}
                    .then((response) => {
                        console.log(payload.label)
                        console.log(response.data)
                        if (response.data) {
                            context.commit("updateCurrentRole", payload)
                        } else {
                            console.log("Unauthorized")
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
    }
};
