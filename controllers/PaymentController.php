<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use posnet\src\Posnet;

/**
 * Class PaymentController
 * Controlador que maneja el procesamiento de pagos en el sistema POSNET.
 */
class PaymentController {
    
    /**
     * @var Posnet Sistema POSNET que gestiona el registro de tarjetas y el procesamiento de pagos.
     */
    private Posnet $posnet;

    /**
     * PaymentController constructor.
     * Inicializa una instancia de Posnet al crear el controlador.
     */
    public function __construct() {
        $this->posnet = new Posnet();
    }

    /**
     * Procesa un pago usando una tarjeta registrada en el sistema POSNET.
     * 
     * Este método recibe los datos de la tarjeta, el monto y la cantidad de cuotas,
     * y genera un ticket en caso de éxito. Si ocurre algún error, devuelve una respuesta
     * con un mensaje de error y el código HTTP correspondiente.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de pago.
     * @param Response $response La respuesta HTTP que contendrá el resultado de la operación.
     * 
     * @return Response La respuesta con el estado de la operación en formato JSON.
     */
    public function makePayment(Request $request, Response $response): Response {
        $data = $request->getParsedBody();

        try {
            // Procesa el pago usando el sistema POSNET
            $ticket = $this->posnet->doPayment($data['cardNumber'], $data['amount'], $data['installments']);
            $response->getBody()->write(json_encode(['status' => 'success', 'ticket' => $ticket]));
        } catch (\Exception $e) {
            // Manejo de errores en caso de excepciones durante el proceso de pago
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
