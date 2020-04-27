import { routes } from './routes';

export function accessRole(role, roles) {
    if (!roles) {
        return true;
    } else if (roles.includes('*')) {
        return true;
    } else {
        //console.log(`Access: role-> ${role} / roles -> ${roles}`)
        return roles.includes(role)
    }
}

export function routeLinks(role) {
    let objRoutes = routes.find(route => route.path === '/app').children

    return objRoutes.filter(function (route) {
        return !route.meta.hidden && accessRole(role, route.meta.roles)
    })
}

export function staticRouteLinks() {
    let objRoutes = routes.find(route => route.path === '/app').children

    return objRoutes.filter(function (route) {
        return !route.meta.hidden && route.meta.static
    })
}
