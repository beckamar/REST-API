<?php

namespace App\Http\Traits;

use illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Relations\HasMany;
use illuminate\Database\Query\Builder as QueryBuilder;
use illuminate\Database\Eloquent\Builder as EloquentBuilder;



trait CanLoadRelationships
{

     /**
     * Carga relaciones en un modelo o una consulta Eloquent/QueryBuilder según los parámetros dados.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $for
     * @param  array|null $relations
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */

    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
      ): Model|QueryBuilder|EloquentBuilder|HasMany {
        $relations = $relations ?? $this->relations ?? [];
    
        foreach ($relations as $relation) {
          $for->when(
            $this->shouldIncludeRelation($relation),
            fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
          );
        }
        return $for;
    }

    
/**
 * Determina si una relación específica debe ser incluida en la respuesta.
 *
 * @param  string $relation Nombre de la relación a verificar.
 * @return bool Retorna verdadero si la relación debe ser incluida, de lo contrario, falso.
 */
protected function shouldIncludeRelation(string $relation): bool {
    // Obtiene los parámetros de consulta 'include' de la solicitud
    $include = request()->query('include');
// Si no se proporciona ningún parámetro 'include', se asume que la relación no debe incluirse
    if(!$include){
        return false;
    }
     // Divide y limpia los parámetros 'include' para comparación
    $relations = array_map('trim', explode(',', $include));
    // Verifica si la relación específica está presente en los parámetros 'include'
    return in_array($relation, $relations);
}

}