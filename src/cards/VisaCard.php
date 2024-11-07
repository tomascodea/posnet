<?php
// src/cards/VisaCard.php

namespace posnet\src\cards;

use posnet\src\AbstractCard;
use InvalidArgumentException;

/**
 * Class VisaCard
 * Representa una tarjeta Visa específica con validaciones particulares.
 */
class VisaCard extends AbstractCard {
    
    /**
     * Valida el número de tarjeta.
     * 
     * Este método asegura que el número de la tarjeta cumple con los requisitos de longitud específicos de Visa.
     *
     * @throws InvalidArgumentException Si el número de la tarjeta no tiene exactamente 8 dígitos.
     */
    protected function validateCard(): void {
        if (strlen($this->number) !== 8) {
            throw new InvalidArgumentException("Invalid Visa card number. It must be 8 digits.");
        }
    }
}
