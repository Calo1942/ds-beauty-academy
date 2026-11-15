/**
 * Lógica de frontend para el módulo de Mi Perfil
 * DS Beauty Academy
 */

let perfilActual = null;

document.addEventListener('DOMContentLoaded', () => {
    cargarDatosPerfil();
    configurarFotoPerfil();
    configurarCarrusel();
    configurarEdicionPerfil();
});

/**
 * Carga todos los datos del perfil desde el backend
 */
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
            perfilActual = res.data;
            renderizarPerfil(res.data);
            const skeleton = document.getElementById('perfilSkeleton');
            const realContent = document.getElementById('perfilRealContent');
            if (skeleton && realContent) {
                $(skeleton).fadeOut(200, () => {
                    $(realContent).fadeIn(250);
                });
            }
        } else {
            console.error('Error al cargar datos del perfil:', res.message);
        }
    } catch (error) {
        console.error('Error en petición de perfil:', error);
    }
}

/**
 * Renderiza la interfaz de usuario con los datos reales del perfil
 */
function renderizarPerfil(data) {
    // 1. Hero Card - Información General
    const elNombre = document.getElementById('perfilNombre');
    const elEmail = document.getElementById('perfilEmail');
    const elTelefono = document.getElementById('perfilTelefono');
    const elMiembro = document.getElementById('perfilMiembroDesde');
    const elRolTexto = document.getElementById('perfilRolTexto');

    if (elNombre) elNombre.textContent = data.nombre || 'Usuario';
    if (elEmail) elEmail.textContent = data.email || '';
    if (elTelefono) elTelefono.textContent = data.telefono || 'Sin teléfono registrado';
    if (elMiembro) elMiembro.textContent = data.miembro_desde || 'Enero de 2026';
    if (elRolTexto) elRolTexto.textContent = data.rol || 'Miembro';

    // 2. Avatar / Foto de Perfil
    actualizarAvatarEnDOM(data.foto);

    // 3. Rellenar campos del formulario modal de edición
    const inputPName = document.getElementById('inputPrimerNombre');
    const inputPLast = document.getElementById('inputPrimerApellido');
    const inputTel = document.getElementById('inputTelefono');
    const inputTelAlt = document.getElementById('inputTelefonoAlt');
    const inputIg = document.getElementById('inputInstagram');

    if (inputPName) inputPName.value = data.primer_nombre || '';
    if (inputPLast) inputPLast.value = data.primer_apellido || '';
    if (inputTel) inputTel.value = data.telefono || '';
    if (inputTelAlt) inputTelAlt.value = data.telefono_alternativo || '';
    if (inputIg) inputIg.value = (data.instagram || '').replace(/^@/, '');

    // 4. Tarjeta Superior Derecha: "Mis Especialidades" (Instructor) o "Mis Cursos" (Estudiante)
    renderizarEspecialidadesOCursos(data);

    // 5. Tarjeta Inferior: "Mis Certificados" o "Diplomas"
    renderizarCertificados(data);

    // 6. Tarjeta de Estadísticas
    renderizarEstadisticas(data.estadisticas);
}

/**
 * Actualiza el avatar en el hero card y en el navbar superior
 */
