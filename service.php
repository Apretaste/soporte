<?php

class Soporte extends Service
{
	/**
	 * Function executed when the service is called
	 *
	 * @param Request
	 * @return Response
	 * */
	public function _main(Request $request){
		$email = $request->email;
		$userName = $request->name;
		$response = new Response();
		if (empty($request->query)){
			// get list of tickets
			$connection = new Connection();
			$result = $connection->deepQuery("SELECT id, `from`, subject, body, status, requester, creation_date FROM support_tickets WHERE `from` = '{$email}' OR requester = '{$email}' ORDER BY creation_date DESC");

			$tickets = array();

			// create array of arrays
			foreach($result as $ticket){
				array_push($tickets, $ticket); 
			}

			// get variables to send to the template
			//$response = new Response();
			if (count($tickets) > 0){
				$responseContent = array(
					"tickets" => $tickets,
					"userName" => $userName,
					"userEmail" => $email,
					"ticketsNum" => count($tickets)
				);
				// create response
				
				$response->setResponseSubject("Lista de tickets de Soporte de Apretaste");
				$response->createFromTemplate("ticketsList.tpl", $responseContent);
			}else{	
				$titulo = "A&uacute;n no has enviado ning&uacute;n mensaje a Soporte.";
				$mensaje = "Todav&iacute;a no has enviado ning&uacute;n mensaje al equipo de Soporte, puedes crear el primero haciendo click en el boton 'Agregar'.";
				$response->setResponseSubject($titulo);
				$response->createFromTemplate("noTickets.tpl", array("titulo" => $titulo, "mensaje" => $mensaje));
			}
		
		}else{ //si el request viene con un parametro
			$query = $request->query;
			if (is_numeric ($query)){
				$response = $this->showTicket($query, $userName);
			}else{
				$response = $this->createTicket($request);
			}
		}
		

		// return
		return $response;
	}

	private function createTicket(Request $request){
		//INSERT INTO `apretaste`.`support_tickets` (`id`, `from`, `subject`, `body`, `status`, `requester`, `creation_date`) VALUES (NULL, 'html@apretaste.com', 'Ticket from html@apretaste.com', 'me da un error la bolita', 'NEW', NULL, CURRENT_TIMESTAMP);
		$connection = new Connection();
		$sql = "INSERT INTO support_tickets (`from`, `subject`, `body`, `status`) ";
		$sql .=" VALUES ('".$request->email."','Ticket from ".$request->email."', '".$request->query." <br>".$request->body."', 'NEW');";
		
		$response = new Response();
		$mensaje = "";
		if ($connection->deepQuery($sql)){
			$titulo = "Mensaje enviado exitosamente.";
			$mensaje = "Su petici&oacute;n ha sido enviada satisfactoriamente. Le responderemos lo m&aacute;s r&aacute;pido posible. En casos extremos podemos demorar hasta 72 horas en responder. Por favor sea paciente.";
			$response->setResponseSubject($titulo);
			$response->createFromTemplate("success.tpl", array("titulo" => $titulo, "mensaje" => $mensaje));
		}else{
			$titulo = "Ocurri&oacute; un eror al procesar los datos.";
			$mensaje = "Disculpe, ocurrio un eror al enviar su mensaje. Por favor verifique e intente de nuevo.";
			$response->setResponseSubject($titulo);
			$response->createFromTemplate("error.tpl", array("titulo" => $titulo, "mensaje" => $mensaje));
		}
		
		return $response;
	}

	private function showTicket($query, $userName){
		$connection = new Connection();
		$result = $connection->deepQuery("SELECT * FROM support_tickets WHERE id = '{$query}';");

		$response = new Response();
		if ($result){
			$ticket = $result[0];
			$titulo = "Ticket #: ". $ticket->id;
			
			$response->setResponseSubject($titulo);
			$response->createFromTemplate("showTicket.tpl", array("titulo" => $titulo, "ticket" => $ticket, "userName" => $userName));
		}else{
			$titulo = "Ticket no encontrado.";
			$mensaje = "Disculpe, el ticket solicitado no se encuentra registrado. Por favor verifique e intente de nuevo.";
			$response->setResponseSubject($titulo);
			$response->createFromTemplate("error.tpl", array("titulo" => $titulo, "mensaje" => $mensaje));
		}

		return $response;
	}
}