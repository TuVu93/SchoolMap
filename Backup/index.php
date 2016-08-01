<!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Polestar</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script src="scripts/jquery.imagemapster.js"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />
	
	<!-- *** IMAGE MAPSTER ****************************************** -->
	<script language="javascript">
	
	// There are two containers (DIV) for plans navigation
	// Left container DIV with ID = "leftCont" and right container DIV with ID = "rightCont"
	// There is one image per container with IDs "leftImg" and "rightImg" however these
	// are basically place holders for the image maps

	// Building selections
	var fromMap = true;
	function buildingSelect(building)
	{		
		//Reset image
		$('#rightImg').mapster('unbind');
		$('#rightImg').attr({ useMap: '' });
		document.getElementById("rightImg").src = "images/blank.png";
		$('#leftImg').mapster('unbind');
		$('#leftImg').attr({ useMap: '' });
		$('#raaagh').html("");
		//get the right number of floor on each building
		var floor; 
		switch (building){
			case 'c':
				floor = 5;
				break;
			case 'd':
				floor = 1;
				break;
			default:
				floor = 3;
		}
		//set left image to building
		var leftImg = "images/Building_" + floor + ".png";
		document.getElementById("leftImg").src = leftImg;
		leftImageMap = "#building_" + floor;
		$('#leftImg').attr({ useMap: leftImageMap });
		//display back button
		document.getElementById("l-half-col").style.display="inline";
		$('#leftImg').mapster({
			singleSelect : true,
			isDeselectable: false,
			mapKey: 'rel',
			fillOpacity: 0.3,
			render_highlight: {
				fillColor: 'ffff99'
			},
			render_select: {
				fillColor: '8cc76c'
			},
			stroke: true,
			strokeColor: '275aea',
			stroke: 2,
			scaleMap: true,
			onClick: function (e) {floorSelect(building, e.key);}
		});
		fromMap = false;
		
		refresh_image();
	}
	
	function floorSelect(building, floor)
	{		
		//key and e.key sent by imagemapster define building & floor selected
		$('#rightImg').mapster('unbind');
		var rightImg = "images/" + building + "_" + floor + ".png";
		document.getElementById("rightImg").src = rightImg;
	
		//set map to selected floor
		var rightImageMap = "#" + building + "_" + floor;
	
		$('#rightImg').attr({ useMap: rightImageMap });
		$('#rightImg').mapster({
			singleSelect : true,
			isDeselectable: false,
			mapKey: 'rel',
			fillOpacity: 0.5,
			render_highlight: {
				fillColor: 'ffff99'
			},
			render_select: {
				fillColor: 'F7344B'
			},
			stroke: true,
			strokeColor: '275aea',
			stroke: 5,
			scaleMap: true,
			onClick: function (e) {roomSelect(building, floor, e.key);}
		});

		refresh_image();
	}
	
	function roomSelect(building, floor, room)
	{
        $.ajax({
            url: 'php/roomSelect.php', //Call roomSelect.php
            type: "POST",
            data: ({room: room}),
            success: function(data){
				// return value
                $("#raaagh").html(data);
            }
        });        
	}
	
	function backtoMain()
	{
		//Reset right image
		$('#rightImg').mapster('unbind');
		$('#rightImg').attr({ useMap: '' });
		document.getElementById("rightImg").src = "images/blank.png";
		
		//Return left image to school map
		document.getElementById("l-half-col").style.display="none";
		leftImg.src = "images/Overview.png";
		fromMap = true;
		
		//Re-assign left container to School_map mapster
		$('#leftImg').attr({ useMap: '#school_map' });
		$('#leftImg').mapster({
			singleSelect : true,
			isDeselectable: false,
			mapKey: 'rel',
			fillOpacity: 0.3,
			render_highlight: {
				fillColor: 'ffff99'
			},
			render_select: {
				fillColor: 'F7344B'
			},
			stroke: true,
			strokeColor: '275aea',
			stroke: 2,
			scaleMap: true,
			onClick: function (e) {buildingSelect(e.key);}
		});
		$('#raaagh').html("Teacher name here!!!");

		refresh_image();
	}
	
	// Initialize Image Mapster

	$(document).ready(function ()
	{
		$('#leftImg').attr({ useMap: '#school_map' });
		
		$('#leftImg').mapster({
			singleSelect : true,
			isDeselectable: false,
			mapKey: 'rel',
			fillOpacity: 0.3,
			render_highlight: {
				fillColor: 'ffff99'
			},
			render_select: {
				fillColor: '8cc76c'
			},
			stroke: true,
			strokeColor: '275aea',
			stroke: 2,
			scaleMap: true,
			onClick: function (e) {buildingSelect(e.key);}
		});
	});
	
	<!--Scale image to windows resolution-->
	var window_width = 0;
	var window_height = 0;

	function refresh_image() {
		var leftW, leftH, rightW, rightH;
		if (fromMap)
		{
			leftW = window_width*9/10;
			rightW = 0;
		}
		else {
			leftW = window_width/5;
			rightW = window_width*3/4;
			}
		$('#leftCont').css('width', leftW + 'px');
		$('#leftImg').css('width', leftW + 'px');
		$('#rightCont').css('width', rightW + 'px');
		$('#rightImg').css('width', rightW + 'px');
		$('#leftImg').mapster('resize', leftW, '');
		$('#rightImg').mapster('resize', rightW, '');
	}

	function window_resize() {
		window_width = $(window).width();
		window_height = $(window).height();
		refresh_image();
	}

	$(document).ready(function ()
	{
		//resize image
		window_resize();
		$(window).bind('resize', function() { window_resize(); });
		//make button remain selected
		$('button').click(function(){
			$('button').removeClass('selected');
			$(this).addClass('selected');
		});
	});
	
	
	<!--Search-->
	function search()
	{
		var name = document.getElementById("searchBox").value;
		name = name.trim();
		if (name.length == 0) { 
			alert("No no!!!");
			return;
		}
		else {
			jQuery.ajax({
            url: 'php/search.php', //Call roomSelect.php
            type: "POST",
			datatype: "json",
            data: ({name: name}),
            success: function(data){
				try {
					var parsed = JSON.parse(data);
					var room = parsed.room.toLowerCase();
					var building = parsed.building.toLowerCase();
					var floor = parsed.floor.toLowerCase();
					buildingSelect(building);
					floorSelect(building, floor);
					roomSelect(building, floor, room);
					$('#leftImg').mapster('set', true, floor);
					$('#rightImg').mapster('set', true, room);
				} catch (e) {
					$('#raaagh').html(data);
				}
            }
			}); 
			document.getElementById("searchBox").value = "";			
		}
	}		
	</script>
