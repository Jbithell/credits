<?php
if (isset($_GET['json'])) {
	$OUTPUT = [];
	$OUTPUT['TYPES'] = $types;
	$OUTPUT['CREDITS'] = [];
	foreach ($types as $typeid=>$type) {
		foreach ($credits as $credit) {
			if ($credit['credits_type'] != $typeid) continue;
			$credit['credits_private_notes'] = '';
			
			$subTypes = explode(",",$credit["credits_sub_types_id_str"]);
			$credit["credits_sub_types_list"] = [];
			if (count($subTypes) > 0) {
				foreach ($subTypes as $subType) {
					$credit["credits_sub_types_list"][] = $creditSubTypes[trim($subType)]['credits_sub_types_name'];
				}
			}
			
			if ($credit['credits_startDate_accuracy'] == "Y") $dateFormat = "Y";
			elseif ($credit['credits_startDate_accuracy'] == "M") $dateFormat = "F Y";
			elseif ($credit['credits_startDate_accuracy'] == "D") $dateFormat = "j F Y";
			else $dateFormat = "";
			$credit['credits_startDate'] = ($credit['credits_startDate'] != "" ? date($dateFormat, strtotime($credit['credits_startDate'])) : "");
			
			if (count($credit['credits_images']) > 0) $credit['credits_images'] = explode(",",$credit["credits_images"]);
			else $credit['credits_images'] = [];
			$credit['type_name'] = $type;
			$OUTPUT['CREDITS'][] =  $credit;
		}
	}
	header('Content-type: application/json');
	die(json_encode($OUTPUT));
} else {	
	$output = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=" crossorigin="anonymous" />
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha256-VsEqElsCHSGmnmHXGQzvoWjWwoznFSZc6hs7ARLRacQ=" crossorigin="anonymous"></script>
			<title>James Bithell Full Credits List</title>
			<table border="1" style="vertical-align: top; width: 100%;"  class="table table-striped table-bordered">';
	$output .= '<thead class="thead-light"><tr><td colspan="999" style="text-align: center;"><h1>James Bithell</h1><h2>';
	$output .= 'Credits List</h2><h3>';
	if (isset($_GET['venue'])) $output .= '<br/><b>Venue: </b>' . creditsanitizestring($_GET['venue']);
	if (isset($_GET['director'])) $output .= '<br/><b>Director: </b>' . creditsanitizestring($_GET['director']);
	if (isset($_GET['subcredit'])) $output .= '<br/><b>Role: </b>' . $creditSubTypes[creditsanitizestring($_GET['subcredit'])]['credits_sub_types_name'];
	$output .= '</h3><h4>Showing ' . count($credits) . ' credit' . (count($credits) == 1 ? '' : 's') . (isset($_GET['future']) ? ' (including planned credits)' : '') . '<br/>'; 
	if (count($_GET) > 0) $output .= ' [<a href="?">Reset Filters</a>]';
	if (!isset($_GET['future'])) $output .= ' [<a href="?' . buildLink("future", 1) . '">View planned credits</a>]';
	$output .= '</h4></td></tr></thead><tbody>';
	foreach ($types as $typeid=>$type) {
		$output .= '<tr><td colspan="999"><h3>' . $type . '</h3></td></tr>';
		$output .= '<tr><th></th><th>Name</th><th>Role</th><th></th><th>Author</th><th>Director</th><th>Date</th><th>Venue</th></tr>';
		foreach ($credits as $credit) {
		
			if ($credit['credits_type'] != $typeid) continue;
			$output .= '<tr>';
			$output .= '<td>' . ($credit['credits_isPaid'] == 1 ? '£' : '') . '</td>';
			$output .= '<td>' . $credit['credits_name'];
			if (strlen($credit['credits_public_notes'])>0) $output .= '<br/><i>' . $credit['credits_public_notes'] . '</i>';
			$output	.= '</td>';
			$output .= '<td>';
			$subTypes = explode(",",$credit["credits_sub_types_id_str"]);
			if (count($subTypes) > 0) {
				foreach ($subTypes as $subType) {
					$output .= '<a href="?' . buildLink("subcredit",trim($subType)) . '" title="Click to filter">' . $creditSubTypes[trim($subType)]['credits_sub_types_name'] . '</a><br/>';
				}
			}
			$output .= '</td>';
			if (isset($_GET['password']) && $_GET['password'] == "EpWus30Jnk") $output .= '<td>' . $credit['credits_private_notes'] . '</td>';
			else $output .= '<td></td>';
			$output .= '<td>' . $credit['credits_subTitle_author'] . '</td>';
			$output .= '<td><a href="?' . buildLink("director", $credit['credits_subTitle_director']) . '" title="Click to filter">' . $credit['credits_subTitle_director'] . '</a></td>';
			if ($credit['credits_startDate_accuracy'] == "Y") $dateFormat = "Y";
			elseif ($credit['credits_startDate_accuracy'] == "M") $dateFormat = "F Y";
			elseif ($credit['credits_startDate_accuracy'] == "D") $dateFormat = "j F Y";
			else $dateFormat = "";
			$output .= '<td>' . ($credit['credits_startDate'] != "" ? date($dateFormat, strtotime($credit['credits_startDate'])) : "");
			if ($credit['credits_performances_attended'] > 1) $output .= ' [' . $credit['credits_performances_attended'] . ' performances]';
			$output .=  '</td>';
			$output .= '<td><a href="?' . buildLink("venue", $credit['credits_venue']) . '" title="Click to filter">' . $credit['credits_venue'] . '</a></td>';
			$output .= '</tr>';
			
		}
	}

	$output .= '</tbody></table>';

	echo $output;
}
?>
