<?php
// src/Customer.php

namespace posnet\src;

/**
 * Class Customer
 * Representa un cliente titular de una tarjeta de crédito.
 */
class Customer {
    
    /**
     * @var string Documento Nacional de Identidad (DNI) del cliente.
     */
    private string $dni;
    
    /**
     * @var string Primer nombre del cliente.
     */
    private string $firstName;
    
    /**
     * @var string Apellido del cliente.
     */
    private string $lastName;

    /**
     * Customer constructor.
     * 
     * @param string $dni DNI del cliente.
     * @param string $firstName Primer nombre del cliente.
     * @param string $lastName Apellido del cliente.
     */
    public function __construct(string $dni, string $firstName, string $lastName) {
        $this->dni = $dni;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Obtiene el nombre completo del cliente, combinando el primer nombre y el apellido.
     * 
     * @return string El nombre completo del cliente.
     */
    public function getFullName(): string {
        return "{$this->firstName} {$this->lastName}";
    }
}
