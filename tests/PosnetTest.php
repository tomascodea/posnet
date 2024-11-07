<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use posnet\src\Posnet;
use posnet\src\Customer;
use posnet\src\cards\VisaCard;
use ReflectionProperty;

/**
 * Class PosnetTest
 * Pruebas unitarias para la clase Posnet, que incluyen registro de tarjetas y procesamiento de pagos en cuotas.
 */
class PosnetTest extends TestCase {

    /**
     * @var Posnet Sistema POSNET usado para procesar pagos y registrar tarjetas.
     */
    private Posnet $posnet;

    /**
     * Configuración inicial para cada test.
     * 
     * Este método inicializa una instancia de Posnet antes de cada test.
     */
    protected function setUp(): void {
        $this->posnet = new Posnet();
    }

    /**
     * Prueba el registro exitoso de una tarjeta Visa.
     * 
     * Verifica que la tarjeta registrada se almacene correctamente en el arreglo interno de tarjetas.
     */
    public function testRegisterVisaCardSuccessfully(): void {
        $customer = new Customer("41575190", "Tomás", "Vazquez");
        $visaCard = new VisaCard("48585333", 1000.0, "Bank", $customer);

        $this->posnet->registerCard($visaCard);

        // Verifica si la tarjeta está registrada
        $registeredCards = new ReflectionProperty($this->posnet, 'registeredCards');
        $registeredCards->setAccessible(true);

        $this->assertArrayHasKey($visaCard->getNumber(), $registeredCards->getValue($this->posnet));
    }

    /**
     * Prueba el pago con un límite de crédito suficiente.
     * 
     * Verifica que se procese correctamente el pago y que el ticket incluya el nombre del cliente, el monto total y el monto por cuota.
     */
    public function testDoPaymentWithSufficientLimit(): void {
        $customer = new Customer("41575190", "Tomás", "Vazquez");
        $visaCard = new VisaCard("12345678", 1000.0, "Bank", $customer);
        $this->posnet->registerCard($visaCard);

        $ticket = $this->posnet->doPayment("12345678", 100, 1);

        $this->assertEquals("Tomás Vazquez", $ticket['customer_name']);
        $this->assertEquals(100, $ticket['total_amount']);
        $this->assertEquals(100, $ticket['installment_amount']);
    }

    /**
     * Prueba que se lance una excepción cuando el límite de crédito es insuficiente.
     * 
     * Verifica que, al intentar procesar un pago mayor al límite de la tarjeta, se lance una excepción.
     */
    public function testDoPaymentWithInsufficientLimitThrowsException(): void {
        $this->expectException(\Exception::class); // Prefijar con \ para asegurarse de que usa la clase global
        $customer = new Customer("41575190", "Tomás", "Vazquez");
        $visaCard = new VisaCard("12345678", 50.0, "Bank", $customer); // Límite bajo
        $this->posnet->registerCard($visaCard);

        // Intentamos hacer un pago mayor al límite
        $this->posnet->doPayment("12345678", 100, 1);
    }

    /**
     * Prueba el cálculo del pago en varias cuotas.
     * 
     * Verifica que el monto total incluya el recargo de cuotas y que el monto por cuota se calcule correctamente.
     */
    public function testDoPaymentWithInstallments(): void {
        $customer = new Customer("41575190", "Tomás", "Vazquez");
        $visaCard = new VisaCard("12345678", 1000.0, "Bank", $customer); // Cambia el número a "12345678" o asegúrate de que coincida
        $this->posnet->registerCard($visaCard);
        
        // Utiliza el mismo número de tarjeta en doPayment
        $ticket = $this->posnet->doPayment("12345678", 100, 4);
    
        $this->assertEquals(109, $ticket['total_amount']); // El monto total esperado es 109
        $this->assertEquals(27.25, $ticket['installment_amount']); // Pago en 4 cuotas
    }
}
