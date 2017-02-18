<?php
/**
 * New Zealand Government Organisations - JSONLD generator
 *
 * Pull authoritative list of all NZ government organisations from govt.nz api and use to load data.govt.nz organisations.
 * Outputs loadable josnld (JSON Linked Data) file ready for use with @see https://github.com/ckan/ckanapi
 **/
setlocale(LC_ALL, 'en_US.UTF8');

//Tell the user we've started
echo "Generating an authoritative list of all New Zealand Governemnt organisations in JSON Linked Data format... please wait...";

//pull direct from govt.nz government organisation service
$govt_orgs = json_decode(file_get_contents("https://www.govt.nz/api/v2/organisation/list?limit=500&sort=name"), true); // decode json

//set up some variables
$orgs_jsonld = "";
$orgs = $govt_orgs['organisations'];

//loop over the orgs
foreach ($orgs as $org){
  //clean up data
  $slug = cleanURL($org['name']);
  $clean_desc = json_esc($org['description']);
  //build the jsonld line
  $orgs_jsonld .= '{"state": "active", "title": "'.$org['name'].'", "description": "'.$clean_desc.'", "name": "'.$slug.'"}'.PHP_EOL;
}

//Use CKANAPI once you have this jsonld file generated to load the organisations into data.govt.nz.
file_put_contents('organisations.jsonld', $orgs_jsonld);

//Tell the user we're finished
echo "DING! Finished - have an awesome day!";

//Cleans the URL to Ascii format to use as 'name' property in CKAN. See http://cubiq.org/the-perfect-php-clean-url-generator
function cleanURL($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[|+ -]+/", $delimiter, $clean);
	return $clean;
}

//ensures that characters in jsonld that might break the API run are correctly escaped.
function json_esc($input, $esc_html = true) {
        $result = '';
        if (!is_string($input)) {
            $input = (string) $input;
        }

        $conv = array("\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', "''" => "\\'", '\\' => '\\\\');
        if ($esc_html) {
            $conv['<'] = '\\u003C';
            $conv['>'] = '\\u003E';
        }

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            if (isset($conv[$input[$i]])) {
                $result .= $conv[$input[$i]];
            }
            else if ($input[$i] < ' ') {
                $result .= sprintf('\\u%04x', ord($input[$i]));
            }
            else {
                $result .= $input[$i];
            }
        }

        return $result;
    }
