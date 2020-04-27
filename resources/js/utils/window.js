const breakpoint = 768;

export function getCurrentWindowState() {
    const userStr = localStorage.getItem("user");

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}

export function getCurrentWinState() {
    return {
        winState: document.documentElement.clientWidth <= breakpoint ?  "mobile" : "desktop"
    };
}

//##################################
/*function isLoggedIn() {
    return localStorage.getItem('isLoggedIn')
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!isLoggedIn()) {
            next({
                name: 'Home',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (isLoggedIn()) {
            next({
                name: 'Dashboard',
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

*/
