<?php
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 3) . "/vendor/autoload.php");

use Slack\Slack;

/**
 * API to display Slack channel history to the mirror
 *
 * @author Shihlin Lu
 **/


	// start session
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

// prepare an empty reply
	$reply = new stdClass();
	$reply->status = 200;
	$reply->data = null;

try {

	// initialize encrypted config variable
	$config = readConfig("/etc/apache2/capstone-mysql/piomirrors.ini");

	// grab mySQL statement
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/piomirrors.ini");

	// variable that will house the API key for Slack API
	$slack = $config["slack"];

	// determine which HTTP method is being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//If method is a GET request, return the latest messages
	$payload = new ChannelsHistoryPayload();
	$payload->setChannelId('C5BGUJ6R0');

	$apiClient = new ApiClient('slack-token-here');
	$response = $apiClient->send($payload);

	if($response->isOk()) {
		$response->getLatest()
	} else {
		// simple error
		echo $response->getError();

		// explained error
		echo $response->getErrorExplanation();
	}







} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);


/**
 * This is work-in-progress code for the slack-string to MySQL logic
 */

$slackString = "!todo boomshakalanaasas"; //This variable will be filled with the string from slack.

if(preg_match('#!#',$slackString) === 1 ) {
	switch($slackString) {

		case (preg_match('#!treky#',$slackString) === 1);
			echo "treky";
			break;
		case (preg_match('#!challenge#',$slackString) === 1);
			echo "challenge";
			break;
		case (preg_match('#!todo#',$slackString) === 1);
			echo "todo";
			break;
		case (preg_match('#!snackbot#',$slackString) === 1);
			echo "snackbot";
			break;
		case (preg_match('#!pun#',$slackString) === 1);
			echo "pun counter";
			break;
		case (preg_match('#!tictactoe#',$slackString) === 1);
			echo "tictactoe";
			break;
		case (preg_match('#!easteregg#',$slackString) === 1);
			echo "easteregg";
			break;
		case (preg_match('#!announce#',$slackString) === 1);
			echo "announce";
			break;
		default;
			echo "String did not match a switch case!";
	}
}