</head>

<body>
<div id=header style="width=100%; height: auto;">
<h1 style="margin-top: 0;float:left; width: 30%; text-align: left; vertical-align: top;">LapinAMK Map</h1> 
<form method="get" action="javascript:search()" id="searchForm" style="margin-top: 3%;float:right; width: 70%; text-align: right;"> 
	<input type="text" id="searchBox" name="name" value="" onfocus="this.value = '';" placeholder="Firstname, Lastname or Room number" style="width: 25%"> 
	<input type="button" onClick="search()" id="submitButton" name="submit" value="Search"> 
</form> 
<p id="raaagh" style="float:right; width: 100%; height: auto; text-align: right;"></p>
</div>

<div id="l-half-col" style="display: none;">
	<img id="backButton" class="back" onclick="backtoMain();return false;" src="images/ButtonBack.png" width="50" height="auto" border="0"/>
	<div id="radioContainer">
		<button id="btn_a" onclick="buildingSelect('a');return false;" class="">A</button>
		<button id="btn_b" onclick="buildingSelect('b');return false;">B</button>
		<button id="btn_c" onclick="buildingSelect('c');return false;">C</button>
		<button id="btn_d" onclick="buildingSelect('d');return false;">D</button>
		<button id="btn_e" onclick="buildingSelect('e');return false;">E</button>
	</div>
</div>
<div id="rightCont">
		<img id="rightImg" src="images/blank.png" style="border-style:none"/>
</div>
<div id="leftCont">
		<img id="leftImg" src="images/Overview.png" style="border-style:none" usemap="#school_map"/>
</div>


<map name="school_map" id="school_map">
	<area shape="poly" rel="a" href="#" coords=" 418,424, 419,424, 418,581, 479,582, 477,590, 528,608, 536,583, 573,582, 575,531, 659,530, 659,476, 575,475, 575,425, 535,425, 535,405, 523,405, 514,401, 506,400, 498,398, 489,398, 477,401, 469,405, 459,405, 459,425, 419,424"/>
	<area shape="poly" rel="b" href="#" coords=" 631,531, 632,605, 640,607, 640,641, 673,640, 673,608, 719,608, 720,641, 765,642, 765,660, 776,661, 784,670, 792,673, 803,676, 814,675, 821,672, 829,667, 836,660, 844,660, 845,642, 875,642, 875,677, 983,678, 985,586, 936,585, 936,530, 972,530, 972,495, 935,495, 935,480, 871,480, 871,402, 657,405, 658,476, 664,477, 663,534, 635,534"/>
	<area shape="poly" rel="c" href="#" coords=" 695,223, 801,223, 1043,143, 1065,205, 835,280, 837,287, 812,298, 786,297, 786,289, 696,288, 696,226"/>
	<area shape="poly" rel="d" href="#" coords=" 36,730, 58,670, 148,704, 137,741, 212,764, 197,819, 189,816, 176,860, 198,866, 161,994, 21,951, 59,825, 84,834, 97,789, 89,787, 104,732, 91,729, 86,744, 42,733, 39,734"/>
	<area shape="poly" rel="e" href="#" coords=" 329,1008,320,964,380,954,379,942,379,934,381,926,365,923,375,894,387,897,432,770,472,784,467,800,461,816,456,830,449,850,439,879,420,925,423,927,422,946,419,946,428,990" />
</map>

<map name="building_1" id="building_1">
    <area href="#" shape="poly" rel="1" coords="31,47,33,43,36,40,39,36,313,36,320,39,324,43,326,47,326,471,325,476,323,480,319,484,317,486,42,485,36,483,34,481,30,473" />
</map>

<map name="building_3" id="building_3">
    <area rel="1" href="#" shape="poly" coords="31,390,326,390,326,472,325,475,323,480,319,483,316,485,41,486,36,483,33,479,30,474,30,391" />
    <area rel="2" href="#" shape="poly" coords="30,341,326,340,325,228,30,228" />
    <area rel="3" href="#" shape="poly" coords="31,196,326,196,325,82,30,82" />
