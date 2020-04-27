export default {
    namespaced: true,

    state: {
        tempData: [],
        currentModalDatas: {
            label: null,
            data: []
        }
    },

    getters: {
        tempData(state) {
            return state.tempData;
        },
        currentModalDatas(state) {
            return state.currentModalDatas;
        }
    },

    mutations: {
        updateTempData(state, payload) {
            state.tempData = payload;
        },
        updateCurrentModalDatas(state, payload) {
            state.currentModalDatas.label = payload.label;
            state.currentModalDatas.data = payload.data;
        },

    },

    actions: {
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
