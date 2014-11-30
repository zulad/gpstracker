<?php

// this place markers on google map

require_once "../+FCT/FCT.inc.php";

$cnx = array('host'=>'','user'=>'','db'=>'','pass'=>''); //<!!-- to set
$req = new REQ();$sql = "";

$req->open($cnx);

$tmp = $req->SQL('SELECT DISTINCT account FROM my_location ;');
foreach($tmp as $v)
	@$users .= '<span onclick="locate(this.innerHTML);">'.$v['account'].'</span><br/>';
	
if($_POST)
{
// send infos


}
else {
// display page

	// style
	$style = '<style>
	
		#users{
			width:250px;
			float:left;
		}
		#googleMap {
		
			width:1000px;
			height:800px;
			float:left;
		}
		.act{
			cusror:pointer;
		}
	</style>';
	
	// checking by date interval
	$sql = 'SELECT * FROM my_location WHERE '.($_GET['tmz']=="Phone"?'date':'server_date '). ' BETWEEN "'.$_GET["start"].'" AND "'.$_GET['end'].'";';
	$tmp = $req->SQL($sql);
	$position = '
		var lineCoordinates = [';
	foreach($tmp as $v)
	{		
		$position .= 'new google.maps.LatLng('.stripslashes($v['latitude']).','.stripslashes($v['longitude']).'),';
	}
	$position .= ']
	//
	var myPositionsList = [ ';
	
	foreach($tmp as $v)
	{
		$position .= '{
						"PhoneDate":"'.$v['date'].'",
						"ServerDate":"'.$v['server_date'].'",
						"Name":"",
						"Address":"",
						"City":"",
						"PostCode":"",
						"Email":"",
						"Phone":"",
						"Fax":"",
						"Language":"fr",
						"Vat":"",
						"PosLong":"'.$v['longitude'].'",
						"PosLat":"'.$v['latitude'].'"
						},';
	}
	$position .= '];';
	
	// script
	$script = '
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCeQMlVV9Wpm_66hfdOqSuxIvRdBkZCsZI&sensor=false"></script>
	<script src="mapper.js"></script>
	<script>
		'.$position.'
		// lance la recherche
		function goSearch(pStart,pEnd,pTmz)
		{
			// check phone
			if(document.getElementById("phoneTmz").checked)
				pTmz = "Phone";
			else
				pTmz = "Server";
				
			window.location.href="?start="+pStart+"&end="+pEnd+"&tmz="+pTmz;
		}
		
	</script>';
	$endScript = '<script>(function(){
					//
					mapper.initialize(myPositionsList);
					mapper.markUserOnMap([,]);
					mapper.locate(myPositionsList,lineCoordinates);
					//
					})();
					</script>';
	// content
	$content = '
		
		<div id="actions">[ Time : Phone <input type="radio" value="Phone" name="tmz" id="phoneTmz"/> Server <input type="radio" value="Server" name="tmz" id=""/> | <span onclick="" class="act">locate</span> | Entre : <input value="'.date("Y-m-d 00:00:00").'" type="text" id="sText_s" /> et <input value="'.date("Y-m-d 23:59:59").'" type="text" id="sText_e" />  <input type="button" onclick="goSearch(document.getElementById(\'sText_s\').value,document.getElementById(\'sText_e\').value);" value="search" /> ]</div><hr/>
		<div id="users">USERS : <br/>'.$users.'</div>
		<div id="map"><div id="googleMap"></div></div>
		<div id="lst">LIST : <br/>'.$list.'</div>
		
	';

	// output
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	'.$style.$script.'</head><body>'.$content.$endScript.'<hr style="clear:both;"/>'.$sql.'</body></html>';
}

$req->close();
unset($cnx,$req,$tmp);

?>