</map>

<map name="building_5" id="building_5">
    <area rel="1" href="#" shape="poly" coords="31,408,326,408,326,473,324,478,321,481,317,485,42,486,37,484,33,481,30,474,30,408" />
    <area rel="2" href="#" shape="poly" coords="31,383,325,383,326,306,30,306,30,382" />
    <area rel="3" href="#" shape="poly" coords="30,298,326,298,326,222,30,223" />
    <area rel="4" href="#" shape="poly" coords="30,214,327,214,326,137,30,138" />
    <area rel="5" href="#" shape="poly" coords="30,130,326,130,326,53,31,53" />
</map>

<map name="a_1" id="a_1">
	<area rel="a106" href="#" shape="poly" coords="282,114,187,114,188,196,282,196" />										<!-- A106 -->
	<area rel="a111" href="#" shape="poly" coords="297,67,315,61,329,57,354,57,392,66,391,115,402,116,402,195,302,195" />	<!-- A111 -->
	<area rel="a115" href="#" shape="poly" coords="408,117,408,193,498,193,498,117" />										<!-- A115 -->
	<area rel="a132" href="#" shape="poly" coords="620,296,620,335,589,335,590,297" />										<!-- A132 -->
	<area rel="a134" href="#" shape="poly" coords="561,296,504,295,504,335,561,335" />										<!-- A134 -->
	<area rel="a136c" href="#" shape="poly" coords="500,351,501,386,466,386,467,352" />										<!-- A136c -->
	<area rel="a136b" href="#" shape="poly" coords="501,390,467,391,467,412,500,410" />										<!-- A136b -->
	<area rel="a136a" href="#" shape="poly" coords="501,416,466,416,466,447,501,448" />										<!-- A136a -->
	<area rel="a141" href="#" shape="poly" coords="261,416,262,446,187,447,188,416" />										<!-- A141 -->
	<area rel="a142" href="#" shape="poly" coords="187,310,258,311,258,334,265,335,262,412,187,412" />						<!-- A142 -->
	<area rel="a102" href="#" shape="poly" coords="209,200,187,200,187,252,211,252" />										<!-- A102 -->
	<area rel="a138" href="#" shape="poly" coords="284,218,285,342,401,342,403,219" />										<!-- A138 -->
</map>

<map name="a_2" id="a_2">
	<area rel="a203" href="#" shape="poly" coords="340,115,263,115,263,216,340,216" />		<!-- A203 -->
	<area rel="a204" href="#" shape="poly" coords="344,115,344,197,370,196,366,116" />		<!-- A204 -->
	<area rel="a205" href="#" shape="poly" coords="370,116,374,197,498,197,498,116" />		<!-- A205 -->
	<area rel="a206" href="#" shape="poly" coords="501,231,503,116,578,116,579,230" />		<!-- A206 -->
	<area rel="a214" href="#" shape="poly" coords="578,349,502,349,503,450,579,450" />		<!-- A214 -->
	<area rel="a215" href="#" shape="poly" coords="498,368,473,369,478,450,498,450" />		<!-- A215 -->
	<area rel="a216" href="#" shape="poly" coords="469,368,474,451,344,450,342,368" />		<!-- A216 -->
	<area rel="a217" href="#" shape="poly" coords="340,351,263,352,263,449,339,450" />		<!-- A217 -->
	<area rel="a218" href="#" shape="poly" coords="340,347,263,348,263,312,340,311" />		<!-- A218 -->
	<area rel="a202" href="#" shape="poly" coords="339,219,262,220,262,254,339,254" />		<!-- A202 -->
</map>

<map name="a_3" id="a_3">
	<area rel="a305" href="#" shape="poly" coords="265,115,341,116,342,217,264,218" />		<!-- A305 -->
	<area rel="a306" href="#" shape="poly" coords="346,116,455,115,456,198,346,197" />		<!-- A306 -->
	<area rel="a307" href="#" shape="poly" coords="459,116,459,158,500,159,500,115" />		<!-- A307 -->
	<area rel="a309" href="#" shape="poly" coords="503,116,502,214,579,214,579,115" />		<!-- A309 -->
	<area rel="a310" href="#" shape="poly" coords="528,218,579,218,579,254,528,255" />		<!-- A310 -->
	<area rel="a315" href="#" shape="poly" coords="528,314,579,314,578,349,528,350" />		<!-- A315 -->
	<area rel="a318" href="#" shape="poly" coords="579,354,504,353,502,452,579,451" />		<!-- A318 -->
   	<area rel="a320" href="#" shape="poly" coords="499,370,346,370,346,452,498,452" />		<!-- A320 -->
   	<area rel="a321" href="#" shape="poly" coords="341,354,266,354,266,452,341,452" />		<!-- A321 -->
	<area rel="a322" href="#" shape="poly" coords="341,314,266,314,266,350,343,351" />		<!-- A322 -->
	<area rel="a304" href="#" shape="poly" coords="316,221,265,221,265,255,316,255" />		<!-- A304 -->
</map>

