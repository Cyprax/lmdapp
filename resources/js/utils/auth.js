export function login(credentials) {

    return new Promise((res, rej) => {
        axios.get('/sanctum/csrf-cookie').then((preResponse) => {
            //console.log("preResponse: " + preResponse)
            axios.post('/api/auth/login', credentials).then( (response) => {
                console.log(response)
                setAuthorization(response.data.token);
                //localStorage.setItem('isLoggedIn', true)
                res(response.data);
            }).catch((error) => {
                //console.log("Wrong username or password: " + error.response.data)
                rej(error.response.data)
                //const key = Object.keys(error.response.data.errors)[0]
                //rej(error.response.data.errors[key])
            })
        }).catch( (preError) => {
            rej("Unable to resolve csrf's sanctum gate:" + preError)
        })
    })

}

export function logout() {
    return new Promise((res, rej) => {
        axios.post('api/auth/logout').then((response) => {
            //setAuthorization(null);
            //localStorage.removeItem('isLoggedIn')
            res(response.data);
        }).catch((error) => {
            rej(error)
        })
    })
}

export function getCurrentUser() {
    const userStr = localStorage.getItem("user");

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}

export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}
