<?php
	ob_start();
function curl_exec_follow($ch, &$maxredirect = null) {
  
  // we emulate a browser here since some websites detect
  // us as a bot and don't let us do our job
  $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)".
                " Gecko/20041107 Firefox/1.0";
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent );

  $mr = $maxredirect === null ? 5 : intval($maxredirect);

  if (ini_get('open_basedir') == '' && ini_get('safe_mode') == 'Off') {

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  } else {
    
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    if ($mr > 0)
    {
      $original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
      $newurl = $original_url;
      
      $rch = curl_copy_handle($ch);
      
      curl_setopt($rch, CURLOPT_HEADER, true);
      curl_setopt($rch, CURLOPT_NOBODY, true);
      curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
      do
      {
        curl_setopt($rch, CURLOPT_URL, $newurl);
        $header = curl_exec($rch);
        if (curl_errno($rch)) {
          $code = 0;
        } else {
          $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
          if ($code == 301 || $code == 302) {
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $newurl = trim(array_pop($matches));
            
            // if no scheme is present then the new url is a
            // relative path and thus needs some extra care
            if(!preg_match("/^https?:/i", $newurl)){
              $newurl = $original_url . $newurl;
            }   
          } else {
            $code = 0;
          }
        }
      } while ($code && --$mr);
      
      curl_close($rch);
      
      if (!$mr)
      {
        if ($maxredirect === null)
        trigger_error('Too many redirects.', E_USER_WARNING);
        else
        $maxredirect = 0;
        
        return false;
      }
      curl_setopt($ch, CURLOPT_URL, $newurl);
    }
  }
  return curl_exec($ch);
}
	$issues = array();

	// Check #1: Admin Files In-tact?

	$checks = array(
		'admin/index.php',
		'admin/docs/',
		'admin/bin/main.php',
		'admin/bin/diagnostics.php',
		'admin/i.header.php',
		'admin/i.footer.php'
	);

	foreach($checks as $file) {
		if(!file_exists("../../" . $file)) {
			$issues[] = array(
				'Problem' => "Missing File",
				'Detail' => $file,
				'Fixed' => false
			);
		}
	}

	// Check #2: Main Libraries In-tact?

	$checks = array(
		'libraries/account.php',
		'libraries/addon.php',
		'libraries/advertisements.php',
		'libraries/blocked.php',
		'libraries/captcha.php',
		'libraries/database.php',
		'libraries/domain.php',
		'libraries/fault.php',
		'libraries/installation.php',
		'libraries/language.php',
		'libraries/pagerank.php',
		'libraries/recaptcha.php',
		'libraries/simple_html_dom.php',
		'libraries/toolapp.php',
		'libraries/tools.php',
		'libraries/templates/',
		'libraries/templates/domain-name-single.php',
		'libraries/templates/domain-name-three.php',
		'libraries/templates/email.php',
		'libraries/templates/textarea-single.php',
		'libraries/templates/tags-text.php',
		'libraries/templates/tool-error.php'
	);

	foreach($checks as $file) {
		if(!file_exists("../../" . $file)) {
			$issues[] = array(
				'Problem' => "Missing Library",
				'Detail' => $file,
				'Fixed' => false
			);
		}
	}

	// Check #3: Can read language and database files?

	$db = "../../data/main.db";
	if(!file_exists($db)) {
		file_put_contents($db, "[]");
		
		$issues[] = array(
			'Problem' => "Missing Database",
			'Detail' => 'data/main.db',
			'Fixed' => true
		);
	}

	$db = "../../data/addons.json";
	if(!file_exists($db)) {
		file_put_contents($db, "[]");
		
		$issues[] = array(
			'Problem' => "Missing Database",
			'Detail' => 'data/addons.json',
			'Fixed' => true
		);
	}

	$db = "../../data/alerts.json";
	if(!file_exists($db)) {
		file_put_contents($db, "[]");
		
		$issues[] = array(
			'Problem' => "Missing Database",
			'Detail' => 'data/alerts.json',
			'Fixed' => true
		);
	}

	$db = "../../data/client-data.json";
	if(!file_exists($db)) {
		file_put_contents($db, "[]");
		
		$issues[] = array(
			'Problem' => "Missing Database",
			'Detail' => 'data/client-data.json',
			'Fixed' => true
		);
	}

	$db = "../../data/groups.json";
	if(!file_exists($db)) {
		file_put_contents($db, "[]");
		
		$issues[] = array(
			'Problem' => "Missing Database",
			'Detail' => 'data/groups.json',
			'Fixed' => true
		);
	}

	$db = "../../data/users.json";
	if(!file_exists($db)) {
		file_put_contents($db, "[]");
		
		$issues[] = array(
			'Problem' => "Missing Database",
			'Detail' => 'data/users.json',
			'Fixed' => true
		);
	}

	$dbs = array(
		'main.db', 'users.json', 'groups.json', 'client-data.json', 'alerts.json', 'addons.json'
	);

	foreach($dbs as $db) {
		$f = file_get_contents("../../data/{$db}");
		$c = json_decode($f, true);

		if($c === null) {
			$issues[] = array(
				'Problem' => "Corrupt Database",
				'Detail' => 'data/{$db}.json',
				'Fixed' => false
			);
		}
	}

	// Check #4: Check for error logs

	if(file_exists("../error.log") || file_exists("../error_log")) {
		$issues[] = array(
			'Problem' => "System Errors",
			'Detail' => 'Please review the box below.',
			'Fixed' => false
		);
	}
	if(file_exists("../../error.log") || file_exists("../../error_log")) {
		$issues[] = array(
			'Problem' => "System Errors",
			'Detail' => 'Please review the box below.',
			'Fixed' => false
		);
	}

	// Check #5: Can the homepage be seen?

	if(file_exists("../../index.html")) {
		$issues[] = array(
			'Problem' => "Homepage Override",
			'Detail' => 'An index.html file is present, and might be blocking index.php.',
			'Fixed' => false
		);
	}

	// Check #6: Check for initialization files


	$checks = array(
		'structures/app.php',
		'structures/footer.php',
		'structures/header.php'
	);

	foreach($checks as $file) {
		if(!file_exists("../../" . $file)) {
			$issues[] = array(
				'Problem' => "Missing Initializer",
				'Detail' => $file,
				'Fixed' => false
			);
		}
	}

	// Check #7: Can a valid result be retrieved from Google (over HTTPS/SSL)??

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.google.com/search?q=webfector");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec_follow($ch);

	if(curl_errno($ch) !== 0 || strpos($data, "unusual traffic") !== false) {
		$issues[] = array(
			'Problem' => "Connection Issue",
			'Detail' => 'Failed to receive results from Google over a secure (https) connection.',
			'Fixed' => false
		);
	}
	curl_close($ch);

	// Check #8: Can a valid result be retrieved from Google (over HTTP/non-SSL)?

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.google.com/search?q=webfector");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec_follow($ch);

	if(curl_errno($ch) !== 0 || strpos($data, "unusual traffic") !== false) {
		$issues[] = array(
			'Problem' => "Connection Issue",
			'Detail' => 'Failed to receive results from Google over a normal (http) connection.',
			'Fixed' => false
		);
	}
	if(strpos($data, "unusual traffic") !== false) {
		$issues[] = array(
			'Problem' => "Blocked By Google",
			'Detail' => 'Your server IP is blocked by Google. This is not caused by SEO Studio.',
			'Fixed' => false
		);
	}
	curl_close($ch);

	// Check #9: Can I connect to the Altusia API?

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.altusia.com/echo");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$data = curl_exec_follow($ch);
	curl_close($ch);

	if(trim($data) !== "echo") {
		$issues[] = array(
			'Problem' => "Connection Issue",
			'Detail' => 'Failed to connect to the Altusia API.',
			'Fixed' => false
		);
	}
?>

<table class="table table-striped" width="100%">
	<thead>
		<tr>
			<th>Problem</th>
			<th>Details</th>
			<th>Fixed</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($issues as $issue) {
?>
		<tr>
			<td><?=$issue['Problem'];?></td>
			<td><?=$issue['Detail'];?></td>
			<td><?php
				if($issue['Fixed']) echo "Yes";
				else echo "No";
			?></td>
		</tr>
<?php
	}

	if(count($issues) == 0) {
		echo "<tr><td colspan='3'>No issues found!</td></tr>";
	}
?>
	</tbody>
</table>