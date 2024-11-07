<?php
// src/Posnet.php

namespace posnet\src;

use Exception;

/**
 * Class Posnet
 * Representa un sistema de punto de venta (POSNET) que permite registrar tarjetas y procesar pagos en cuotas.
 */
class Posnet {
    
    /**
     * @var array Lista de tarjetas registradas, almacenadas por número de tarjeta.
     */
    private array $registeredCards = [];

    /**
     * Registra una tarjeta en el sistema POSNET.
     * 
     * Este método almacena la tarjeta en el sistema para permitir su uso en pagos futuros.
     *
     * @param AbstractCard $card La tarjeta a registrar.
     */
    public function registerCard(AbstractCard $card): void {
        $this->registeredCards[$card->getNumber()] = $card;
    }
    
    /**
     * Procesa un pago usando una tarjeta registrada.
     * 
     * Este método verifica que la tarjeta esté registrada y tenga suficiente límite. Luego, aplica el recargo
     * correspondiente si el pago se realiza en más de una cuota y genera un ticket con los detalles de la transacción.
     *
     * @param string $cardNumber Número de la tarjeta utilizada para el pago.
     * @param float $amount Monto total a abonar.
     * @param int $installments Número de cuotas (1-6) en las que se divide el pago.
     * 
     * @return array Datos del ticket que incluyen el nombre del cliente, el monto total y el monto de cada cuota.
     * 
     * @throws Exception Si la tarjeta no está registrada o si el límite de la tarjeta es insuficiente.
     */
    public function doPayment(string $cardNumber, float $amount, int $installments = 1): array {
        if (!isset($this->registeredCards[$cardNumber])) {
            throw new Exception("Card not registered or invalid.");
        }
    
        $card = $this->registeredCards[$cardNumber];
        $totalAmountInCents = (int)($amount * 100); // Convertimos a centavos para evitar decimales
    
        // Aplicar recargo iterativamente por cada cuota adicional
        for ($i = 2; $i <= $installments; $i++) {
            $totalAmountInCents += (int)($amount * 0.03 * 100); // Añadimos 3% en cada cuota extra
        }
    
        $totalAmount = $totalAmountInCents / 100; // Volvemos a convertir a unidades
    
        if ($card->getLimit() < $totalAmount) {
            throw new Exception("Insufficient card limit for this payment.");
        }
    
        $card->reduceLimit($totalAmount);
    
        return [
            'customer_name' => $card->getCustomerName(),
            'total_amount' => $totalAmount,
            'installment_amount' => round($totalAmount / $installments, 2)
        ];
    }
}