<?php

    namespace DsBeautyAcademy\config\interfaces;

    interface Crud {
        public function guardar($data);
        public function buscarTodos();
        public function buscar($id);
        public function actualizar($id, $data);
        public function eliminar($id);
    }
