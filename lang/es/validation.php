<?php

return [
    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    'after'                => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo :attribute solo puede contener letras.',
    'alpha_dash'           => 'El campo :attribute solo puede contener letras, números y guiones.',
    'alpha_num'            => 'El campo :attribute solo puede contener letras y números.',
    'array'                => 'El campo :attribute debe ser un arreglo.',
    'before'               => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'array'   => 'El campo :attribute debe tener entre :min y :max elementos.',
        'file'    => 'El campo :attribute debe tener entre :min y :max kilobytes.',
        'numeric' => 'El campo :attribute debe tener entre :min y :max.',
        'string'  => 'El campo :attribute debe tener entre :min y :max caracteres.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => 'El campo :attribute de confirmación no coincide.',
    'current_password'     => 'La contraseña actual es incorrecta.',
    'date'                 => 'El campo :attribute no es una fecha válida.',
    'date_equals'          => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format'          => 'El campo :attribute no coincide con el formato :format.',
    'declined'             => 'El campo :attribute debe ser rechazado.',
    'different'            => 'El campo :attribute y :other deben ser diferentes.',
    'digits'               => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between'       => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'email'                => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with'            => 'El campo :attribute debe terminar con uno de los siguientes: :values.',
    'enum'                 => 'El valor seleccionado para :attribute es inválido.',
    'exists'               => 'El campo :attribute seleccionado no es válido.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute debe tener un valor.',
    'gt'                   => [
        'array'   => 'El campo :attribute debe tener más de :value elementos.',
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file'    => 'El campo :attribute debe tener más de :value kilobytes.',
        'string'  => 'El campo :attribute debe tener más de :value caracteres.',
    ],
    'gte'                  => [
        'array'   => 'El campo :attribute debe tener :value elementos o más.',
        'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
        'file'    => 'El campo :attribute debe tener :value kilobytes o más.',
        'string'  => 'El campo :attribute debe tener :value caracteres o más.',
    ],
    'image'                => 'El campo :attribute debe ser una imagen.',
    'in'                   => 'El campo :attribute seleccionado no es válido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El campo :attribute debe ser un número entero.',
    'ip'                   => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo :attribute debe ser una cadena JSON válida.',
    'lt'                   => [
        'array'   => 'El campo :attribute debe tener menos de :value elementos.',
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file'    => 'El campo :attribute debe tener menos de :value kilobytes.',
        'string'  => 'El campo :attribute debe tener menos de :value caracteres.',
    ],
    'lte'                  => [
        'array'   => 'El campo :attribute no debe tener más de :value elementos.',
        'numeric' => 'El campo :attribute debe ser menor o igual que :value.',
        'file'    => 'El campo :attribute no debe tener más de :value kilobytes.',
        'string'  => 'El campo :attribute no debe tener más de :value caracteres.',
    ],
    'max'                  => [
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
        'file'    => 'El campo :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string'  => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
        'file'    => 'El campo :attribute debe tener al menos :min kilobytes.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'multiple_of'          => 'El campo :attribute debe ser un múltiplo de :value.',
    'not_in'               => 'El campo :attribute seleccionado no es válido.',
    'not_regex'            => 'El formato del campo :attribute no es válido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'password'             => [
        'letters'          => 'La contraseña debe contener al menos una letra.',
        'mixed'           => 'La contraseña debe contener letras mayúsculas y minúsculas.',
        'numbers'         => 'La contraseña debe contener al menos un número.',
        'symbols'         => 'La contraseña debe contener al menos un símbolo.',
        'uncompromised'   => 'La contraseña ha aparecido en una filtración de datos. Por favor, elija una contraseña diferente.',
    ],
    'present'              => 'El campo :attribute debe estar presente.',
    'prohibited'           => 'El campo :attribute está prohibido.',
    'prohibited_if'        => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless'    => 'El campo :attribute está prohibido a menos que :other esté en :values.',
    'prohibits'            => 'El campo :attribute prohíbe que :other esté presente.',
    'regex'                => 'El formato del campo :attribute no es válido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_array_keys'  => 'El campo :attribute debe contener las claves: :values.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El campo :attribute es obligatorio cuando :other es aceptado.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values están presentes.',
    'same'                 => 'El campo :attribute y :other deben coincidir.',
    'size'                 => [
        'array'   => 'El campo :attribute debe tener :size elementos.',
        'file'    => 'El campo :attribute debe tener :size kilobytes.',
        'numeric' => 'El campo :attribute debe ser :size.',
        'string'  => 'El campo :attribute debe tener :size caracteres.',
    ],
    'starts_with'          => 'El campo :attribute debe comenzar con uno de los siguientes: :values.',
    'string'               => 'El campo :attribute debe ser una cadena de texto.',
    'timezone'             => 'El campo :attribute debe ser una zona horaria válida.',
    'unique'               => 'El campo :attribute ya ha sido registrado.',
    'uploaded'             => 'El campo :attribute no se pudo subir.',
    'url'                  => 'El campo :attribute debe ser una URL válida.',
    'uuid'                 => 'El campo :attribute debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'mensaje-personalizado',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */
    'attributes' => [],
];
