import { getCurrentUser, getRoles } from "./utils/auth";
const user = getCurrentUser();

export default {
    state: {
        //Window
        winState: null,
        winOverlayState: null,
        optionBarState: false,

        //Roles
        roles: [],
        currentRole: {label: 'student'}, //?????????????????????
        //User
        profileUser: {},
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        customers: [],

        //Others
        tempData: [],
        currentModalDatas: { label: null, data: [] }
    },
    getters: {
        //Window
        winState(state) {
            return state.winState;
        },
        winOverlayState(state) {
            return state.winOverlayState;
        },
        winSideState(state, getters) {
            return ( getters.winState !== "mobile" ) || ( getters.winState === "mobile" && getters.winOverlayState )
        },
        optionBarState(state) {
            return state.optionBarState;
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
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        },
        customers(state) {
            return state.customers;
        },

        //Others
        tempData(state) {
            return state.tempData;
        },
        currentModalDatas(state) {
            return state.currentModalDatas;
        }
    },
    mutations: {
        //Window
        resizeWindow(state, payload) {
            state.winState = payload.winState
        },
        updateWinOverlay(state, payload) {
            state.winOverlayState = payload.winOverlayState
        },
        toggleOptionBarState(state) {
            state.optionBarState = !state.optionBarState
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
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.token});

            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        },
        updateCustomers(state, payload) {
            state.customers = payload;
        },

        //Others
        updateTempData(state, payload) {
            state.tempData = payload;
        },
        updateCurrentModalDatas(state, payload) {
            state.currentModalDatas.label = payload.label;
            state.currentModalDatas.data = payload.data;
        },
    },
    actions: {
        /*
        //Window
        resizeWindow(context, payload) {
            context.commit("resizeWindow", payload);
        },
        updateOverlay(context, payload) {
            context.commit("updateOverlay", paylolad);
        }
        */

        //Roles
        updateRoles(context, getters) {
            axios.get('api/user/roles')
            .then( (response) => {
                console.log("roles:" + response.data + " and count: " + response.data.length)
                context.commit("updateRoles", response.data)
                /*if (!getters.roles.includes(getters.currentRole)) {
                    let firstRole = response.data.length > 0 ? response.data[0] : null
                    context.commit("updateCurrentRole", firstRole)
                }*/
                let firstRole = response.data.length > 0 ? response.data[0] : null
                context.commit("updateCurrentRole", firstRole)
            })
            .catch( (error) => {
                console.log(error)
            })
        },
        updateCurrentRole(context, payload) {
            if (!payload || !payload.label) {
                context.commit("updateCurrentRole", null)
            } else {
                axios.post('api/user/hasRole', payload) //payload: {label: 'role'}
                .then( (response) => {
                    console.log(payload.label)
                    console.log(response.data)
                    if (response.data) {
                        context.commit("updateCurrentRole", payload)
                    } else {
                        console.log("Unauthorized")
                    }
                })
                .catch( (error) => {
                    console.log(error)
                })
            }
        },

        //User
        updateProfileUser(context) {
            axios.get('/api/user')
            .then( (response) => {
                context.commit("updateProfileUser", response.data)
            })
        },
        login(context) {
            context.commit("login");
        },
        getCustomers(context) {
            axios.get('/api/customers')
            .then((response) => {
                context.commit('updateCustomers', response.data.customers);
            })
        },
        logout(context) {
            context.commit("logout");
        },

        //TempData
        updateTempData(context, payload) {
            //payload: {function: apiFunction, params: apiParams}

            payload.function(payload.params)
            .then((apiObj) => {
                console.log('here')
                if (!apiObj.error) {
                    context.commit("updateTempData", apiObj.data)
                } else {
                    context.commit("updateTempData", apiObj.default)
                }
            })
        },
        updateTempDataStandard(context, payload) {
            axios.post('/api/user/classes', payload)
            .then((response) => {
                context.commit("updateTempData", response.data)
            }).catch((error) => {
                console.log('error; ' + error)
            })
        }
    }
};
