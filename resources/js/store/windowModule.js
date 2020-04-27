export default {
    namespaced: true,

    state: {
        winState: null,
        winOverlayState: null,
        optionBarState: false,
    },

    getters: {
        winState(state) {
            return state.winState;
        },
        winOverlayState(state) {
            return state.winOverlayState;
        },
        winSideState(state, getters) {
            return (getters.winState !== "mobile") || (getters.winState === "mobile" && getters.winOverlayState)
        },
        optionBarState(state) {
            return state.optionBarState;
        },
    },

    mutations: {
        resizeWindow(state, payload) {
            state.winState = payload.winState
        },
        updateWinOverlay(state, payload) {
            state.winOverlayState = payload.winOverlayState
        },
        toggleOptionBarState(state) {
            state.optionBarState = !state.optionBarState
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

    }
};