<map name="b_1" id="b_1">
	<area rel="a130" href="#" shape="poly" coords="414,227,543,226,545,267,558,268,558,309,571,311,572,352,585,354,585,407,415,407" />			<!-- A130 -->
	<area rel="b167" href="#" shape="poly" coords="579,228,578,343,591,344,595,357,676,357,676,228" />											<!-- B167 -->
	<area rel="b156" href="#" shape="poly" coords="723,219,881,219,877,253,866,291,841,328,804,361,771,379,736,386" />							<!-- B156 -->
	<area rel="b116" href="#" shape="poly" coords="967,522,1138,521,1137,452,1054,451,1053,416,967,417" />										<!-- B116 -->
	<area rel="b115" href="#" shape="poly" coords="887,533,957,532,956,577,887,578" />															<!-- B115 -->
	<area rel="b118" href="#" shape="poly" coords="990,533,1054,532,1055,576,982,576,981,551" />												<!-- B118 -->
	<area rel="b114" href="#" shape="poly" coords="886,583,1055,580,1056,681,1050,681,1032,664,1010,666,1009,715,887,714" />					<!-- B114 -->
	<area rel="b119" href="#" shape="poly" coords="1013,713,1012,670,1031,669,1046,683,1056,685,1056,713" />									<!-- B119 -->
	<area rel="b123" href="#" shape="poly" coords="1061,671,1062,798,1186,798,1186,763,1172,764,1172,671" />									<!-- B123 -->
	<area rel="b124" href="#" shape="poly" coords="1173,803,1061,803,1061,891,1173,891" />														<!-- B124 -->
	<area rel="b134" href="#" shape="poly" coords="850,619,849,846,822,847,812,861,790,879,773,885,746,886,726,880,703,866,689,846,668,846,669,803,711,801,712,685,668,636,595,619" />	<!-- B134 -->
	<area rel="b146" href="#" shape="poly" coords="566,625,566,712,486,713,486,625" />															<!-- B146 -->
	<area rel="b147" href="#" shape="poly" coords="481,713,481,625,446,624,446,713" />															<!-- B147 -->
	<area rel="b148" href="#" shape="poly" coords="441,625,442,713,349,713,349,624" />															<!-- B148 -->
	<area rel="b149" href="#" shape="poly" coords="349,621,424,621,423,606,443,595,441,532,350,532" />											<!-- B149 -->
   	<area rel="b102" href="#" shape="poly" coords="627,532,628,586,556,586,556,532" />															<!-- B102 -->
	<area rel="b_INFO" href="#" shape="poly" coords="676,410,614,410,615,361,677,361" />														<!-- INFO -->
</map>

<map name="b_2" id="b_2">												
	<area rel="b217" href="#" shape="poly" coords="960,534,1052,534,1052,621,959,621" />								<!-- B217 -->
	<area rel="b218" href="#" shape="poly" coords="1053,625,959,625,959,714,1053,714" />								<!-- B218 -->
	<area rel="b219" href="#" shape="poly" coords="1061,672,1172,672,1172,766,1185,766,1185,780,1059,780" />			<!-- B219 -->
	<area rel="b220" href="#" shape="poly" coords="1060,785,1186,784,1186,799,1173,801,1172,894,1061,894" />			<!-- B220 -->
	<area rel="b221" href="#" shape="poly" coords="1012,893,924,894,923,787,998,786,1013,802" />						<!-- B221 -->
	<area rel="b225" href="#" shape="poly" coords="770,651,770,694,796,693,795,653" />									<!-- B225 -->
	<area rel="b228" href="#" shape="poly" coords="844,720,666,720,666,847,844,847" />									<!-- B228 -->
	<area rel="b223" href="#" shape="poly" coords="767,652,768,693,736,693,736,653" />									<!-- B223 -->
	<area rel="b235" href="#" shape="poly" coords="732,653,732,717,590,714,590,682,602,679,614,666,619,652" />			<!-- B235 -->
	<area rel="b242" href="#" shape="poly" coords="438,625,346,625,346,714,439,714" />									<!-- B242 -->
	<area rel="b243" href="#" shape="poly" coords="346,534,439,533,438,622,346,621" />									<!-- B243 -->
	<area rel="b244" href="#" shape="poly" coords="444,533,495,533,495,599,443,599" />									<!-- B244 -->
	<area rel="b202" href="#" shape="poly" coords="551,533,551,621,624,622,624,532" />									<!-- B202 -->
	<area rel="b204" href="#" shape="poly" coords="628,533,628,584,696,583,696,532" />									<!-- B204 -->
	<area rel="b211" href="#" shape="poly" coords="738,534,771,533,771,568,765,576,750,577,738,564" />					<!-- B211 -->
	<area rel="b214" href="#" shape="poly" coords="775,534,775,571,766,580,766,621,807,621,808,533" />					<!-- B214 -->
	<area rel="b215" href="#" shape="poly" coords="813,534,919,532,920,621,812,621" />									<!-- B215 -->
	<area rel="b156" href="#" shape="poly" coords="717,218,735,411,893,411,892,233,899,234,900,210,880,211,880,217,879,218" /> <!-- B156 -->
</map>

