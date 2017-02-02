<?php

namespace XmlUploader\Constants;

class ErrorMessages
{

    const ERROR_PARSE = 'El archivo no contiene un formato de XML';
    const ERROR_MISSING_REQUIRED_NODES = 'El Xml debe contener todos los nodos requeridos';
    const ERROR_SIZE = 'El archivo Excede la el tamaño';
    const ERROR_EXTENSION = 'El archivo debe tener una extension xml';
    const ERROR_OPTION_FILENAME = 'El nombre de variable es erroneo';
    const ERROR_QUANTITY = 'La cantidad de archivos excede el permitido';
}