function actualizarAvatarEnDOM(fotoUrl) {
    const elImg = document.getElementById('perfilAvatarImg');
    const elIcon = document.getElementById('perfilAvatarIcon');
    const btnEliminarFotoModal = document.getElementById('btnModalEliminarFoto');
    const elNavbarAvatar = document.getElementById('navbarUserAvatar');

    if (fotoUrl) {
        // Hero Card
        if (elImg) {
            elImg.src = fotoUrl;
            elImg.style.display = 'block';
        }
        if (elIcon) elIcon.style.display = 'none';

        // Modal botón eliminar
        if (btnEliminarFotoModal) btnEliminarFotoModal.style.display = 'inline-block';

        // Navbar
        if (elNavbarAvatar) {
            if (elNavbarAvatar.tagName.toLowerCase() === 'img') {
                elNavbarAvatar.src = fotoUrl;
            } else {
                // Reemplazar icono por imagen
                const newImg = document.createElement('img');
                newImg.src = fotoUrl;
                newImg.alt = 'Avatar';
                newImg.className = 'user-avatar';
                newImg.id = 'navbarUserAvatar';
                newImg.style.objectFit = 'cover';
                elNavbarAvatar.parentNode.replaceChild(newImg, elNavbarAvatar);
            }
        }
    } else {
        // Hero Card
        if (elImg) elImg.style.display = 'none';
        if (elIcon) elIcon.style.display = 'block';

        // Modal botón eliminar
        if (btnEliminarFotoModal) btnEliminarFotoModal.style.display = 'none';

        // Navbar
        if (elNavbarAvatar && elNavbarAvatar.tagName.toLowerCase() === 'img') {
            const newIcon = document.createElement('i');
            newIcon.className = 'fas fa-user-circle user-avatar';
            newIcon.id = 'navbarUserAvatar';
            elNavbarAvatar.parentNode.replaceChild(newIcon, elNavbarAvatar);
        }
    }
}

/**
 * Renderiza dinámicamente las especialidades (Instructor/Admin) o los cursos (Estudiante)
 */
