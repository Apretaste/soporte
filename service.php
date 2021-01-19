<?php

use Apretaste\Request;
use Apretaste\Response;
use Apretaste\Tutorial;
use Apretaste\Challenges;

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
		$email = $request->person->email;
		$username = $request->person->username;

		// get the list of messages
		$tickets = Connection::query("
			SELECT A.*, B.username 
			FROM support_tickets A 
			JOIN person B
			ON A.from = B.email
			WHERE A.from = '$email' 
			OR A.requester = '$email' 
			ORDER BY A.creation_date ASC", true, 'utf8mb4');

		// prepare chats for the view
		$chat = [];
		foreach ($tickets as $ticket) {
			$message = new stdClass();
			$message->class = $ticket->from == $email ? "me" : "you";
			$message->from = $ticket->username;
			$message->text = preg_replace('/[\x00-\x1F\x7F]/u', '', $ticket->body);
			$message->date = date_format((new DateTime($ticket->creation_date)), 'd/m/Y h:i a');
			$message->status = $ticket->status;
			$chat[] = $message;
		}

		// send data to the view
		$response->setTemplate('home.ejs', ['chat' => $chat, 'myUsername' => $username]);
	}

	/**
	 * Create a new ticket
	 *
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @throws \Exception
	 */
	public function _escribir(Request $request, Response $response)
	{
		// get data to save ticket
		$email = $request->person->email;
		$message = preg_replace('/[\x00-\x1F\x7F]/u', '', $request->input->data->message);

		$app_name = $request->input->app ?? '';
		$app_version = $request->input->appversion ?? '';
		$os_version = $request->input->osversion ?? '';

		// insert the ticket
		$body = Connection::escape($message, 1024);
		Connection::query("
			INSERT INTO support_tickets (`from`, `subject`, `body`, app_name, app_version, os_version, from_id)
			VALUES ('{$email}', 'Ticket from {$email}', '$body', '$app_name', '$app_version', '$os_version', {$request->person->id})", true, 'utf8mb4');

		// save report
		Connection::query("
			INSERT INTO support_reports (inserted, new_count) VALUES (CURRENT_DATE, 1)
			ON DUPLICATE KEY UPDATE new_count=new_count+1");

		// complete challenge
		Challenges::complete("write-to-support", $request->person->id);

		// complete tutorial
		Tutorial::complete($request->person->id, 'contact_support');
	}
}