<map name="b_3" id="b_3">
	<area rel="b330" href="#" shape="poly" coords="437,455,344,455,343,544,437,544" />				<!-- B330 -->
	<area rel="b331" href="#" shape="poly" coords="441,456,441,509,492,510,492,456" />				<!-- B331 -->
	<area rel="b302" href="#" shape="poly" coords="551,456,692,456,693,544,551,544" />				<!-- B302 -->
	<area rel="b303" href="#" shape="poly" coords="698,456,698,545,841,544,841,456" />				<!-- B303 -->
	<area rel="b304" href="#" shape="poly" coords="844,456,880,456,880,501,845,501" />				<!-- B304 -->
	<area rel="b305" href="#" shape="poly" coords="882,456,882,501,917,501,916,456" />				<!-- B305 -->
	<area rel="b310" href="#" shape="poly" coords="956,456,1049,456,1050,590,955,589" />			<!-- B310 -->
	<area rel="b312" href="#" shape="poly" coords="986,603,956,603,956,638,987,638" />				<!-- B312 -->
	<area rel="b313" href="#" shape="poly" coords="988,592,1051,592,1051,637,990,638" />			<!-- B313 -->
	<area rel="b314" href="#" shape="poly" coords="1055,595,1055,704,1181,705,1181,688,1166,689,1168,594" />	<!-- B314 -->
	<area rel="b315" href="#" shape="poly" coords="1055,707,1181,706,1181,723,1167,723,1168,817,1056,817" />	<!-- B315 -->
	<area rel="b316" href="#" shape="poly" coords="920,817,1009,817,1009,725,993,709,919,710" />	<!-- B316 -->
	<area rel="b320" href="#" shape="poly" coords="843,548,697,549,697,637,841,638" />				<!-- B320 -->
	<area rel="b321" href="#" shape="poly" coords="692,549,551,549,550,638,693,638" />				<!-- B321 -->
	<area rel="b322" href="#" shape="poly" coords="547,592,515,593,513,637,546,637" />				<!-- B322 -->
	<area rel="b323" href="#" shape="poly" coords="512,593,477,592,477,638,512,637" />				<!-- B323 -->
	<area rel="b328" href="#" shape="poly" coords="475,571,442,571,441,638,475,637" />				<!-- B328 -->
	<area rel="b329" href="#" shape="poly" coords="437,548,342,548,344,637,437,637" />				<!-- B329 -->
</map>

<map name="c_1" id="c_1">
	<area rel="c137" href="#" shape="poly" coords="127,392,185,393,182,477,160,477,160,467,126,468" />	<!-- C137 -->
	<area rel="c136" href="#" shape="poly" coords="189,394,245,393,244,476,188,474" />					<!-- C136 -->
	<area rel="c135" href="#" shape="poly" coords="251,394,343,394,342,476,249,476" />					<!-- C135 -->
	<area rel="c121" href="#" shape="poly" coords="590,360,703,321,727,400,614,439" />					<!-- C121 -->
	<area rel="c119" href="#" shape="poly" coords="708,320,821,282,843,360,732,399" />					<!-- C119 -->
	<area rel="c115c" href="#" shape="poly" coords="822,263,866,248,894,341,851,358" />					<!-- C115C -->
	<area rel="c115" href="#" shape="poly" coords="871,248,925,229,953,320,901,341" />					<!-- C115 -->
	<area rel="c103" href="#" shape="poly" coords="1083,192,1137,173,1155,232,1100,249" />				<!-- C103 -->
	<area rel="c102d" href="#" shape="poly" coords="1143,173,1160,230,1215,211,1197,155" />				<!-- C102d -->
	<area rel="c102" href="#" shape="poly" coords="1202,152,1219,210,1273,191,1255,135" />				<!-- C102 -->
	<area rel="c100" href="#" shape="poly" coords="1268,269,1323,249,1345,320,1289,339" />				<!-- C100 -->
	<area rel="c107" href="#" shape="poly" coords="1232,273,1256,351,1202,369,1177,292" />				<!-- C107 -->
	<area rel="c106" href="#" shape="poly" coords="1196,371,1172,293,941,372,964,449" /> 				<!-- C106 -->
	<area rel="c116" href="#" shape="poly" coords="889,388,912,467,799,507,776,428"/>					<!-- C116 -->
	<area rel="c117" href="#" shape="poly" coords="770,430,793,509,622,567,598,488"/> 					<!-- C117 -->
	<area rel="c118" href="#" shape="poly" coords="598,509,617,568,584,580,566,520" />					<!-- C118 -->
	<area rel="c126" href="#" shape="poly" coords="560,523,585,604,516,627,504,628,507,541" />			<!-- C126 -->
	<area rel="c128" href="#" shape="poly" coords="342,521,383,520,383,551,400,551,400,602,341,602" /> 	<!-- C128 -->
	<area rel="c129" href="#" shape="poly" coords="281,520,279,602,336,603,336,522" />					<!-- C129 -->
	<area rel="c130" href="#" shape="poly" coords="275,518,219,518,217,600,273,601" />					<!-- C130 -->
	<area rel="c131" href="#" shape="poly" coords="212,519,156,518,156,601,212,602" />					<!-- C131 -->
</map>

