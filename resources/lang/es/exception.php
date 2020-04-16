<?php

return [

  /**
   * --------------------------------------------------------------------------
   * Exception Language Lines
   * --------------------------------------------------------------------------
   * Las siguientes lineas de idioma son usadas por la librerío ApiResponser.
   */

  // Excepciones del archivo App\Http\Controllers\ApiResponser;
  'model'          => 'No existe una instancia de :attribute con el id especificado',
  'authentication' => 'Requiere estar autenticado',
  'authorization'  => 'No posee permisos para ejecutar esta acción',
  'query'          => 'Existe un error en la interacción con la base de datos',
  'interal_error'  => 'Ha ocurrido un error, comuníquese con su proveedor',
  'not_found_data' => 'No hay datos',
  'validation'     => 'Error al validar',
  'attribute'      => 'Atributo no encontado',

  // Excepciones del archivo App\Exceptions\Handler;
  'notfound'         => 'No se encontró la ruta solicitada',
  'methodnotallowed' => 'El método solicitado no es válido',
  
  // Excepciones del App/Http/Middleware/CustomThrottleRequests;
  'too_many_attemps' => 'Su solicitud no puede ser respondida, el servidor llegó a límite de peticiones permitidas',

];