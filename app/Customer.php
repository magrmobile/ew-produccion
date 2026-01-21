<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nit', 
        'nrc', 
        'nombre', 
        'codActividad', 
        'nombreComercial', 
        'tipoEstablecimiento', 
        'departamento',
        'municipio',
        'complemento',
        'codPais',
        'codDomiciliado',
        'codigoMH',
        'puntoVentaMH',
        'bienTitulo',
        'tipoPersona',
        'telefono',
        'correo',
        'category_id',
        'created_by',
        'nombre_contacto',
        'tipodoc_contacto',
        'numdoc_contacto'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
