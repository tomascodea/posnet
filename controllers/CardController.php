<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use posnet\src\Customer;
use posnet\src\cards\VisaCard;
use posnet\src\cards\AmexCard;
use posnet\src\Posnet;

/**
 * Class CardController
 * Maneja el registro de tarjetas en el sistema POSNET.
 */
class CardController {
    
    /**
     * @var Posnet Sistema POSNET para registrar tarjetas y procesar pagos.
     */
    private Posnet $posnet;

    /**
     * CardController constructor.
     * Inicializa el sistema POSNET para manejar el registro de tarjetas.
     */
    public function __construct() {
        $this->posnet = new Posnet();
    }

    /**
     * Registra una tarjeta en el sistema POSNET.
     * 
     * Este método verifica el tipo de tarjeta (Visa o AMEX) y la registra en el sistema.
     * 
     * @param Request $request La solicitud entrante con los datos de la tarjeta y el cliente.
     * @param Response $response La respuesta donde se escribe el estado del registro.
     * 
     * @return Response La respuesta con el estado y mensaje de éxito o error en formato JSON.
     */
    public function registerCard(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        
        $customer = new Customer($data['dni'], $data['firstName'], $data['lastName']);
        $cardType = strtolower($data['type']);
        
        // Validar el tipo de tarjeta y crear la instancia correspondiente
        if ($cardType === 'visa') {
            $card = new VisaCard($data['number'], $data['limit'], $data['bankName'], $customer);
        } elseif ($cardType === 'amex') {
            $card = new AmexCard($data['number'], $data['limit'], $data['bankName'], $customer);
        } else {
            // Enviar error si el tipo de tarjeta no es compatible
            $response->getBody()->write(json_encode([
                'status' => 'error', 
                'message' => 'Card type not supported. Only Visa or AMEX.'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Registrar la tarjeta en POSNET
        $this->posnet->registerCard($card);

        // Respuesta de éxito
        $response->getBody()->write(json_encode([
            'status' => 'success', 
            'message' => 'Card registered successfully'
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
