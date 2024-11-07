<?php
// src/AbstractCard.php

namespace posnet\src;

use posnet\src\Customer;

/**
 * Class AbstractCard
 * Representa una tarjeta de crédito abstracta que sirve como base para implementaciones específicas de tarjetas.
 * 
 * Las subclases deben implementar el método `validateCard` para validar los requisitos específicos de cada tipo de tarjeta.
 */
abstract class AbstractCard {
    
    /**
     * @var string Número de la tarjeta de crédito.
     */
    protected string $number;
    
    /**
     * @var float Límite disponible en la tarjeta de crédito.
     */
    protected float $limit;
    
    /**
     * @var string Nombre del banco emisor de la tarjeta.
     */
    protected string $bankName;
    
    /**
     * @var Customer Cliente titular de la tarjeta de crédito.
     */
    protected Customer $customer;

    /**
     * AbstractCard constructor.
     * 
     * @param string $number Número de la tarjeta de crédito.
     * @param float $limit Límite de crédito de la tarjeta.
     * @param string $bankName Nombre del banco emisor.
     * @param Customer $customer Instancia del cliente titular de la tarjeta.
     */
    public function __construct(string $number, float $limit, string $bankName, Customer $customer) {
        $this->number = $number;
        $this->limit = $limit;
        $this->bankName = $bankName;
        $this->customer = $customer;
        $this->validateCard();
    }

    /**
     * Método abstracto para validar el número de la tarjeta.
     * 
     * Este método debe ser implementado por subclases para asegurar que la tarjeta cumple con los requisitos específicos.
     */
    abstract protected function validateCard(): void;

    /**
     * Obtiene el número de la tarjeta de crédito.
     * 
     * @return string El número de la tarjeta.
     */
    public function getNumber(): string {
        return $this->number;
    }

    /**
     * Obtiene el límite de crédito disponible en la tarjeta.
     * 
     * @return float El límite disponible.
     */
    public function getLimit(): float {
        return $this->limit;
    }

    /**
     * Reduce el límite de la tarjeta después de una transacción.
     * 
     * @param float $amount Cantidad a reducir del límite disponible.
     */
    public function reduceLimit(float $amount): void {
        $this->limit -= $amount;
    }

    /**
     * Obtiene el nombre completo del titular de la tarjeta.
     * 
     * @return string El nombre completo del cliente.
     */
    public function getCustomerName(): string {
        return $this->customer->getFullName();
    }
}
