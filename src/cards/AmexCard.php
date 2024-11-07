<?php
// src/cards/AmexCard.php

namespace posnet\src\cards;

use posnet\src\AbstractCard;
use InvalidArgumentException;

/**
 * Class AmexCard
 * Representa una tarjeta American Express específica con validaciones particulares.
 */
class AmexCard extends AbstractCard {
    
    /**
     * Valida el número de tarjeta.
     * 
     * Este método se asegura de que el número de tarjeta cumple con los requisitos de una tarjeta AMEX.
     *
     * @throws InvalidArgumentException Si el número de la tarjeta no tiene exactamente 8 dígitos.
     */
    protected function validateCard(): void {
        if (strlen($this->number) !== 8) {
            throw new InvalidArgumentException("Invalid AMEX card number. It must be 8 digits.");
        }
    }
}
