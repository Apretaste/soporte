<?php

class Soporte extends Service
{
	/**
	 * Function executed when the service is called
	 *
	 * @param Request
	 * @return Response
	 */
	public function _main(Request $request)
	{
		$email = $request->email;
		$userName = $request->username;

		$response = new Response();
		if (empty($request->query))
		{
			// get list of tickets
			$connection = new Connection();
			$result = $connection->query("SELECT id, `from`, subject, body, status, requester, creation_date FROM support_tickets WHERE `from` = '{$email}' OR requester = '{$email}' ORDER BY creation_date DESC");

			$tickets = array();

			// create array of arrays
			foreach($result as $ticket){
				array_push($tickets, $ticket);
			}

			// get variables to send to the template
			if (count($tickets) > 0)
			{
				$responseContent = array(
					"tickets" => $tickets,
					"userName" => $userName,
					"userEmail" => $email,
					"ticketsNum" => count($tickets)
				);

				$response->setCache(180);
				$response->setResponseSubject("Lista de consultas al soporte");
				$response->createFromTemplate("ticketsList.tpl", $responseContent);
			}
			else
			{
				$response->setResponseSubject("No hay consultas abiertas");
				$response->createFromTemplate("noTickets.tpl", array());
			}
		}
		else //si el request viene con un parametro
		{
			if (is_numeric($request->query)) $response = $this->showTicket($request->query);
			else $response = $this->createTicket($request);
		}

		// return
		return $response;
	}

	/**
	 * Create a new ticket
	 */
	private function createTicket(Request $request)
	{
		// insert ticket
		$connection = new Connection();
		$connection->query("
			INSERT INTO support_tickets (`from`, `subject`, `body`)
			VALUES ('{$request->email}', 'Ticket from {$request->email}', '{$request->query}')");

		// create response
		$response = new Response();
		$response->setResponseSubject("Mensaje enviado");
		$response->createFromTemplate("success.tpl", array());
		return $response;
	}

	/**
	 * Display a full ticket
	 */
	private function showTicket($query)
	{
		// get ticket
		$connection = new Connection();
		$result = $connection->query("SELECT * FROM support_tickets WHERE id = '$query';");

		$response = new Response();
		if ($result)
		{
			$ticket = $result[0];
			$ticket->username = $this->utils->getUsernameFromEmail($ticket->from);

			$response->setResponseSubject("Ticket #{$ticket->id}");
			$response->createFromTemplate("showTicket.tpl", array("ticket" => $ticket));
		}
		else
		{
			$response->setResponseSubject("Ticket no encontrado");
			$response->createFromText("Disculpe, el ticket solicitado no se encuentra registrado. Por favor verifique el numero e intente de nuevo");
		}

		$response->setCache();
		return $response;
	}
}