<map name="c_2" id="c_2">
	<area rel="c239" href="#" shape="poly" coords="128,394,186,394,184,481,127,479" />			<!-- C239 -->
	<area rel="c238" href="#" shape="poly" coords="191,395,247,395,246,481,188,483" />			<!-- C238 -->
	<area rel="c237" href="#" shape="poly" coords="251,394,251,481,307,481,309,395" />			<!-- C237 -->
	<area rel="c236" href="#" shape="poly" coords="313,396,312,481,344,482,345,396" />			<!-- C236 -->
	<area rel="c222" href="#" shape="poly" coords="590,363,644,344,668,425,613,443" />			<!-- C222 -->
	<area rel="c219" href="#" shape="poly" coords="649,343,673,421,844,365,818,285" />			<!-- C219 -->
	<area rel="c215a" href="#" shape="poly" coords="850,363,821,266,863,252,893,346" />			<!-- C215a -->
   	<area rel="c215" href="#" shape="poly" coords="898,347,868,250,922,230,953,326" />			<!-- C215 -->
	<area rel="c215b" href="#" shape="poly" coords="960,324,995,313,968,233,934,244" />			<!-- C215b -->
   	<area rel="c203" href="#" shape="poly" coords="1105,276,1279,218,1253,136,1080,195" />		<!-- C203 -->
	<area rel="c201" href="#" shape="poly" coords="1263,266,1319,247,1345,328,1288,348" />		<!-- C201 -->
	<area rel="c202" href="#" shape="poly" coords="1255,358,1229,278,1058,335,1083,417" />		<!-- C202 -->
	<area rel="c206" href="#" shape="poly" coords="1078,419,1061,369,999,388,1014,441" />		<!-- C206 -->
	<area rel="c206a" href="#" shape="poly" coords="995,390,1010,442,964,457,950,406" />		<!-- C206a -->
	<area rel="c216" href="#" shape="poly" coords="914,475,890,393,773,432,798,513" />			<!-- C216 -->
	<area rel="c217" href="#" shape="poly" coords="769,433,795,516,623,573,599,492" />			<!-- C217 -->
	<area rel="c227" href="#" shape="poly" coords="560,526,586,611,512,635,437,633,437,542,504,545" />	<!-- C227 -->
	<area rel="c229" href="#" shape="poly" coords="401,523,343,522,342,609,400,609" />			<!-- C229 -->
	<area rel="c230" href="#" shape="poly" coords="338,523,281,523,282,608,338,609" />			<!-- C230 -->
	<area rel="c231" href="#" shape="poly" coords="276,523,221,521,219,608,276,608" />			<!-- C231 -->
	<area rel="c232" href="#" shape="poly" coords="216,522,158,521,157,609,213,608" />			<!-- C232 -->
</map>

<map name="c_3" id="c_3">
    <area rel="c338" href="#" shape="poly" coords="129,483,165,484,166,488,188,489,187,399,129,398" />  						<!-- C338 -->
    <area rel="c337" href="#" shape="poly" coords="192,399,192,489,215,489,229,491,230,489,250,490,249,398" />					<!-- C337 -->
	<area rel="c336" href="#" shape="poly" coords="254,490,275,490,277,493,289,494,290,490,310,489,310,399,253,399" /> 			<!-- C336 -->
	<area rel="c335" href="#" shape="poly" coords="313,400,313,492,325,492,327,490,345,490,347,400" />							<!-- C335 -->
	<area rel="c334" href="#" shape="poly" coords="351,493,406,494,408,401,350,400" />											<!-- C334 -->
	<area rel="c320" href="#" shape="poly" coords="591,365,615,449,670,430,644,345" />											<!-- C320 -->
    <area rel="c319" href="#" shape="poly" coords="650,343,674,428,692,422,693,425,705,422,705,418,729,409,704,324" />  		<!-- C319 -->
	<area rel="c318" href="#" shape="poly" coords="708,322,734,408,749,402,754,404,764,401,764,398,789,388,763,303" />			<!-- C318 -->
	<area rel="c317" href="#" shape="poly" coords="768,301,793,387,845,368,821,282" />											<!-- C317 -->
	<area rel="c313a" href="#" shape="poly" coords="821,264,826,279,824,280,850,365,874,358,865,332,888,324,866,248" />			<!-- C313a -->
	<area rel="c313" href="#" shape="poly" coords="870,247,925,227,948,298,948,302,891,321" />									<!-- C313 -->
	<area rel="c303" href="#" shape="poly" coords="1082,189,1107,275,1280,212,1255,128" />										<!-- C303 -->
	<area rel="c300" href="#" shape="poly" coords="1268,261,1292,254,1295,259,1325,252,1347,327,1289,349,1265,270,1267,267" />	<!-- C300 -->
	<area rel="c302" href="#" shape="poly" coords="1061,346,1084,421,1257,360,1237,286,1233,288,1232,275,1063,334,1064,343" />	<!-- C302 -->
    <area rel="c306" href="#" shape="poly" coords="1001,393,1065,370,1080,422,1016,446" />										<!-- C306 -->
	<area rel="c306a" href="#" shape="poly" coords="950,411,996,395,1012,446,966,464" />										<!-- C306a -->
	<area rel="c314" href="#" shape="poly" coords="776,437,800,522,916,482,891,396" />											<!-- C314 -->
	<area rel="c315" href="#" shape="poly" coords="599,499,625,585,798,523,780,465,768,469,758,442" />							<!-- C315 -->
    <area rel="c325" href="#" shape="poly" coords="509,555,516,649,587,624,562,536" />											<!-- C325 -->
	<area rel="c326" href="#" shape="poly" coords="438,648,440,556,505,556,513,650" />											<!-- C326 -->
    <area rel="c328" href="#" shape="poly" coords="345,533,370,533,370,529,382,529,382,533,404,534,404,624,344,624" />			<!-- C328 -->
	<area rel="c329" href="#" shape="poly" coords="284,533,306,533,307,529,319,529,321,534,341,534,341,623,284,623" />			<!-- C329 -->
	<area rel="c330" href="#" shape="poly" coords="222,533,247,532,247,528,260,528,260,533,278,533,279,623,221,622" />			<!-- C330 -->
    <area rel="c331" href="#" shape="poly" coords="160,532,184,532,184,528,198,527,198,532,217,533,217,622,159,622" />			<!-- C331 -->
