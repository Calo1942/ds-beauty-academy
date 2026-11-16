#!/usr/bin/env python3
"""
Generador de Recursos Multimedia de Prueba — DS Beauty Academy
Genera avatares de perfil, comprobantes bancarios realistas y evidencias de entregas prácticas.
"""

import os
from PIL import Image, ImageDraw, ImageFont

BASE_DIR = '/opt/lampp/htdocs/proyectos/ds-beauty-academy'
FONTS_DIR = os.path.join(BASE_DIR, 'assets/vendor/fonts')

FONT_BOLD_PATH = os.path.join(FONTS_DIR, 'Inter-Bold.ttf')
FONT_SEMIBOLD_PATH = os.path.join(FONTS_DIR, 'Inter-SemiBold.ttf')
FONT_REGULAR_PATH = os.path.join(FONTS_DIR, 'Inter-Regular.ttf')

def get_font(path, size):
    try:
        return ImageFont.truetype(path, size)
    except Exception:
        return ImageFont.load_default()

def create_avatar(filepath, name, role, bg_colors):
    """
    Crea un avatar elegante (400x400) con degradado o tonalidad suave,
    monograma con iniciales, anillo de diseño luxury y rol.
    """
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    width, height = 400, 400
    img = Image.new('RGB', (width, height), bg_colors[0])
    draw = ImageDraw.Draw(img)

    # Degradado vertical suave
    c1 = bg_colors[0]
    c2 = bg_colors[1]
    for y in range(height):
        r = int(c1[0] + (c2[0] - c1[0]) * (y / height))
        g = int(c1[1] + (c2[1] - c1[1]) * (y / height))
        b = int(c1[2] + (c2[2] - c1[2]) * (y / height))
        draw.line([(0, y), (width, y)], fill=(r, g, b))

    # Círculo central decorativo
    center_x, center_y = 200, 180
    radius = 120
    draw.ellipse(
        [center_x - radius, center_y - radius, center_x + radius, center_y + radius],
        fill=(24, 24, 28, 200),
        outline=(232, 51, 130), # var(--accent, #E83382)
        width=4
    )

    # Anillo exterior sutil
    outer_radius = 135
    draw.ellipse(
        [center_x - outer_radius, center_y - outer_radius, center_x + outer_radius, center_y + outer_radius],
        outline=(255, 255, 255, 30),
        width=1
    )

    # Iniciales
    parts = name.strip().split()
    initials = ""
    if len(parts) >= 2:
        initials = (parts[0][0] + parts[1][0]).upper()
    elif len(parts) == 1 and len(parts[0]) > 0:
        initials = parts[0][:2].upper()
    else:
        initials = "DS"

    font_initials = get_font(FONT_BOLD_PATH, 76)
    bbox_init = draw.textbbox((0, 0), initials, font=font_initials)
    text_w = bbox_init[2] - bbox_init[0]
    text_h = bbox_init[3] - bbox_init[1]
    draw.text((center_x - text_w // 2, center_y - text_h // 2 - 8), initials, font=font_initials, fill=(255, 255, 255))

    # Nombre y rol al pie
    font_name = get_font(FONT_SEMIBOLD_PATH, 20)
    font_role = get_font(FONT_REGULAR_PATH, 14)

    bbox_name = draw.textbbox((0, 0), name, font=font_name)
    nw = bbox_name[2] - bbox_name[0]
    draw.text((center_x - nw // 2, 330), name, font=font_name, fill=(245, 245, 247))

    bbox_role = draw.textbbox((0, 0), role, font=font_role)
    rw = bbox_role[2] - bbox_role[0]
    draw.text((center_x - rw // 2, 360), role, font=font_role, fill=(232, 51, 130))

    img.save(filepath, format='JPEG', quality=90)


def create_comprobante(filepath, banco_nombre, ref, monto, fecha, estatus, color_theme):
    """
    Crea una imagen de comprobante bancario realista (600x850).
    """
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    w, h = 600, 850
    img = Image.new('RGB', (w, h), (245, 247, 250))
    draw = ImageDraw.Draw(img)

    # Cabecera bancaria con el color temático del banco
    banner_h = 130
    draw.rectangle([(0, 0), (w, banner_h)], fill=color_theme)

    # Título Banco
    font_bank = get_font(FONT_BOLD_PATH, 26)
    font_sub = get_font(FONT_REGULAR_PATH, 14)
    draw.text((36, 36), banco_nombre.upper(), font=font_bank, fill=(255, 255, 255))
    draw.text((36, 75), "Comprobante Digital de Transferencia", font=font_sub, fill=(230, 235, 245))

    # Tarjeta de Estado
    is_rechazado = (estatus.lower() == 'rechazado')
    status_bg = (254, 226, 226) if is_rechazado else (220, 252, 231)
    status_fg = (185, 28, 28) if is_rechazado else (21, 128, 61)
    status_text = "TRANSFERENCIA RECHAZADA" if is_rechazado else "OPERACIÓN EXITOSA / VERIFICADA"

    card_y = 150
    draw.rounded_rectangle([(36, card_y), (w - 36, card_y + 60)], radius=12, fill=status_bg)
    font_status = get_font(FONT_BOLD_PATH, 16)
    sb_box = draw.textbbox((0, 0), status_text, font=font_status)
    draw.text((w // 2 - (sb_box[2] - sb_box[0]) // 2, card_y + 20), status_text, font=font_status, fill=status_fg)

    # Monto Central Destacado
    monto_y = 235
    font_lbl = get_font(FONT_REGULAR_PATH, 14)
    font_monto = get_font(FONT_BOLD_PATH, 42)

    lbl_text = "Monto de la Operación"
    draw.text((36, monto_y), lbl_text, font=font_lbl, fill=(100, 116, 139))
    draw.text((36, monto_y + 25), f"${monto:.2f} USD", font=font_monto, fill=(15, 23, 42))

    # Línea divisoria
    div_y = 330
    draw.line([(36, div_y), (w - 36, div_y)], fill=(226, 232, 240), width=2)

    # Detalles de la Transacción
    details = [
        ("Número de Referencia:", ref),
        ("Fecha y Hora:", fecha),
        ("Beneficiario:", "DS BEAUTY ACADEMY C.A."),
        ("RIF / Identificación:", "J-50129384-2"),
        ("Concepto / Motivo:", "Pago de Matrícula Académica"),
        ("Canal de Pago:", banco_nombre),
        ("Código de Aprobación:", f"AUT-{abs(hash(ref)) % 1000000:06d}")
    ]

    y_pos = 350
    font_d_lbl = get_font(FONT_REGULAR_PATH, 14)
    font_d_val = get_font(FONT_SEMIBOLD_PATH, 15)

    for label, val in details:
        draw.text((36, y_pos), label, font=font_d_lbl, fill=(100, 116, 139))
        draw.text((36, y_pos + 22), val, font=font_d_val, fill=(15, 23, 42))
        y_pos += 56

    # Pie de seguridad
    footer_y = h - 85
    draw.line([(36, footer_y), (w - 36, footer_y)], fill=(226, 232, 240), width=1)
    font_foot = get_font(FONT_REGULAR_PATH, 12)
    draw.text((36, footer_y + 16), "Este documento es un comprobante electrónico válido generado por el sistema.", font=font_foot, fill=(148, 163, 184))
    draw.text((36, footer_y + 36), "DS Beauty Academy — Departamento de Conciliación Financiera y Tesorería", font=font_foot, fill=(148, 163, 184))

    img.save(filepath, format='JPEG', quality=88)


def create_entrega(filepath, titulo, estudiante, curso, nota=None):
    """
    Crea una imagen de evidencia fotográfica de práctica (800x600).
    """
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    w, h = 800, 600
    img = Image.new('RGB', (w, h), (18, 18, 20)) # Dark Luxury base
    draw = ImageDraw.Draw(img)

    # Marco exterior luxury
    draw.rectangle([(20, 20), (w - 20, h - 20)], outline=(232, 51, 130), width=2)
    draw.rectangle([(30, 30), (w - 30, h - 30)], outline=(40, 40, 48), width=1)

    # Encabezado
    font_brand = get_font(FONT_BOLD_PATH, 20)
    font_sub = get_font(FONT_REGULAR_PATH, 14)
    draw.text((50, 45), "DS BEAUTY ACADEMY — EVIDENCIA DE EVALUACIÓN PRÁCTICA", font=font_brand, fill=(232, 51, 130))
    draw.text((50, 75), f"Curso: {curso}", font=font_sub, fill=(160, 160, 175))

    # Área de visualización de muestra práctica
    preview_box = [(50, 115), (w - 50, h - 140)]
    draw.rounded_rectangle(preview_box, radius=12, fill=(28, 28, 32), outline=(50, 50, 60), width=1)

    # Título de la práctica en el centro
    font_title = get_font(FONT_BOLD_PATH, 28)
    bbox_t = draw.textbbox((0, 0), titulo, font=font_title)
    draw.text((w // 2 - (bbox_t[2] - bbox_t[0]) // 2, 230), titulo, font=font_title, fill=(245, 245, 247))

    font_student = get_font(FONT_SEMIBOLD_PATH, 20)
    st_text = f"Alumna: {estudiante}"
    bbox_s = draw.textbbox((0, 0), st_text, font=font_student)
    draw.text((w // 2 - (bbox_s[2] - bbox_s[0]) // 2, 280), st_text, font=font_student, fill=(200, 200, 215))

    font_sub2 = get_font(FONT_REGULAR_PATH, 15)
    info_text = "Registro Fotográfico de Alta Resolución — Demostración Técnica de Acreditación"
    bbox_i = draw.textbbox((0, 0), info_text, font=font_sub2)
    draw.text((w // 2 - (bbox_i[2] - bbox_i[0]) // 2, 320), info_text, font=font_sub2, fill=(140, 140, 155))

    # Pie con calificación si aplica
    if nota:
        badge_text = f"Calificación Otorgada: {nota:.2f} / 100.00 pts"
        font_b = get_font(FONT_BOLD_PATH, 16)
        bbox_b = draw.textbbox((0, 0), badge_text, font=font_b)
        draw.text((50, h - 90), badge_text, font=font_b, fill=(74, 222, 128))
    else:
        badge_text = "Estatus: En Revisión por la Instructora Titular"
        font_b = get_font(FONT_BOLD_PATH, 16)
        draw.text((50, h - 90), badge_text, font=font_b, fill=(250, 204, 21))

    img.save(filepath, format='JPEG', quality=88)


def main():
    print("Iniciando generación de multimedia de prueba...")

    # 1. AVATARES DE USUARIOS (perfiles/)
    perfiles_dir = os.path.join(BASE_DIR, 'assets/uploads/perfiles')
    
    # Administrador 2
    create_avatar(
        os.path.join(perfiles_dir, 'perfil_coordinacion.jpg'),
        "Coordinación Académica", "Administración",
        [(30, 20, 50), (15, 12, 25)]
    )

    # Instructores 1 al 8
    instructores = [
        ("instructor_1_daniela.jpg", "Daniela Sánchez", "Instructora Maquillaje", [(60, 20, 45), (20, 10, 18)]),
        ("instructor_2_valeria.jpg", "Valeria Mendoza", "Lash Artist Pro", [(20, 40, 60), (10, 15, 25)]),
        ("instructor_3_mariana.jpg", "Mariana Gómez", "Nail Master Educator", [(50, 25, 40), (18, 12, 16)]),
        ("instructor_4_carlos.jpg", "Carlos Silva", "Barber Master Pro", [(30, 35, 45), (12, 15, 20)]),
        ("instructor_5_andreina.jpg", "Andreína Castillo", "Cosmiatra Clínica", [(25, 45, 35), (10, 20, 15)]),
        ("instructor_6_gabriela.jpg", "Gabriela Fuentes", "PhiBrows Royal Artist", [(55, 35, 20), (20, 15, 10)]),
        ("instructor_7_nelson.jpg", "Nelson Ramírez", "Colorista Capilar Master", [(45, 20, 55), (18, 10, 22)]),
        ("instructor_8_patricia.jpg", "Patricia Rojas", "Terapeuta Holística", [(35, 40, 30), (15, 18, 12)])
    ]
    for fn, name, rol, colors in instructores:
        create_avatar(os.path.join(perfiles_dir, fn), name, rol, colors)

    # Estudiantes (32 estudiantes)
    estudiantes_sample = [
        (1, "Camila Herrera"), (2, "Sofía Morales"), (3, "Mariana Peña"), (4, "Isabella Castro"),
        (5, "Valentina Ortiz"), (6, "Lucía Méndez"), (7, "Valeria Navarro"), (8, "María Rivas"),
        (9, "Andrea Torres"), (10, "Natalia Salazar"), (11, "Oriana Romero"), (12, "Stephany Márquez"),
        (13, "Daniela Quintero"), (14, "Paola Benítez"), (15, "Génesis Blanco"), (16, "Adriana Suárez"),
        (17, "Claudia Altuve"), (18, "Michelle Chacón"), (19, "Fabiana Villegas"), (20, "Yoselin Guanipa"),
        (21, "Vanessa León"), (22, "Kimberly Montilla"), (23, "Carmen Briceño"), (24, "Laura Maldonado"),
        (25, "Anabella Carrizo"), (26, "Victoria Palacios"), (27, "Gabriel Pineda"), (28, "Kevin Colmenarez"),
        (29, "José Morales"), (30, "Anthony Mendoza"), (31, "Bárbara Aguilar"), (32, "Nayelis Terán")
    ]
    palette = [
        [(40, 25, 55), (15, 10, 25)],
        [(25, 40, 55), (10, 18, 25)],
        [(55, 30, 35), (25, 12, 15)],
        [(35, 45, 30), (12, 20, 12)],
        [(45, 35, 25), (20, 15, 10)]
    ]
    for idx, name in estudiantes_sample:
        fn = f"estudiante_{idx}_{name.split()[0].lower()}.jpg"
        colors = palette[idx % len(palette)]
        create_avatar(os.path.join(perfiles_dir, fn), name, "Estudiante", colors)

    print("Avatares generados exitosamente.")

    # 2. COMPROBANTES DE PAGO (comprobantes/)
    comp_dir = os.path.join(BASE_DIR, 'assets/uploads/comprobantes')

    bancos_colors = {
        'BDV': (220, 38, 38),      # Rojo Banco de Venezuela
        'BANESCO': (16, 149, 105), # Verde Banesco
        'PAGOMOVIL': (124, 58, 237),# Morado Pago Móvil
        'MERCANTIL': (2, 132, 199), # Azul Mercantil
        'ZELLE': (116, 39, 219),    # Púrpura Zelle
        'BANCAMIGA': (13, 148, 136),# Turquesa Bancamiga
        'BINANCE': (202, 138, 4),   # Amarillo/Oro Binance
        'BNC': (30, 41, 59)         # Azul Oscuro BNC
    }

    comprobantes_data = [
        ("pago_ref_8947291.jpg", "Banco de Venezuela", "REF-BDV-8947291", 140.00, "15/08/2026 10:30", "Verificado", bancos_colors['BDV']),
        ("pago_ref_3847192.jpg", "Banesco Banco Universal", "REF-BAN-3847192", 140.00, "16/08/2026 12:00", "Verificado", bancos_colors['BANESCO']),
        ("pago_ref_9102834.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-9102834", 180.00, "02/09/2026 09:45", "Verificado", bancos_colors['PAGOMOVIL']),
        ("pago_ref_4920192.jpg", "Banco Mercantil", "REF-MER-4920192", 180.00, "03/09/2026 15:00", "Verificado", bancos_colors['MERCANTIL']),
        ("pago_ref_819203.jpg", "Zelle Transfer", "ZELLE-CONF-819203", 180.00, "05/09/2026 17:10", "Verificado", bancos_colors['ZELLE']),
        ("pago_ref_1029384.jpg", "Bancamiga Universal", "REF-BAM-1029384", 140.00, "25/08/2026 12:30", "Verificado", bancos_colors['BANCAMIGA']),
        ("pago_ref_7748291.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-7748291", 180.00, "11/09/2026 15:30", "Pendiente", bancos_colors['PAGOMOVIL']),
        ("pago_ref_6619284.jpg", "Banco de Venezuela", "REF-BDV-6619284", 180.00, "12/09/2026 11:30", "Pendiente", bancos_colors['BDV']),
        ("pago_ref_error01.jpg", "Banesco Banco Universal", "REF-BAN-ERROR-01", 50.00, "10/09/2026 09:00", "Rechazado", bancos_colors['BANESCO']),
        ("pago_ref_3329104.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-3329104", 60.00, "15/09/2026 09:30", "Verificado", bancos_colors['PAGOMOVIL']),
        ("pago_ref_5548291.jpg", "Banco de Venezuela", "REF-BDV-5548291", 60.00, "16/09/2026 11:00", "Verificado", bancos_colors['BDV']),
        ("pago_ref_920194.jpg", "Zelle Transfer", "ZELLE-CONF-920194", 85.00, "06/10/2026 14:30", "Verificado", bancos_colors['ZELLE']),
        ("pago_ref_88391.jpg", "Binance Pay USDT", "BINANCE-TXID-88391", 85.00, "07/10/2026 16:50", "Verificado", bancos_colors['BINANCE']),
        ("pago_ref_2948192.jpg", "Banesco Banco Universal", "REF-BAN-2948192", 85.00, "08/10/2026 11:30", "Pendiente", bancos_colors['BANESCO']),
        ("pago_ref_8849102.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-8849102", 85.00, "09/10/2026 14:00", "Pendiente", bancos_colors['PAGOMOVIL']),
        ("pago_ref_1102934.jpg", "Banco de Venezuela", "REF-BDV-1102934", 95.00, "28/08/2026 10:00", "Verificado", bancos_colors['BDV']),
        ("pago_ref_4492019.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-4492019", 95.00, "29/08/2026 16:00", "Verificado", bancos_colors['PAGOMOVIL']),
        ("pago_ref_7738291.jpg", "Banesco Banco Universal", "REF-BAN-7738291", 120.00, "05/09/2026 10:30", "Verificado", bancos_colors['BANESCO']),
        ("pago_ref_5592810.jpg", "Banco Nacional de Crédito", "REF-BNC-5592810", 120.00, "06/09/2026 15:00", "Verificado", bancos_colors['BNC']),
        ("pago_ref_9948271.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-9948271", 120.00, "07/09/2026 11:45", "Pendiente", bancos_colors['PAGOMOVIL']),
        ("pago_ref_334910.jpg", "Zelle Transfer", "ZELLE-CONF-334910", 160.00, "03/10/2026 10:30", "Verificado", bancos_colors['ZELLE']),
        ("pago_ref_77291.jpg", "Binance Pay USDT", "BINANCE-TXID-77291", 160.00, "04/10/2026 12:15", "Verificado", bancos_colors['BINANCE']),
        ("pago_ref_1182940.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-1182940", 160.00, "05/10/2026 15:00", "Pendiente", bancos_colors['PAGOMOVIL']),
        ("pago_ref_9938192.jpg", "Banco de Venezuela", "REF-BDV-9938192", 160.00, "06/10/2026 16:30", "Pendiente", bancos_colors['BDV']),
        ("pago_ref_6629103.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-6629103", 50.00, "07/10/2026 09:30", "Verificado", bancos_colors['PAGOMOVIL']),
        ("pago_ref_8849201.jpg", "Banesco Banco Universal", "REF-BAN-8849201", 50.00, "08/10/2026 11:00", "Verificado", bancos_colors['BANESCO']),
        ("pago_ref_2293841.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-2293841", 50.00, "09/10/2026 14:30", "Pendiente", bancos_colors['PAGOMOVIL']),
        ("pago_ref_662910.jpg", "Zelle Transfer", "ZELLE-CONF-662910", 50.00, "10/10/2026 16:15", "Verificado", bancos_colors['ZELLE']),
        # Históricos
        ("hist_001.jpg", "Banco de Venezuela", "REF-BDV-HIST-001", 120.00, "25/01/2026 09:30", "Verificado", bancos_colors['BDV']),
        ("hist_002.jpg", "Banesco Banco Universal", "REF-BAN-HIST-002", 120.00, "26/01/2026 11:00", "Verificado", bancos_colors['BANESCO']),
        ("hist_003.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-HIST-003", 150.00, "02/02/2026 11:45", "Verificado", bancos_colors['PAGOMOVIL']),
        ("hist_004.jpg", "Banco Mercantil", "REF-MER-HIST-004", 120.00, "28/01/2026 14:30", "Verificado", bancos_colors['MERCANTIL']),
        ("hist_005.jpg", "Bancamiga Universal", "REF-BAM-HIST-005", 150.00, "03/02/2026 16:00", "Verificado", bancos_colors['BANCAMIGA']),
        ("hist_006.jpg", "Zelle Transfer", "ZELLE-HIST-006", 150.00, "04/02/2026 17:15", "Verificado", bancos_colors['ZELLE']),
        ("hist_007.jpg", "Pago Móvil Interbancario", "PAGOMOVIL-HIST-007", 80.00, "20/02/2026 10:30", "Verificado", bancos_colors['PAGOMOVIL']),
        ("hist_008.jpg", "Banco de Venezuela", "REF-BDV-HIST-008", 80.00, "21/02/2026 12:00", "Verificado", bancos_colors['BDV']),
        ("hist_009.jpg", "Banesco Banco Universal", "REF-BAN-HIST-009", 110.00, "02/03/2026 14:30", "Verificado", bancos_colors['BANESCO']),
        ("hist_010.jpg", "Banco Nacional de Crédito", "REF-BNC-HIST-010", 110.00, "03/03/2026 16:45", "Verificado", bancos_colors['BNC'])
    ]

    for fn, banco, ref, monto, fecha, estatus, color in comprobantes_data:
        create_comprobante(os.path.join(comp_dir, fn), banco, ref, monto, fecha, estatus, color)

    print("Comprobantes bancarios generados exitosamente.")

    # 3. ENTREGAS DE EVALUACIONES (entregas/)
    entregas_dir = os.path.join(BASE_DIR, 'assets/uploads/entregas')
    entregas_sample = [
        ("abanicos_oriana.jpg", "Práctica de Abanicos Volumen Ruso 4D", "Oriana Romero", "Masterclass Pestañas", 92.0),
        ("cuticula_michelle.jpg", "Manicura Rusa y Nivelación con Base Rubber", "Michelle Chacón", "Manicura Rusa y Polygel", 94.0),
        ("cuticula_fabiana.jpg", "Limpieza de Bolsillo y Cutícula Combinada", "Fabiana Villegas", "Manicura Rusa y Polygel", None)
    ]
    for fn, titulo, est, curso, nota in entregas_sample:
        create_entrega(os.path.join(entregas_dir, fn), titulo, est, curso, nota)

    print("Evidencias de entregas generadas exitosamente.")

    # 4. DOCUMENTOS PDF DE PRUEBA (diplomas/, recursos/, certificados/, entregas/)
    def make_pdf(filepath, title, subtitle="Documento Académico Oficial"):
        os.makedirs(os.path.dirname(filepath), exist_ok=True)
        # Sanitizar título para PDF básico
        clean_title = title.replace('(', '').replace(')', '').replace('\\', '')
        stream_content = f"""BT
/F1 18 Tf
50 730 Td
(DS BEAUTY ACADEMY) Tj
0 -26 Td
/F1 12 Tf
({clean_title}) Tj
0 -18 Td
/F1 10 Tf
({subtitle}) Tj
0 -30 Td
/F1 9 Tf
(Este documento digital ha sido generado como material academico de prueba.) Tj
ET"""
        stream_bytes = stream_content.encode('utf-8')
        pdf_content = f"""%PDF-1.4
1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj
2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj
3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj
4 0 obj << /Length {len(stream_bytes)} >> stream
{stream_content}
endstream endobj
5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj
xref
0 6
0000000000 65535 f 
0000000010 00000 n 
0000000060 00000 n 
0000000117 00000 n 
0000000236 00000 n 
0000000420 00000 n 
trailer << /Size 6 /Root 1 0 R >>
startxref
500
%%EOF"""
        with open(filepath, 'wb') as f:
            f.write(pdf_content.encode('utf-8'))

    # Diplomas
    diplomas_dir = os.path.join(BASE_DIR, 'assets/uploads/diplomas')
    diplomas_files = [
        "diploma_daniela_kryolan.pdf", "diploma_daniela_visagismo.pdf", "diploma_valeria_philashes.pdf",
        "diploma_valeria_elleebana.pdf", "diploma_mariana_kinetics.pdf", "diploma_mariana_organic.pdf",
        "diploma_carlos_barber.pdf", "diploma_andreina_cosmiatria.pdf", "diploma_andreina_peelings.pdf",
        "diploma_gabriela_phibrows.pdf", "diploma_nelson_schwarzkopf.pdf", "diploma_patricia_maderoterapia.pdf"
    ]
    for df in diplomas_files:
        make_pdf(os.path.join(diplomas_dir, df), f"Diploma de Instructor - {df}", "Acreditacion Profesional")

    # Recursos
    recursos_dir = os.path.join(BASE_DIR, 'assets/uploads/recursos')
    recursos_files = [
        "manual_colorimetria_guia.pdf", "plantilla_facecharts_ds.pdf", "protocolo_bioseguridad_2026.pdf",
        "guia_mapeos_lashes.pdf", "consentimiento_informado_pestanas.pdf", "catalogo_fresas_manicura.pdf",
        "guia_preparadores_quimicos.pdf", "formulas_pigmentos_cejas.pdf", "ejercicios_trazos_espinas.pdf",
        "rueda_fondos_aclaracion.pdf", "ficha_evaluacion_facial.pdf", "guia_peelings_esteticos.pdf",
        "poster_angulos_corte.pdf", "mantenimiento_maquinas_barber.pdf"
    ]
    for rf in recursos_files:
        make_pdf(os.path.join(recursos_dir, rf), f"Recurso Didactico - {rf}", "Material de Apoyo Academico")

    # Certificados
    cert_dir = os.path.join(BASE_DIR, 'assets/uploads/certificados')
    cert_files = [
        "cert_cosmetologia_camila_herrera.pdf", "cert_cosmetologia_sofia_morales.pdf",
        "cert_cosmetologia_isabella_castro.pdf", "cert_cosmetologia_natalia_salazar.pdf",
        "cert_cosmetologia_oriana_romero.pdf", "cert_barberia_gabriel_pineda.pdf",
        "cert_barberia_kevin_colmenarez.pdf", "cert_barberia_jose_morales.pdf"
    ]
    for cf in cert_files:
        make_pdf(os.path.join(cert_dir, cf), f"Certificado de Aprobacion - {cf}", "Acreditacion Oficial DS Beauty Academy")

    # Entregas (PDFs)
    entregas_pdf_files = [
        "entrega_camila_eval1.pdf", "entrega_sofia_eval1.pdf", "entrega_mariana_eval1.pdf",
        "entrega_isabella_eval1.pdf", "entrega_valentina_eval1.pdf", "entrega_natalia_eval1.pdf",
        "quiz_camila.pdf", "quiz_sofia.pdf",
        "hist_camila_eval9.pdf", "hist_camila_eval10.pdf", "hist_camila_eval11.pdf",
        "hist_sofia_eval9.pdf", "hist_sofia_eval10.pdf", "hist_sofia_eval11.pdf",
        "hist_isabella_eval9.pdf", "hist_isabella_eval10.pdf", "hist_isabella_eval11.pdf",
        "hist_natalia_eval9.pdf", "hist_natalia_eval10.pdf", "hist_natalia_eval11.pdf",
        "hist_oriana_eval9.pdf", "hist_oriana_eval10.pdf", "hist_oriana_eval11.pdf",
        "hist_paola_eval9.pdf", "hist_paola_eval10.pdf", "hist_paola_eval11.pdf",
        "barber_gabriel_eval12.pdf", "barber_gabriel_eval13.pdf", "barber_kevin_eval12.pdf",
        "barber_kevin_eval13.pdf", "barber_jose_eval12.pdf", "barber_jose_eval13.pdf",
        "barber_anthony_eval12.pdf", "barber_anthony_eval13.pdf"
    ]
    for epf in entregas_pdf_files:
        make_pdf(os.path.join(entregas_dir, epf), f"Evaluacion Entregada - {epf}", "Evidencia Academica Evaluada")

    print("Documentos PDF generados exitosamente.")
    print("¡Generación completada al 100%!")

if __name__ == '__main__':
    main()

