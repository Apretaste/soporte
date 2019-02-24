<?php

class Service
{
	/**
	 * Show the list of messages
	 *
	 * @param Request
	 * @param Response
	 */
	public function _main(Request $request, Response $response)
	{
		// @TODO replace email with ids
		$userEmail = Utils::getPerson($request->person->id)->email;
		$username = $request->person->username;

		// get the list of messages
		$tickets = Connection::query("
			SELECT *
			FROM support_tickets
			WHERE `from` = '$userEmail'
			OR requester = '$userEmail'
			ORDER BY creation_date ASC");

		// prepare chats for the view
		$chat = [];
		$usernames = [$userEmail => $username];
		foreach($tickets as $ticket) {
			$from = $ticket->from;
			$requester = $ticket->requester;

			// dont lookup for the same email twice
			if( ! in_array($from, $usernames)) $usernames[$from] = Utils::getPerson($from)->username;

			$message = new stdClass();
			$message->from = $usernames[$from]; //replace emails with usernames
			$message->text = $ticket->body;
			$message->date = date_format((new DateTime($ticket->creation_date)),'d/m/Y h:i a');
			$message->status = $ticket->status;
			$message->class = $from==$userEmail ? "me":"you";
			$chat[] = $message;
		}

		// send data to the view
		$response->setTemplate('home.ejs',['chat' => $chat, 'myUsername' => $username]);
	}

	/**
	 * Create a new ticket
	 *
	 * @param Request $request
	 * @param Response $response
	 */
	public function _escribir(Request $request, Response $response)
	{
		$message = $request->input->data->message;
		$userEmail = Utils::getPerson($request->person->id)->email; // @TODO replace email with ids

		// insert ticket
		$body = Connection::escape($message, 1024);
		Connection::query("
			INSERT INTO support_tickets (`from`, `subject`, `body`)
			VALUES ('{$userEmail}', 'Ticket from {$userEmail}', '$body')");
	}
}