function renderizarEspecialidadesOCursos(data) {
    const container = document.getElementById('perfilEspecialidadesContainer');
    const titulo = document.getElementById('tituloCardEspecialidades');
    const icono = document.getElementById('iconoCardEspecialidades');
    const link = document.getElementById('linkCardEspecialidades');

    if (!container) return;

    if (data.tipo_perfil === 'estudiante') {
        // Contexto ESTUDIANTE: Mostrar cursos inscritos
        if (titulo) titulo.textContent = 'Mis Cursos en Formación';
        if (icono) icono.className = 'fas fa-book-open text-accent';
        if (link) {
            link.href = '?url=matricula';
            link.innerHTML = 'Mis matrículas &rarr;';
        }

        if (data.cursos && data.cursos.length > 0) {
            container.innerHTML = data.cursos.map(c => {
                const cupoClass = (c.estatus_cupo || '').toLowerCase();
                return `
                    <div class="curso-mini-box">
                        <div class="curso-mini-header">
                            <span class="curso-mini-title">${escapeHtml(c.nombre_curso)}</span>
                            <span class="curso-mini-badge ${cupoClass}">${escapeHtml(c.estatus_cupo)}</span>
                        </div>
                        <div class="curso-mini-instructor">
                            <i class="fas fa-chalkboard-teacher text-accent"></i>
                            <span>${escapeHtml(c.nombre_instructor || 'Instructor DS')}</span>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = `
                <div class="perfil-empty-box">
                    <i class="fas fa-book-reader"></i>
                    <p class="text-white fw-semibold mb-0">Sin cursos activos</p>
                    <small class="text-muted">No estás matriculado en ningún curso actualmente.</small>
                </div>
            `;
        }
    } else if (data.tipo_perfil === 'instructor') {
        // Contexto INSTRUCTOR: Mostrar especialidades asignadas
        if (titulo) titulo.textContent = 'Mis Especialidades';
        if (icono) icono.className = 'far fa-star text-accent';
        if (link) {
            link.href = '?url=especialidad';
            link.innerHTML = 'Ver todas &rarr;';
        }

        if (data.especialidades && data.especialidades.length > 0) {
            container.innerHTML = data.especialidades.map(esp => `
                <div class="especialidad-mini-box">
                    <i class="fas ${esp.icono || 'fa-star'}"></i>
                    <span>${escapeHtml(esp.nombre_especialidad)}</span>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="perfil-empty-box">
                    <i class="far fa-star"></i>
                    <p class="text-white fw-semibold mb-0">Sin especialidades asignadas</p>
                    <small class="text-muted">Contacta a coordinación para asociar tus áreas docentes.</small>
                </div>
            `;
        }
    } else {
        // Contexto ADMINISTRADOR: Catálogo de especialidades de la academia
        if (titulo) titulo.textContent = 'Especialidades de la Academia';
        if (icono) icono.className = 'far fa-star text-accent';
        if (link) {
            link.href = '?url=especialidad';
            link.innerHTML = 'Gestionar &rarr;';
        }

        if (data.especialidades && data.especialidades.length > 0) {
            container.innerHTML = data.especialidades.slice(0, 8).map(esp => `
                <div class="especialidad-mini-box">
                    <i class="fas ${esp.icono || 'fa-star'}"></i>
                    <span>${escapeHtml(esp.nombre_especialidad)}</span>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="perfil-empty-box">
                    <i class="far fa-star"></i>
                    <p class="text-white fw-semibold mb-0">Sin especialidades registradas</p>
                </div>
            `;
        }
    }
}

/**
 * Renderiza los certificados reales o estado vacío
 */
function renderizarCertificados(data) {
    const trackCert = document.getElementById('perfilCertificadosTrack');
    const titulo = document.getElementById('tituloCardCertificados');
    const desc = document.getElementById('descCardCertificados');
    const btnDownloadAll = document.getElementById('btnDescargarTodosCertificados');
    const btnPrev = document.getElementById('btnCertPrev');
    const btnNext = document.getElementById('btnCertNext');

    if (!trackCert) return;

    if (data.tipo_perfil === 'instructor') {
        if (titulo) titulo.textContent = 'Mis Diplomas y Acreditaciones';
        if (desc) desc.textContent = 'Acreditaciones profesionales y certificaciones académicas';
    } else {
        if (titulo) titulo.textContent = 'Mis Certificados';
        if (desc) desc.textContent = 'Tus certificaciones y avales académicos oficiales';
    }

    if (data.certificados && data.certificados.length > 0) {
        if (btnDownloadAll) btnDownloadAll.style.display = 'inline-flex';
        if (btnPrev) btnPrev.style.display = 'flex';
        if (btnNext) btnNext.style.display = 'flex';

        trackCert.innerHTML = data.certificados.map(cert => {
            const tienePdf = cert.url_pdf && cert.url_pdf !== '#' && cert.url_pdf.trim() !== '';
            return `
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
                        ${tienePdf ? `
                            <a href="${escapeHtml(cert.url_pdf)}" target="_blank" class="btn-cert-download">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                        ` : `
                            <span class="btn-cert-download" style="opacity: 0.6; cursor: default;">
                                <i class="fas fa-check-circle"></i> Emitido
                            </span>
                        `}
                    </div>
                </div>
            `;
        }).join('');
    } else {
        // Estado vacío limpio sin datos inventados
        if (btnDownloadAll) btnDownloadAll.style.display = 'none';
        if (btnPrev) btnPrev.style.display = 'none';
        if (btnNext) btnNext.style.display = 'none';

        trackCert.innerHTML = `
            <div class="perfil-empty-cert-track">
                <i class="fas fa-award"></i>
                <h4>Sin certificaciones emitidas aún</h4>
                <p>Una vez completes y apruebes tus programas formativos, tus diplomas y certificados avalados aparecerán aquí para su descarga oficial.</p>
            </div>
        `;
    }
}

/**
 * Renderiza los valores de la tarjeta de estadísticas
 */
function renderizarEstadisticas(stats) {
    if (!stats) return;

    const t1 = document.getElementById('statTitulo1');
    const v1 = document.getElementById('statValor1');
    const t2 = document.getElementById('statTitulo2');
    const v2 = document.getElementById('statValor2');
    const t3 = document.getElementById('statTitulo3');
    const v3 = document.getElementById('statValor3');

    if (t1) t1.textContent = stats.item1_titulo;
    if (v1) v1.textContent = stats.item1_valor;
    if (t2) t2.textContent = stats.item2_titulo;
    if (v2) v2.textContent = stats.item2_valor;
    if (t3) t3.textContent = stats.item3_titulo;
    if (v3) v3.textContent = stats.item3_valor;
}

/**
 * Configuración de la funcionalidad de foto de perfil con recorte y ajuste interactivo
 */
let cropperInstance = null;

function configurarFotoPerfil() {
    const btnEditarFoto = document.getElementById('btnEditarFoto');
    const circleAvatar = document.getElementById('perfilAvatarCircle');
    const inputFoto = document.getElementById('inputFotoPerfil');
    const btnModalSubir = document.getElementById('btnModalSubirFoto');
    const btnModalEliminar = document.getElementById('btnModalEliminarFoto');

    // Elementos del modal de recorte
    const modalRecorte = document.getElementById('modalRecortarFoto');
    const imgParaRecortar = document.getElementById('imagenParaRecortar');
    const btnCerrarRecorte = document.getElementById('btnCerrarModalRecorte');
    const btnCancelarRecorte = document.getElementById('btnCancelarRecorte');
    const btnGuardarRecorte = document.getElementById('btnGuardarRecorte');

    // Herramientas de ajuste
    const btnZoomIn = document.getElementById('btnCropperZoomIn');
    const btnZoomOut = document.getElementById('btnCropperZoomOut');
    const btnRotateLeft = document.getElementById('btnCropperRotateLeft');
    const btnRotateRight = document.getElementById('btnCropperRotateRight');
    const btnReset = document.getElementById('btnCropperReset');

    const cerrarModalRecorte = () => {
        if (cropperInstance) {
            try {
                cropperInstance.destroy();
            } catch (err) {
                console.warn('Error al destruir cropper:', err);
            }
            cropperInstance = null;
        }
        const wrapper = document.getElementById('cropperContainerWrapper');
        if (wrapper) {
            wrapper.innerHTML = '<img id="imagenParaRecortar" src="" alt="Ajustar Imagen">';
        }
        if (modalRecorte) modalRecorte.classList.remove('show');
        if (inputFoto) inputFoto.value = '';
    };

    window.cerrarModalRecorte = cerrarModalRecorte;

    if (btnCerrarRecorte) btnCerrarRecorte.addEventListener('click', cerrarModalRecorte);
    if (btnCancelarRecorte) btnCancelarRecorte.addEventListener('click', cerrarModalRecorte);

    if (modalRecorte) {
        modalRecorte.addEventListener('click', (e) => {
            if (e.target === modalRecorte) cerrarModalRecorte();
        });
    }

    // Centrar y ajustar la imagen completa en el contenedor
    const ajustarImagenCompleta = () => {
        if (!cropperInstance) return;
        const containerData = cropperInstance.getContainerData();
        const imageData = cropperInstance.getImageData();
        if (containerData.width && containerData.height && imageData.naturalWidth && imageData.naturalHeight) {
            const scaleX = (containerData.width * 0.85) / imageData.naturalWidth;
            const scaleY = (containerData.height * 0.85) / imageData.naturalHeight;
            const initialScale = Math.min(scaleX, scaleY);
            if (initialScale > 0) {
                cropperInstance.zoomTo(initialScale);
                const currentImg = cropperInstance.getImageData();
                const left = (containerData.width - currentImg.width) / 2;
                const top = (containerData.height - currentImg.height) / 2;
                cropperInstance.moveTo(left, top);
            }
        }
    };

    // Controles interactivos de recorte y giro
    if (btnZoomIn) btnZoomIn.addEventListener('click', () => cropperInstance && cropperInstance.zoom(0.15));
    if (btnZoomOut) btnZoomOut.addEventListener('click', () => cropperInstance && cropperInstance.zoom(-0.15));
    if (btnRotateLeft) btnRotateLeft.addEventListener('click', () => cropperInstance && cropperInstance.rotate(-90));
    if (btnRotateRight) btnRotateRight.addEventListener('click', () => cropperInstance && cropperInstance.rotate(90));
    if (btnReset) btnReset.addEventListener('click', () => {
        if (!cropperInstance) return;
        cropperInstance.reset();
        ajustarImagenCompleta();
    });

    // Disparar selector de archivos
    const dispararSelector = () => {
        if (inputFoto) inputFoto.click();
    };

    if (btnEditarFoto) btnEditarFoto.addEventListener('click', (e) => {
        e.stopPropagation();
        dispararSelector();
    });

    if (circleAvatar) circleAvatar.addEventListener('click', dispararSelector);
    if (btnModalSubir) btnModalSubir.addEventListener('click', dispararSelector);

    // Cuando se selecciona un archivo -> ABRIR MODAL DE AJUSTE Y RECORTE
    if (inputFoto) {
        inputFoto.addEventListener('change', () => {
            const file = inputFoto.files[0];
            if (!file) return;

            // Validación en el cliente
            const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Formato no válido',
                        text: 'Por favor, selecciona una imagen en formato JPG, PNG o WEBP.',
                        confirmButtonColor: '#E83382'
                    });
                } else {
                    alert('Por favor selecciona una imagen válida (JPG, PNG o WEBP).');
                }
                inputFoto.value = '';
                return;
            }

            if (file.size > 8 * 1024 * 1024) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Imagen muy pesada',
                        text: 'El tamaño de la imagen no debe superar los 8 MB.',
                        confirmButtonColor: '#E83382'
                    });
                } else {
                    alert('El tamaño de la imagen no debe superar los 8 MB.');
                }
                inputFoto.value = '';
                return;
            }

            // Destruir cualquier instancia previa de Cropper
            if (cropperInstance) {
                try {
                    cropperInstance.destroy();
                } catch (err) {
                    console.warn('Error al destruir cropper:', err);
                }
                cropperInstance = null;
            }

            // Leer archivo y montarlo en Cropper de forma estable
            const reader = new FileReader();
            reader.onload = (e) => {
                const wrapper = document.getElementById('cropperContainerWrapper');
                if (!wrapper) return;

                // Limpiar contenedor y crear nueva etiqueta img
                wrapper.innerHTML = '';
                const newImg = document.createElement('img');
                newImg.id = 'imagenParaRecortar';
                newImg.alt = 'Ajustar Imagen';
                newImg.src = e.target.result;
                wrapper.appendChild(newImg);

                // Mostrar el modal primero para que el layout tenga dimensiones calculables
                if (modalRecorte) modalRecorte.classList.add('show');

                // Inicializar Cropper.js una vez que la imagen esté cargada y el DOM renderizado
                newImg.onload = () => {
                    setTimeout(() => {
                        if (!modalRecorte.classList.contains('show')) return;
                        if (cropperInstance) {
                            try {
                                cropperInstance.destroy();
                            } catch (err) { }
                            cropperInstance = null;
                        }

                        if (typeof Cropper !== 'undefined') {
                            cropperInstance = new Cropper(newImg, {
                                aspectRatio: 1,
                                viewMode: 0,
                                dragMode: 'move',
                                autoCropArea: 0.85,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                                preview: '.perfil-cropper-preview',
                                zoomOnWheel: true,
                                zoomOnTouch: true,
                                wheelZoomRatio: 0.1,
                                ready() {
                                    ajustarImagenCompleta();
                                }
                            });
                        }
                    }, 80);
                };
            };
            reader.readAsDataURL(file);
        });
    }

    // ACEPTAR Y GUARDAR: Procesar canvas recortado y subir al servidor
    if (btnGuardarRecorte) {
        btnGuardarRecorte.addEventListener('click', () => {
            if (!cropperInstance) return;

            const canvas = cropperInstance.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            if (!canvas) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al procesar',
                        text: 'No se pudo recortar la imagen seleccionada.',
                        confirmButtonColor: '#E83382'
                    });
                }
                return;
            }

            btnGuardarRecorte.disabled = true;
            btnGuardarRecorte.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';

            canvas.toBlob(async (blob) => {
                if (!blob) {
                    btnGuardarRecorte.disabled = false;
                    btnGuardarRecorte.innerHTML = '<i class="fas fa-check me-1"></i> Aceptar y Guardar Foto';
                    return;
                }

                const formData = new FormData();
                formData.append('subirFotoPerfil', '1');
                formData.append('foto_perfil', blob, 'avatar.jpg');

                try {
                    const response = await fetch('?url=perfil', {
                        method: 'POST',
                        body: formData
                    });
                    const res = await response.json();

                    if (res.status === 'success' && res.data && res.data.foto_url) {
                        if (perfilActual) perfilActual.foto = res.data.foto_url;
                        actualizarAvatarEnDOM(res.data.foto_url);
                        cerrarModalRecorte();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Foto Actualizada!',
                                text: 'Tu foto de perfil se ha ajustado y guardado exitosamente.',
                                confirmButtonColor: '#E83382',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al guardar foto',
                                text: res.message || 'No se pudo guardar la foto de perfil.',
                                confirmButtonColor: '#E83382'
                            });
                        }
                    }
                } catch (err) {
                    console.error('Error al subir foto recortada:', err);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'Ocurrió un error al procesar la solicitud.',
                            confirmButtonColor: '#E83382'
                        });
                    }
                } finally {
                    btnGuardarRecorte.disabled = false;
                    btnGuardarRecorte.innerHTML = '<i class="fas fa-check me-1"></i> Aceptar y Guardar Foto';
                }
            }, 'image/jpeg', 0.92);
        });
    }

    // Eliminar foto actual
    if (btnModalEliminar) {
        btnModalEliminar.addEventListener('click', async () => {
            const confirmar = typeof Swal !== 'undefined'
                ? await Swal.fire({
                    title: '¿Eliminar foto de perfil?',
                    text: 'Se restablecerá el avatar al ícono por defecto.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#E83382',
                    cancelButtonColor: '#374151'
                }).then(r => r.isConfirmed)
                : confirm('¿Eliminar tu foto de perfil?');

            if (!confirmar) return;

            try {
                const formData = new FormData();
                formData.append('eliminarFotoPerfil', '1');

                const response = await fetch('?url=perfil', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();

                if (res.status === 'success') {
                    if (perfilActual) perfilActual.foto = null;
                    actualizarAvatarEnDOM(null);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Foto eliminada',
                            text: 'Se ha restablecido el avatar por defecto.',
                            confirmButtonColor: '#E83382',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'No se pudo eliminar la foto de perfil.',
                            confirmButtonColor: '#E83382'
                        });
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    }
}

/**
 * Control del carrusel de certificados
 */
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
            if (!perfilActual || !perfilActual.certificados || perfilActual.certificados.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin certificados',
                        text: 'No tienes certificados disponibles para descargar en este momento.',
                        confirmButtonColor: '#E83382'
                    });
                }
                return;
            }

            // Descargar certificados con URL física válida
            const certsConPdf = perfilActual.certificados.filter(c => c.url_pdf && c.url_pdf !== '#' && c.url_pdf.trim() !== '');
            if (certsConPdf.length > 0) {
                certsConPdf.forEach(c => {
                    const a = document.createElement('a');
                    a.href = c.url_pdf;
                    a.target = '_blank';
                    a.download = '';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Certificados oficiales',
                        text: 'Tus certificados se encuentran registrados en el sistema académico.',
                        confirmButtonColor: '#E83382'
                    });
                }
            }
        });
    }
}

/**
 * Control del modal y guardado de edición de datos personales
 */
function configurarEdicionPerfil() {
    const btnAbrir = document.getElementById('btnAbrirEditarPerfil');
    const btnCerrar = document.getElementById('btnCerrarModalPerfil');
    const btnCancelar = document.getElementById('btnCancelarEditarPerfil');
    const modalEl = document.getElementById('modalEditarPerfil');
    const formEditar = document.getElementById('formEditarPerfil');

    const abrir = () => {
        if (modalEl) {
            modalEl.classList.add('show');
            // Cargar datos actuales en los campos
            if (perfilActual) {
                const inputPName = document.getElementById('inputPrimerNombre');
                const inputPLast = document.getElementById('inputPrimerApellido');
                const inputTel = document.getElementById('inputTelefono');
                const inputTelAlt = document.getElementById('inputTelefonoAlt');
                const inputIg = document.getElementById('inputInstagram');

                if (inputPName) inputPName.value = perfilActual.primer_nombre || '';
                if (inputPLast) inputPLast.value = perfilActual.primer_apellido || '';
                if (inputTel) inputTel.value = perfilActual.telefono || '';
                if (inputTelAlt) inputTelAlt.value = perfilActual.telefono_alternativo || '';
                if (inputIg) inputIg.value = (perfilActual.instagram || '').replace(/^@/, '');
            }
            const pass = document.getElementById('inputPassword');
            const passConfirm = document.getElementById('inputPasswordConfirm');
            if (pass) pass.value = '';
            if (passConfirm) passConfirm.value = '';
        }
    };

    const cerrar = () => {
        if (modalEl) modalEl.classList.remove('show');
    };

    if (btnAbrir) btnAbrir.addEventListener('click', abrir);
    if (btnCerrar) btnCerrar.addEventListener('click', cerrar);
    if (btnCancelar) btnCancelar.addEventListener('click', cerrar);

    // Cerrar al hacer clic en el fondo oscuro exterior
    if (modalEl) {
        modalEl.addEventListener('click', (e) => {
            if (e.target === modalEl) cerrar();
        });
    }

    // Cerrar con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modalRecorte = document.getElementById('modalRecortarFoto');
            if (modalRecorte && modalRecorte.classList.contains('show')) {
                if (window.cerrarModalRecorte) window.cerrarModalRecorte();
            } else if (modalEl && modalEl.classList.contains('show')) {
                cerrar();
            }
        }
    });

    // Enviar formulario de actualización de datos
    if (formEditar) {
        formEditar.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validación de contraseña
            const pass = document.getElementById('inputPassword')?.value || '';
            const passConfirm = document.getElementById('inputPasswordConfirm')?.value || '';

            if (pass !== '') {
                if (pass.length < 6) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Contraseña muy corta',
                            text: 'La nueva contraseña debe tener al menos 6 caracteres.',
                            confirmButtonColor: '#E83382'
                        });
                    } else {
                        alert('La nueva contraseña debe tener al menos 6 caracteres.');
                    }
                    document.getElementById('inputPassword')?.focus();
                    return;
                }

                if (pass !== passConfirm) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Las contraseñas no coinciden',
                            text: 'Por favor, confirma tu nueva contraseña reescribiéndola de forma idéntica en ambos campos.',
                            confirmButtonColor: '#E83382'
                        });
                    } else {
                        alert('Las contraseñas no coinciden. Por favor, asegúrate de escribir la misma contraseña en ambos campos.');
                    }
                    document.getElementById('inputPasswordConfirm')?.focus();
                    return;
                }
            }

            const btnGuardar = document.getElementById('btnGuardarPerfil');
            if (btnGuardar) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
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
                    cerrar();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Perfil Actualizado!',
                            text: res.message || 'Tus datos han sido actualizados correctamente.',
                            confirmButtonColor: '#E83382',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    // Actualizar el nombre en el navbar de inmediato
                    if (res.data && res.data.nombre) {
                        const navUserName = document.querySelector('.topbar-right .user-name');
                        if (navUserName) navUserName.textContent = res.data.nombre;
                    }

                    // Recargar datos actualizados
                    cargarDatosPerfil();
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'No se pudo actualizar',
                            text: res.message || 'Ocurrió un error al guardar los cambios.',
                            confirmButtonColor: '#E83382'
                        });
                    }
                }
            } catch (err) {
                console.error('Error al guardar perfil:', err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo comunicar con el servidor.',
                        confirmButtonColor: '#E83382'
                    });
                }
            } finally {
                if (btnGuardar) {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = 'Guardar Cambios';
                }
            }
        });
    }
}

/**
 * Escapa cadenas para prevenir inyecciones HTML en el frontend
 */
function escapeHtml(text) {
    if (!text) return '';
    return text
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
