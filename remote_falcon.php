<h1 style="margin-left: 1em;">Remote Falcon Plugin</h1>
<h3 style="margin-left: 1em;">
	<p>
		Any time changes are made, go to Content Setup and click "Remote Falcon" to see the changes. 
	</p>
	<p>
		After completing the initial steps and/or modifying any of the toggles, you will need to restart FPP. After restarting, it may take up to a minute for the Remote URL 
		to appear on the Remote Falcon Control Panel.
	</p>
</h3>

<?php
/**GLOBALS */
$pageLocation = "Location: ?plugin=fremote-falcon&page=remote_falcon.php";
$sleepTime = "sleep 1";
$pluginPath = "/home/fpp/media/plugins/remote-falcon";
$scriptPath = "/home/fpp/media/plugins/remote-falcon/scripts";
$remoteFppEnabled = trim(file_get_contents("$pluginPath/remote_fpp_enabled.txt"));
$remoteJukeboxEnabled = trim(file_get_contents("$pluginPath/remote_jukebox_enabled.txt"));

/**FORM FUNCTIONS */
if (isset($_POST['saveRemoteToken'])) {
	$remoteToken = trim($_POST['remoteToken']);
  global $pluginPath;
	shell_exec("rm -f $pluginPath/remote_token.txt");
	shell_exec("echo $remoteToken > $pluginPath/remote_token.txt");
	echo "
		<div style=\"margin-left: 1em;\">
			<h4 style=\"color: #39b54a;\">Remote Token $remoteToken successfully saved. Please refresh the page.</h4>
		</div>
	";
}

if (isset($_POST['updateToggles'])) {
  global $pluginPath;
	$remoteFppChecked = "false";
	$remoteJukeboxChecked = "false";
	if (isset($_POST['remoteFppEnabled'])) {
		$remoteFppChecked = "true";
	}
	if (isset($_POST['remoteJukeboxEnabled'])) {
		$remoteJukeboxChecked = "true";
	}
	shell_exec("rm -f $pluginPath/remote_fpp_enabled.txt");
	shell_exec("rm -f $pluginPath/remote_jukebox_enabled.txt");
	shell_exec("echo $remoteFppChecked > $pluginPath/remote_fpp_enabled.txt");
	shell_exec("echo $remoteJukeboxChecked > $pluginPath/remote_jukebox_enabled.txt");
	echo "
		<div style=\"margin-left: 1em;\">
			<h4 style=\"color: #39b54a;\">Toggles have been successfully updated. Please refresh the page.</h4>
		</div>
	";
}

/**PLUGIN UI */
if(file_exists("$pluginPath/remote_token.txt")) {
	$remoteToken = file_get_contents("$pluginPath/remote_token.txt");
	if($remoteToken) {
		echo "
			<h3 style=\"margin-left: 1em; color: #39b54a;\">Step 1:</h3>
			<h5 style=\"margin-left: 1em;\">If you need to update your remote token, place it in the input box below and click \"Update Token\".</h5>
			<div style=\"margin-left: 1em;\">
				<form method=\"post\">
					Your Remote Token: <input type=\"text\" name=\"remoteToken\" id=\"remoteToken\" size=100 value=\"${remoteToken}\">
					<br>
					<input id=\"saveRemoteTokenButton\" class=\"button\" name=\"saveRemoteToken\" type=\"submit\" value=\"Update Token\"/>
				</form>
			</div>
		";
	}
} else {
	echo "
		<h3 style=\"margin-left: 1em; color: #39b54a;\">Step 1:</h3>
		<h5 style=\"margin-left: 1em;\">Place your unique remote token, found on your Remote Falcon Control Panel, in the input box below and click \"Save Token\".</h5>
		<div style=\"margin-left: 1em;\">
			<form method=\"post\">
				<input type=\"text\" name=\"remoteToken\" id=\"remoteToken\" size=100>
				<br>
				<input id=\"saveRemoteTokenButton\" class=\"button\" name=\"saveRemoteToken\" type=\"submit\" value=\"Save Token\"/>
			</form>
		</div>
	";
}

