import { getCurrentUser, login as signin, logout as signout, getRoles } from "../utils/auth";
const user = getCurrentUser();

export default {
    namespaced: true,

    state: {
        //User
        isLoggedIn: !!user,
        isLoading: false,
        authError: null,
        profileUser: {},
        currentUser: user,

        //Roles
        roles: [],
        currentRole: {
            label: 'student'
        }, //?????????????????????
    },

    getters: {
        //Auth
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

        //User
        profileUser(state) {
            return state.profileUser;
        },

    },

    mutations: {
        //Auth
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

        //User
        updateProfileUser(state, payload) {
            state.profileUser = payload
        },

   },

    actions: {
        //Auth
        logout(context, {router, snotify}) {
            signout().then((rep) => {
                context.commit('logout');
                router.push({ path: '/login' });
                snotify.success('Déconnexion réussie');
            }).catch((error) => {
                snotify.warning('Echec de déconnexion: Une erreur a été détectée!');
            })
        },
        login(context, {data, router, snotify}) {
            context.commit("login");

            signin(data).then((res) => {
                context.commit("loginSuccess", res);
                router.push({ path: '/' });
                snotify.success('Bienvenue sur la plateforme LmdApp');
            }).catch((error) => {
                console.log(error)
                console.log(router)
                context.commit("loginFailed", { error });
                snotify.error('Echec de la connexion');
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

        //User
        updateProfileUser(context) {
            axios.get('/api/user')
                .then((response) => {
                    context.commit("updateProfileUser", response.data)
                })
        },
    }
};
