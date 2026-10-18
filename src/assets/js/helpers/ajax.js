// ajax.js - Wrapper centralizado para peticiones asíncronas
import { showSuccessMessage, showErrorMessage, showConnectionError, showToast } from './sweetalert.js';

const ajaxRequest = (url, method = 'POST', data = {}, options = {}) => {
    const isFormData = data instanceof FormData;

    return $.ajax({
        url,
        method,
        dataType: 'json',
        data,
        processData: !isFormData,
        contentType: isFormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
        ...options
    });
};

// Endpoints CRUD adaptados a las convenciones de ds-beauty-academy
export const getAll = (url) => ajaxRequest(url, 'POST', { buscarTodos: 'true' });
export const getById = (url, id) => ajaxRequest(url, 'POST', { buscar: id });
export const remove = (url, id) => ajaxRequest(url, 'POST', { eliminar: id });

export const create = (url, data, options = {}) => {
    if (data instanceof FormData) {
        if (!data.has('guardar')) {
            data.append('guardar', 'true');
        }
        return ajaxRequest(url, 'POST', data, options);
    }
    return ajaxRequest(url, 'POST', { ...data, guardar: 'true' }, options);
};

export const update = (url, data, options = {}) => {
    if (data instanceof FormData) {
        if (!data.has('actualizar')) {
            data.append('actualizar', 'true');
        }
        return ajaxRequest(url, 'POST', data, options);
    }
    return ajaxRequest(url, 'POST', { ...data, actualizar: 'true' }, options);
};

// Función para parsear respuestas mixtas (en caso de que PHP envíe algún warning HTML antes del JSON)
const parseResponse = (response) => {
    if (typeof response === 'string') {
        try {
            return JSON.parse(response);
        } catch (e) {
            return {
                status: 'error',
                code: 500,
                message: 'Error: Respuesta del servidor no es un JSON válido'
            };
        }
    }
    return response;
};

// Manejo centralizado de errores HTTP / AJAX
const handleAjaxError = async (error) => {
    console.error('AJAX Error:', error);

    if (error.status === 0) {
        await showConnectionError();
        return;
    }

    if (error.responseJSON) {
        const errorMsg = error.responseJSON.message || error.responseJSON.error || 'Error en el servidor';
        await showErrorMessage(errorMsg);
        return;
    }

    if (error.responseText) {
        const parsedResponse = parseResponse(error.responseText);
        if (parsedResponse.message) {
            await showErrorMessage(parsedResponse.message);
            return;
        }
    }

    await showErrorMessage('Error de comunicación con el servidor');
};

/**
 * Ejecuta una promesa AJAX, gestiona el estado de éxito/error y llama a un callback
 * Considera exitosa una respuesta con:
 * - status === 'success'
 * - code === 200 o 201
 * - success === true
 */
export const executeAjax = async (ajaxPromise, successMessage = null, onSuccess = null) => {
    try {
        let response = await ajaxPromise;
        response = parseResponse(response);

        const isSuccess = response.status === 'success' || 
                          response.success === true || 
                          response.code === 200 || 
                          response.code === 201;

        if (isSuccess) {
            const msg = successMessage || response.message || 'Operación realizada correctamente';
            showToast(msg, 'success');
            if (onSuccess) onSuccess(response);
            return true;
        }

        const errorMsg = response.message || response.error || 'Ha ocurrido un error inesperado';
        await showErrorMessage(errorMsg);
        return false;
    } catch (error) {
        await handleAjaxError(error);
        return false;
    }
};