echo "<br>";
if(strval($remoteFppEnabled) == "true") {
	echo "
		<h3 style=\"margin-left: 1em; color: #39b54a;\">Step 2:</h3>
		<h5 style=\"margin-left: 1em;\">Adjust the toggles below to turn Remote FPP and Remote Jukebox on or off. Remote FPP is turned on by default. When done, click \"Update Toggles\".</h5>
		<div style=\"margin-left: 1em;\">
			<form method=\"post\">
				<input type=\"checkbox\" name=\"remoteFppEnabled\" id=\"remoteFppEnabled\" checked/> Remote FPP Enabled
	";
}else {
	echo "
		<h3 style=\"margin-left: 1em; color: #39b54a;\">Step 2:</h3>
		<h5 style=\"margin-left: 1em;\">Adjust the toggles below to turn Remote FPP and Remote Jukebox on or off. Remote FPP is turned on by default.</h5>
		<div style=\"margin-left: 1em;\">
			<form method=\"post\">
				<input type=\"checkbox\" name=\"remoteFppEnabled\" id=\"remoteFppEnabled\"/> Remote FPP Enabled
	";
}
echo "<br>";
if(strval($remoteJukeboxEnabled) == "true") {
	echo "
				<input type=\"checkbox\" name=\"remoteJukeboxEnabled\" id=\"remoteJukeboxEnabled\" checked/> Remote Jukebox Enabled
				<br>
				<input id=\"updateTogglesButton\" class=\"button\" name=\"updateToggles\" type=\"submit\" value=\"Update Toggles\"/>
			</form>
		</div>
	";
}else {
	echo "
				<input type=\"checkbox\" name=\"remoteJukeboxEnabled\" id=\"remoteJukeboxEnabled\"/> Remote Jukebox Enabled
				<br>
				<input id=\"updateTogglesButton\" class=\"button\" name=\"updateToggles\" type=\"submit\" value=\"Update Toggles\"/>
			</form>
		</div>
	";
}

echo "<br>";
echo "
	<h3 style=\"margin-left: 1em; color: #39b54a;\">Step 3:</h3>
	<h5 style=\"margin-left: 1em;\">Click the Restart FPPD button below. Click \"View Remote Falcon Logs\" to refresh the logs after startup.</h5>
";

if(file_exists("$pluginPath/remote_url.txt")) {
	$remoteUrl = file_get_contents("$pluginPath/remote_url.txt");
	$pieces = explode(' ', $remoteUrl);
	$lastWord = "";
	$lastWord = trim(array_pop($pieces));
	if (strpos($lastWord, '.localhost.run') === false) {
			foreach ($pieces as &$value) {
				if (strpos($value, '.localhost.run') !== false) {
						$lastWord = trim($value);
				}
		}
	}
	$lastWord = substr($lastWord, 1); 
	$lastWord = "https://" . $lastWord;
	echo "<br>";
	echo "
		<div style=\"margin-left: 1em;\">
			Your current Remote URL is <strong style=\"color: #39b54a;\">$lastWord</strong>
		</div>
	";
}

echo "<br>";
echo "
	<h5 style=\"margin-left: 1em;\">If the URL above is correct, but the one in the logs below is not, click \"Send URL\" button to update the URL in the Remote Falcon app.</h5>
	<div style=\"margin-left: 1em;\">
			<form method=\"post\">
				<input id=\"sendUrlButton\" class=\"button\" name=\"sendUrl\" type=\"submit\" value=\"Send URL\"/>
			</form>
		</div>
";

if (isset($_POST['sendUrl'])) {
	$remoteToken = trim(file_get_contents("$pluginPath/remote_token.txt"));
	$remoteUrl = file_get_contents("$pluginPath/remote_url.txt");
	$pieces = explode(' ', $remoteUrl);
	$lastWord = "";
	$lastWord = trim(array_pop($pieces));
	if (strpos($lastWord, '.localhost.run') === false) {
			foreach ($pieces as &$value) {
				if (strpos($value, '.localhost.run') !== false) {
						$lastWord = trim($value);
				}
		}
	}
	$lastWord = substr($lastWord, 1); 
	$lastWord = "https://" . $lastWord;
	$url = "https://remotefalcon.com/cgi-bin/rmrghbsEvMhSH8LKuJydVn23pvsFKX/saveRemoteByKey.php";
	$data = array(
		'remoteKey' => $remoteToken,
		'remoteURL' => $lastWord
	);
	$options = array(
		'http' => array(
			'method'  => 'POST',
			'content' => json_encode( $data ),
			'header'=>  "Content-Type: application/json\r\n" .
									"Accept: application/json\r\n"
			)
	);
	$context  = stream_context_create( $options );
	$result = file_get_contents( $url, false, $context );
	$response = json_decode( $result );
	if($response === true) {
		echo "
			<h3 style=\"margin-left: 1em; color: #39b54a;\">Success!</h3>
		";
	}else {
		echo "
			<h3 style=\"margin-left: 1em; color: #39b54a;\">Error!</h3>
		";
	}
}

echo "<br>";
if(file_exists("$pluginPath/remote_falcon.log")) {
	echo "
		<div style=\"margin-left: 1em;\">
			<form method=\"post\">
				<input id=\"viewLogsButton\" class=\"button\" name=\"viewLogs\" type=\"submit\" value=\"View Remote Falcon Logs (Click to refresh)\"/>
			</form>
		</div>
	";
}

if (isset($_POST['viewLogs'])) {
	$logs = file_get_contents("$pluginPath/remote_falcon.log");
	echo "
		<textarea rows=\"10\" cols=\"100\" disabled>
			$logs
		</textarea>
	";
}
?>