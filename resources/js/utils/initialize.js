import { accessRole } from "../routes/filters";
import { setAuthorization } from "../utils/auth"

export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const requiresGuess = to.matched.some(record => record.meta.requiresGuess);
        const currentUser = store.state.auth.currentUser;

        if(requiresAuth && !currentUser) {
            next('/login');
        } else if (requiresGuess && currentUser) {
            next('/');
        } else if(currentUser && to.meta.roles && !accessRole(store.getters['auth/currentRole'].label, to.meta.roles)) {
            next('/');
        } else {
            next();
        }
    });

    //Expiration du token => Déconnexion automatique
    axios.interceptors.response.use(null, (error) => {
        if (error.response.status == 401) {
            store.commit('auth/logout');
            router.push('/login');
        }

        return Promise.reject(error);
    });

    if (store.getters['auth/currentUser']) {
        setAuthorization(store.getters['auth/currentUser'].token);
    }
}




