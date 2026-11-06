/**
 * Lógica de frontend para el módulo de Mi Perfil
 * DS Beauty Academy
 */

document.addEventListener('DOMContentLoaded', () => {
    cargarDatosPerfil();
    configurarCarrusel();
    configurarEdicionPerfil();
});

async function cargarDatosPerfil() {
    try {
        const formData = new FormData();
        formData.append('obtenerPerfil', '1');

        const response = await fetch('?url=perfil', {
            method: 'POST',
            body: formData
        });

        const res = await response.json();
        if (res.status === 'success' && res.data) {
            renderizarPerfil(res.data);
        } else {
            console.error('Error al cargar datos del perfil:', res.message);
        }
    } catch (error) {
        console.error('Error en petición de perfil:', error);
    }
}

function renderizarPerfil(data) {
    // 1. Hero Card
    const elNombre = document.getElementById('perfilNombre');
    const elEmail = document.getElementById('perfilEmail');
    const elTelefono = document.getElementById('perfilTelefono');
    const elMiembro = document.getElementById('perfilMiembroDesde');
    const elRolTexto = document.getElementById('perfilRolTexto');

    if (elNombre) elNombre.textContent = data.nombre || 'Usuario';
    if (elEmail) elEmail.textContent = data.email || '';
    if (elTelefono) elTelefono.textContent = data.telefono || 'Sin registrar';
    if (elMiembro) elMiembro.textContent = data.miembro_desde || 'Enero de 2024';
    if (elRolTexto) elRolTexto.textContent = data.rol || 'Miembro';

    // Rellenar formulario modal de edición
    const inputTel = document.getElementById('inputTelefono');
    const inputIg = document.getElementById('inputInstagram');
    if (inputTel) inputTel.value = data.telefono || '';
    if (inputIg) inputIg.value = (data.instagram || '').replace(/^@/, '');

    // 2. Especialidades
    const containerEsp = document.getElementById('perfilEspecialidadesContainer');
    if (containerEsp && data.especialidades && data.especialidades.length > 0) {
        containerEsp.innerHTML = data.especialidades.map(esp => `
            <div class="especialidad-mini-box">
                <i class="fas ${esp.icono || 'fa-star'}"></i>
                <span>${escapeHtml(esp.nombre_especialidad)}</span>
            </div>
        `).join('');
    }

    // 3. Certificados
    const trackCert = document.getElementById('perfilCertificadosTrack');
    if (trackCert && data.certificados && data.certificados.length > 0) {
        trackCert.innerHTML = data.certificados.map(cert => `
            <div class="perfil-cert-card">
                <div class="cert-card-preview">
                    <div class="cert-watermark">
                        <span class="cert-logo-text">DS</span>
                        <span class="cert-academy-text">BEAUTY ACADEMY</span>
                    </div>
                    <div class="cert-label">CERTIFICADO</div>
                    <div class="cert-title">${escapeHtml(cert.titulo)}</div>
                    <div class="cert-date">Emitido: ${cert.fecha}</div>
                    <div class="cert-stamp">
                        <i class="fas fa-certificate"></i>
                    </div>
                </div>
                <div class="cert-card-action">
                    <a href="${cert.url_pdf && cert.url_pdf !== '#' ? cert.url_pdf : 'javascript:void(0)'}" 
                       target="${cert.url_pdf && cert.url_pdf !== '#' ? '_blank' : '_self'}" 
                       class="btn-cert-download ${!cert.url_pdf || cert.url_pdf === '#' ? 'btn-cert-simulated' : ''}">
                        <i class="fas fa-download"></i> Descargar
                    </a>
                </div>
            </div>
        `).join('');

        // Evento para certificados sin URL física directa
        trackCert.querySelectorAll('.btn-cert-simulated').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Descarga de Certificado',
                        text: 'El documento PDF oficial ha sido generado y preparado para su descarga.',
                        confirmButtonColor: '#E83382'
                    });
                } else {
                    alert('Generando descarga del certificado...');
                }
            });
        });
    }

    // 4. Estadísticas
    if (data.estadisticas) {
        const t1 = document.getElementById('statTitulo1');
        const v1 = document.getElementById('statValor1');
        const t2 = document.getElementById('statTitulo2');
        const v2 = document.getElementById('statValor2');
        const t3 = document.getElementById('statTitulo3');
        const v3 = document.getElementById('statValor3');

        if (t1) t1.textContent = data.estadisticas.item1_titulo;
        if (v1) v1.textContent = data.estadisticas.item1_valor;
        if (t2) t2.textContent = data.estadisticas.item2_titulo;
        if (v2) v2.textContent = data.estadisticas.item2_valor;
        if (t3) t3.textContent = data.estadisticas.item3_titulo;
        if (v3) v3.textContent = data.estadisticas.item3_valor;
    }
}

function configurarCarrusel() {
    const track = document.getElementById('perfilCertificadosTrack');
    const btnPrev = document.getElementById('btnCertPrev');
    const btnNext = document.getElementById('btnCertNext');
    const btnDownloadAll = document.getElementById('btnDescargarTodosCertificados');

    if (btnPrev && track) {
        btnPrev.addEventListener('click', () => {
            track.scrollBy({ left: -260, behavior: 'smooth' });
        });
    }

    if (btnNext && track) {
        btnNext.addEventListener('click', () => {
            track.scrollBy({ left: 260, behavior: 'smooth' });
        });
    }

    if (btnDownloadAll) {
        btnDownloadAll.addEventListener('click', () => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Descarga Completa',
                    text: 'Empaquetando todas tus certificaciones académicas en formato ZIP...',
                    confirmButtonColor: '#E83382'
                });
            }
        });
    }
}

function configurarEdicionPerfil() {
    const btnAbrir = document.getElementById('btnAbrirEditarPerfil');
    const formEditar = document.getElementById('formEditarPerfil');
    const modalEl = document.getElementById('modalEditarPerfil');

    if (btnAbrir && modalEl) {
        btnAbrir.addEventListener('click', () => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
    }

    if (formEditar) {
        formEditar.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btnGuardar = document.getElementById('btnGuardarPerfil');
            if (btnGuardar) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            }

            try {
                const formData = new FormData(formEditar);
                formData.append('actualizarPerfil', '1');

                const response = await fetch('?url=perfil', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();

                if (res.status === 'success') {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Actualizado!',
                            text: res.message || 'Tu información ha sido actualizada.',
                            confirmButtonColor: '#E83382'
                        });
                    }
                    cargarDatosPerfil();
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'No se pudo actualizar el perfil',
                            confirmButtonColor: '#E83382'
                        });
                    }
                }
            } catch (err) {
                console.error(err);
            } finally {
                if (btnGuardar) {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = 'Guardar Cambios';
                }
            }
        });
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

