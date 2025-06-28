<?php

namespace App\interfaces;

interface ProcesadorPagoInterface
{
  public function procesarPago(float $monto): string;
  public function confirmarPago(string $id): bool;
  public function verificarEstado(string $id): string;
  public function cancelarPago(string $id): bool;
}
