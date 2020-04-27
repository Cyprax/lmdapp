//Params && Datas -----------------------------------------------
export function getParams(mode, role) { //PARAMS FUNCTION (post mode)
    let apiUrl = '';
    switch (mode) {
        case 'eval': apiUrl = 'api/eval/params'; break;
        default: break;
    };

    return universalApi('post', apiUrl, {
        'role': role
    })
}

export function getDatas(mode, role, datas, paramMode) { //INDEX FUNCTION (post mode); datas: pileDatas
    let apiUrl = '';
    switch (mode) {
        case 'eval': apiUrl = 'api/eval/datas/index'; break;
        default: break;
    };

    return universalApi('post', apiUrl, {
        'role': role,
        'paramMode': paramMode,
        'params': _.map( datas, (obj) => {
            return _.pick( obj, ['label', 'id'] )
        } )
    })
}
//CRUD operations -----------------------------------------

export function crudRead(mode, role, id) { //SHOW FUNCTION
    let apiUrl = ''
    switch (mode) {
        case 'eval': apiUrl = `api/eval/datas/${id}`; break;
        default: break;
    }

    return universalApi('get', apiUrl, {
        'role': role,
    })
}

export function crudUpdate(mode, role, id, data) { //UPDATE FUNCTION
    let apiUrl = ''
    switch (mode) {
        case 'eval': apiUrl = `api/eval/datas/${id}`; break;
        default: break;
    }

    return universalApi('patch', apiUrl, {
        'role': role,
        'data': data
    })
}

export function crudDelete(mode, role, id) { //DESTROY FUNCTION
    let apiUrl = ''
    switch (mode) {
        case 'eval': apiUrl = `api/eval/datas/${id}`; break;
        default: break;
    }

    return universalApi('delete', apiUrl, {
        'role': role,
    })
}

//Universal Axios function---------------------------------
export async function universalApi(apiMethod, apiUrl, apiData = {}) {
    const config = {
        method: apiMethod,
        url: apiUrl,
        data: apiData
    }
    let response = await axios(config)
    return response;
}