</map>

<map name="c_4" id="c_4">
    <area rel="c440" href="#" shape="poly" coords="129,395,187,395,188,482,167,482,166,477,128,477" />							<!-- C440 -->
    <area rel="c439" href="#" shape="poly" coords="191,396,191,483,213,482,213,486,227,486,228,483,248,483,248,396" />			<!-- C439 -->
	<area rel="c438" href="#" shape="poly" coords="251,396,252,483,274,483,276,487,289,488,290,484,309,483,309,397" />			<!-- C438 -->
	<area rel="c437" href="#" shape="poly" coords="312,398,313,485,325,486,324,483,345,483,346,398" />							<!-- C437 -->
	<area rel="c436" href="#" shape="poly" coords="350,486,405,487,407,399,349,398" />											<!-- C436 -->
	<area rel="c424" href="#" shape="poly" coords="589,364,614,445,634,439,636,442,648,438,647,435,669,426,644,346" />			<!-- C424 -->
    <area rel="c423" href="#" shape="poly" coords="648,344,703,325,728,406,704,414,705,418,693,422,691,419,672,425" />			<!-- C423 -->
    <area rel="c422" href="#" shape="poly" coords="706,324,731,406,749,399,752,403,764,399,762,396,763,394,787,387,763,305" />	<!-- C422 -->
	<area rel="c421" href="#" shape="poly" coords="766,302,790,385,809,378,810,381,823,377,822,375,823,373,846,367,820,286,818,283" /><!-- C421 -->
	<area rel="c414a" href="#" shape="poly" coords="820,268,848,365,871,357,864,333,887,324,866,252" />							<!-- C414a -->
    <area rel="c414" href="#" shape="poly" coords="867,251,898,349,921,341,918,334,925,332,918,316,946,304,924,232" />			<!-- C414 -->
    <area rel="c411" href="#" shape="poly" coords="933,246,970,235,994,315,979,321,971,293,948,300" />							<!-- C411 -->
    <area rel="c403" href="#" shape="poly" coords="1081,197,1105,278,1279,218,1254,138" />										<!-- C403 -->
	<area rel="c400" href="#" shape="poly" coords="1267,264,1291,257,1295,263,1323,256,1345,328,1288,348,1266,272,1268,270" />	<!-- C400 -->
    <area rel="c402" href="#" shape="poly" coords="1117,317,1142,399,1256,360,1231,278" />										<!-- C402 -->
	<area rel="c406" href="#" shape="poly" coords="1058,337,1083,418,1139,399,1114,319" />										<!-- C406 -->
	<area rel="c407" href="#" shape="poly" coords="1000,357,1054,339,1078,420,1025,439" />										<!-- C407 -->
	<area rel="c408" href="#" shape="poly" coords="940,375,997,358,1021,440,966,459" />											<!-- C408 -->
    <area rel="c415" href="#" shape="poly" coords="834,415,889,395,914,476,859,495" />											<!-- C415 -->
    <area rel="c416" href="#" shape="poly" coords="775,434,800,516,857,497,831,415" />											<!-- C416 -->
    <area rel="c417" href="#" shape="poly" coords="716,454,741,535,796,518,779,461,767,465,759,438" />							<!-- C417 -->
    <area rel="c418" href="#" shape="poly" coords="658,474,712,455,737,537,682,555" />											<!-- C418 -->
    <area rel="c419" href="#" shape="poly" coords="599,493,624,575,679,557,653,476" />											<!-- C419 -->
    <area rel="c429" href="#" shape="poly" coords="438,545,505,546,562,528,588,612,515,637,438,635" />							<!-- C429 -->
    <area rel="c431" href="#" shape="poly" coords="344,525,402,525,403,612,343,611" />											<!-- C431 -->
    <area rel="c432" href="#" shape="poly" coords="282,524,341,525,341,611,282,610" />											<!-- C432 -->
    <area rel="c433" href="#" shape="poly" coords="159,523,279,524,279,610,158,610" />											<!-- C433 -->
</map>

<map name="c_5" id="c_5">
    <area rel="c520" href="#" shape="poly" coords="127,452,362,453,362,465,379,465,379,462,407,462,407,520,389,520,387,548,127,548" />	<!-- C520 -->
    <area rel="c501" href="#" shape="poly" coords="1171,227,1197,315,1328,270,1301,182" />												<!-- C501 -->
</map>

