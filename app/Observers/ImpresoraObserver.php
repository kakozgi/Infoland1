<?php

namespace App\Observers;

use App\Models\Impresora;
use App\Models\HistorialContador;

class ImpresoraObserver
{
    /**
     * Handle the Impresora "created" event.
     */
    public function created(Impresora $impresora): void
    {
        //
    }

    /**
     * Handle the Impresora "updated" event.
     */
    public function updated(Impresora $impresora)
    {
        // Verificar si el contador ha cambiado
        if ($impresora->isDirty('contador_actual')) {
            // Registrar un nuevo historial de contador
            HistorialContador::create([
                'impresora_id' => $impresora->id,
                'contador' => $impresora->contador_actual,
                'fecha_registro' => now(),
            ]);
        }
    }

    /**
     * Handle the Impresora "deleted" event.
     */
    public function deleted(Impresora $impresora): void
    {
        //
    }

    /**
     * Handle the Impresora "restored" event.
     */
    public function restored(Impresora $impresora): void
    {
        //
    }

    /**
     * Handle the Impresora "force deleted" event.
     */
    public function forceDeleted(Impresora $impresora): void
    {
        //
    }
}