<map name="d_1" id="d_1">
    <area rel="d111" href="#" shape="poly" coords="792,265,862,270,862,311,793,311" />					<!-- D111 -->
    <area rel="d110" href="#" shape="poly" coords="867,270,867,310,924,311,923,275" />					<!-- D110 -->
    <area rel="d109" href="#" shape="poly" coords="927,275,994,280,990,350,927,350" />					<!-- D109 -->
    <area rel="d108" href="#" shape="poly" coords="927,353,927,395,985,395,988,354" />					<!-- D108 -->
    <area rel="d107" href="#" shape="poly" coords="927,399,927,439,983,439,985,399" />					<!-- D107 -->
    <area rel="d106" href="#" shape="poly" coords="927,442,982,442,980,485,927,485" />					<!-- D106 -->
    <area rel="d105" href="#" shape="poly" coords="927,490,980,489,977,537,927,538" />					<!-- D105 -->
    <area rel="d104" href="#" shape="poly" coords="927,542,976,541,973,592,927,593" />					<!-- D104 -->
	<area rel="d112" href="#" shape="poly" coords="792,316,844,315,846,358,792,358" />					<!-- D112 -->
    <area rel="d113" href="#" shape="poly" coords="792,364,845,363,844,425,792,425" />					<!-- D113 -->
    <area rel="d103" href="#" shape="poly" coords="787,487,840,487,841,548,786,548" />					<!-- D103 -->
    <area rel="d101" href="#" shape="poly" coords="660,487,739,486,739,548,659,548" />					<!-- D101 -->
    <area rel="d102" href="#" shape="poly" coords="722,583,842,582,842,675,725,676" />					<!-- D102 -->
    <area rel="d114" href="#" shape="poly" coords="724,679,842,677,842,770,723,771" />					<!-- D114 -->
    <area rel="d115" href="#" shape="poly" coords="743,774,842,774,842,867,771,866,768,803,743,801" />	<!-- D115 -->
</map>

<map name="e_1" id="e_1">
    <area rel="e126" href="#" shape="poly" coords="173,145,199,130,211,150,187,165" />				<!-- E126 -->
    <area rel="e127" href="#" shape="poly" coords="204,127,239,184,289,161,253,97" />				<!-- E127 -->
    <area rel="e131" href="#" shape="poly" coords="263,224,305,295,352,267,311,196" />				<!-- E131 -->
    <area rel="e119" href="#" shape="poly" coords="307,298,342,357,389,329,354,269" />				<!-- E119 -->
    <area rel="e121" href="#" shape="poly" coords="357,384,382,425,429,396,405,356,404,354" />		<!-- E121 -->
    <area rel="e103" href="#" shape="poly" coords="607,274,609,371,696,370,694,274" />				<!-- E103 -->
    <area rel="e104" href="#" shape="poly" coords="697,274,699,370,787,369,783,272" />				<!-- E104 -->
    <area rel="e105" href="#" shape="poly" coords="787,272,875,270,877,366,791,367" />				<!-- E105 -->
    <area rel="e106" href="#" shape="poly" coords="878,270,963,269,965,364,879,366" />				<!-- E106 -->
    <area rel="e107" href="#" shape="poly" coords="965,269,968,365,1055,362,1053,268" />			<!-- E107 -->
    <area rel="e108" href="#" shape="poly" coords="1056,268,1057,362,1188,362,1186,267" />			<!-- E108 -->
	<area rel="e114" href="#" shape="poly" coords="963,255,971,247,996,247,997,262,964,263" />		<!-- E114 -->
	<area rel="e113" href="#" shape="poly" coords="999,246,999,262,1030,262,1029,244" />			<!-- E113 -->
    <area rel="e112" href="#" shape="poly" coords="1038,244,1039,261,1072,260,1073,252,1064,243" />	<!-- E112 -->
</map>

<map name="e_2" id="e_2">
    <area rel="e202" href="#" shape="poly" coords="558,299,557,360,639,359,639,300" />			<!-- E202 -->
    <area rel="e234" href="#" shape="poly" coords="359,261,397,330,404,326,411,324,416,321,423,318,431,315,438,314,443,312,452,310,461,308,467,306,476,306,483,306,492,305,490,226,468,226,444,231,421,237,402,242,385,249" /> <!-- E234 -->
	<area rel="e209" href="#" shape="poly" coords="659,265,679,262,760,263,761,286,776,286,777,360,691,360,691,328,695,322,669,295,644,294,644,278,658,278" />		<!-- E209 -->
    <area rel="e210" href="#" shape="poly" coords="783,263,913,264,913,362,783,361" />			<!-- E210 -->
    <area rel="e211" href="#" shape="poly" coords="918,266,919,363,1048,363,1047,266" />		<!-- E211 -->
    <area rel="e212" href="#" shape="poly" coords="1054,266,1185,266,1187,362,1053,362" />		<!-- E212 -->
    <area rel="e214" href="#" shape="poly" coords="956,246,955,256,996,255,996,239,963,238" />	<!-- E214 -->
    <area rel="e215" href="#" shape="poly" coords="998,239,998,256,1027,257,1027,240" />		<!-- E215 -->
    <area rel="e216" href="#" shape="poly" coords="1035,240,1036,257,1067,258,1068,248,1059,240" />	<!-- E216 -->
</map>

<map name="e_3" id="e_3">
    <area rel="eArcticCivil" href="#" shape="poly" coords="791,265,791,363,923,364,922,264" />			<!-- ARCTIC CIVIL ENGINEERING -->
    <area rel="eArcticPower" href="#" shape="poly" coords="1067,267,1200,265,1200,366,1066,364" />		<!-- ARCTIC POWER -->
</map>
</body>
</html>