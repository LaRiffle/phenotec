<?php
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
      	<title>PhenoTec Research & Consuting</title>
       	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="index5.css" />
	<link rel="shortcut icon" type="image/x-icon" href="gas_icone.ico" />
   </head>
   <!-- Design et réalisation par Théo Leffyr -->
	<body>
	<div id="show_article"></div>
	<div id="head">
	<div style="height: 4px; background-color: white;"></div>
	<div id="divFirstTable"  onclick="changePage('index');">
		<table class="topTable"><tr>
		<td id="title">Phenotec <span style="font-size: 28px; margin-left: 73px; position: relative; bottom: 4px;">Research Service & Consulting</span></td>
		</tr></table>
	</div>

		<div id="head_menu" style="margin-top: 0px; height: 183px;">
		</div>
		<?php
		$dirname = './img/head_img/';
		$dir = opendir($dirname);
		$maxHead = 0;
		while($file = readdir($dir)) {
		if($file != '.' && $file != '..' && !is_dir($dirname.$file))
		{
			$maxHead++;
			$pathArray[$maxHead] = $dirname.$file;
		}
		}
		closedir($dir);
		$num = rand(0, $maxHead);
		?>
		<div class="imageHead" id="imageHead"></div><div class="imageHeadCover" id="imageHeadCover"></div><img class="screenShock" id="screenShock" src="img/screenShock.jpg">
	</div>
	<?php
	function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)
		{
		   //Test1: fichier correctement uploadé
			 if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
		   //Test2: taille limite
			 if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
		   //Test3: extension
			 $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
			 if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
		   //Déplacement
			 return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
		}
	if(isset($_POST['title']) AND isset($_POST['domain']) AND isset($_POST['more']) AND isset($_POST['simple']))
	{
		$nom_image = $_POST['title'].'.jpg';
		if($_FILES['img2']['name'] == '') {
			$nom_image = 'none';
			$upload1 = 1;
		} else {
		$upload1 = upload('img2','img/'.$_POST['title'].'.jpg',FALSE, array('png','gif','jpg','jpeg')); }
		if ($upload1) {
		$order ='0';
		$date = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').'';
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=db421923256.db.1and1.com;dbname=db421923256', 'dbo421923256', 'JC.D@24next', $pdo_options);
		$bdd->exec('INSERT INTO  `phenotec_content` (  `date` ,  `order` ,  `domain` ,  `title` ,  `simple` ,  `more` ,  `img` ,  `right` ) VALUES (\''.$date.'\',  \''.$order.'\',  \''.$_POST['domain'].'\',  \''.$_POST['title'].'\',  \''.$_POST['simple'].'\',  \''.$_POST['more'].'\', \''.$nom_image.'\', \''.$_POST['right'].'\')');
		?><script> alert("Article added !");</script><?php
		} else {
		?><script> alert("There might be a mistake in the picture upload...");</script><?php
		}
	}
	if(isset($_GET['write'])) {
	if(isset($_POST['name']) AND isset($_POST['statut']) AND isset($_POST['object']) AND isset($_POST['text'])
	AND $_POST['name'] != '' AND $_POST['statut'] != '' AND $_POST['text'] != '')
	{
		$date = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').'';
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=db421923256.db.1and1.com;dbname=db421923256', 'dbo421923256', 'JC.D@24next', $pdo_options);
		$bdd->exec('INSERT INTO  `message` (  `date` ,  `name` ,  `statut` ,  `object` ,  `text`) VALUES (\''.$date.'\',  \''.$_POST['name'].'\',  \''.$_POST['statut'].'\',  \''.$_POST['object'].'\',  \''.$_POST['text'].'\')');
		?>
		<script>
		var stop_show2 = 0;
		var stop_shut2 = 0;
		setTimeout(function() {
			if(!stop_show2) {
			document.getElementById('show_article').innerHTML = '<div class="show_article_bg" onclick="shutWindow();"><div class="show_info_window" onclick="stop_shut2 = \'1\';" style="background-color: #EEEEEE; box-shadow: 0px 10px 12px #9999DD;">'
						+ '<article class="part" style="border: none;">'
						+'<div class="table" id="table"><table><tr><td><a style="color: #333333;" onclick="shutWindow();">Close</a></td></tr></table></div>'
						+'<h2 style="margin-left: 2.4%; margin-top: 20px; font-size: 2.2em;" id="title">The Phenotec Website</h2>'
						+'<p style="font-size: 1.2em;">Your message has been correctly sent.<br />Thanks for your collaboration.</p></article>'
						+ '</div></div>';
			} else {
			stop_show2 = 0; }
			}, 100);
		</script>
		<?php
	}
	else {
	?>
		<script>
		var name = "<?php if(isset($_POST['name'])) {echo $_POST['name'];} else {echo '';} ?>";
		var object = "<?php if(isset($_POST['object'])) {echo $_POST['object'];} else {echo '';} ?>";
		var text = "<?php if(isset($_POST['text'])) {echo $_POST['text'];} else {echo '';} ?>";
		setTimeout(function() {
			changePage('contact');
			}, 100);
		var stop_show2 = 0;
		var stop_shut2 = 0;
		setTimeout(function() {
			if(!stop_show2) {
			document.getElementById('show_article').innerHTML = '<div class="show_article_bg" onclick="shutWindow();"><div class="show_info_window" onclick="stop_shut2 = \'1\';" style="background-color: #EEEEEE; box-shadow: 0px 10px 12px #9999DD;">'
						+ '<article class="part" style="border: none;">'
						+'<div class="table" id="table"><table><tr><td><a style="color: #333333;" onclick="shutWindow();">Close</a></td></tr></table></div>'
						+'<h2 style="margin-left: 2.4%; margin-top: 20px;" id="title">The Phenotec Website</h2>'
						+'<p style="font-size: 1.2em;">Some required informations are missing. Please try again.</p>'
						+'<div style="font-size: 20px;"><form method="post" action="index4.php?write" style="margin-left: 20px;">'
						+'<span style="color: black; font-weight: bold;">Name : <input type="text" name="name" id="name" value="'+ name +'" size="12" maxlength="255" style="font-size: 20px; margin-right: 50px;"/> '
						+'You are <select name="statut" id="statut" style="font-size: 20px;">'
						+'<option value="entreprise">an entreprise</option>'
						+'<option value="scientific">a scientific</option>'
						+'<option value="visitor">a visitor</option>'
						+'<option value="troll">a terrible terrorist !</option>'
						+'</select>'
						+'<div style="margin-top: 5px;"><span style="font-size: 1em; color: black;font-weight: bold;">Purpose : </span>'
						+'<input type="text" name="object" id="object" value="'+ object +'" size="40" style="font-size: 20px;" maxlength="255"/></div><br />'
						+'<textarea name="text" style="font-size: 20px; margin-top: 6px; margin-bottom: 4px;" id="text" cols="55" rows="5">'+ text +'</textarea><br />'
						+'<input type="submit" name="submit" value="Send" style="font-size: 20px; margin-top: 5px;" />'
						+'</form></div>'
						+'</article>'
						+ '</div></div>';
			} else {
			stop_show2 = 0; }
			}, 100);
		</script>
		<?php
	}
	}
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host=db421923256.db.1and1.com;dbname=db421923256', 'dbo421923256', 'JC.D@24next', $pdo_options);
	?>
		<div id="bodytitle2">
		<table class="topTable"><tr>
		<td id="main_message" style="text-transform: capitalize;">Welcome</td>
		<td class="menu_title2" id="about us" onclick="changePage('about us');"><a href="#">About us</a></td>
		<td class="menu_title2" id="services" onclick="changePage('services');"><a href="#">Services</a></td>
		<td class="menu_title2" id="news" onclick="changePage('news');"><a href="#">News</a></td>
		<td class="menu_title2" id="partners" onclick="changePage('partners');"><a href="#">Partners</a></td>
		<td class="menu_title2" id="contact" onclick="changePage('contact');"><a href="#">Contact</a></td>
		</tr></table>
		</div>
		</div>
		<div id="body1">
		</div>
		<section id="body">
	<?php
	$page = array ('index', 'about us', 'our vision', 'news', 'services', 'partners', 'contact');
	foreach($page as $domain)
	{
		echo'<section id="domain-'.$domain.'" style="display: none;">';
		$i = 0;

		$reponse = $bdd->query('SELECT * FROM phenotec_content WHERE `domain` = \''.$domain.'\'ORDER BY  `order`');
		while($donnees = $reponse->fetch()) {
			if($donnees['visibility'] == '1')
				$i++;
		}
		if($domain == 'news') {
		if($i >= 5 AND $i <= 7) {

			$width = intval((935-20*$i)/$i);
			echo '<div class="previewHeading" style="height: '.$width.'px;">';
			$reponse = $bdd->query('SELECT * FROM phenotec_content WHERE `domain` = \''.$domain.'\'ORDER BY  `order`');
			while($donnees = $reponse->fetch()) {
			echo'<div class="icone5" style="background-image: url(\'img/'.$donnees['img'].'\'); width: '.$width.'px; height: '.$width.'px;">';
			echo'<div class="text_icone2" style="font-size: ';
			if($i == 5) echo'20px;';
			if($i == 6) {echo'20px;'; $width = $width-1;}
			if($i == 7) {echo'17px;'; $width = $width-2;};
			$margin = 41-(4*$i);
			if($donnees['titleS'] != '0')
				echo' margin-top: '.$margin.'px;"><a href="#'.$donnees['title'].'" style="color: #333333; font-weight: bold;">'.$donnees['titleS'].'</a></div></div>';
			else
				echo' margin-top: '.$margin.'px;"><a href="#'.$donnees['title'].'" style="color: #333333; font-weight: bold;">'.$donnees['title'].'</a></div></div>';
			}
			echo'</div>';
		}
		}
		$reponse = $bdd->query('SELECT * FROM phenotec_content WHERE `domain` = \''.$domain.'\'ORDER BY  `order`');
		while($donnees = $reponse->fetch())
		{
			if($donnees['visibility'] == '1') {
			echo '<div>';
			echo'<article class="part" id=\''.$donnees['title'].'\' style="';
			// if($donnees['right'] AND $donnees['img'] != 'undefined.jpg')
				//echo 'text-align: right;';
			if($donnees['border'] == '0' OR $donnees['border'] == '')
				echo 'border: none; padding: 5px; padding-left: 15px; padding-right: 15px;';
			if($domain == 'partners')
				echo 'border: none; border-radius: 0px; border-bottom: 1px #444444 solid;';
			echo '" >';
			if($donnees['img'] != 'undefined.jpg') {
				if($donnees['right'] != '0') {
					if($domain == 'partners')
						echo'<img class="imageRight" src="img/'.$donnees['img'].'" style="width: 25%;">';
					else
						echo'<img class="imageRight" src="img/'.$donnees['img'].'">';
				} else {
					if($domain == 'partners')
						echo'<img class="imageLeft" src="img/'.$donnees['img'].'" style="width: 25%;">';
					else
						echo'<img class="imageLeft" src="img/'.$donnees['img'].'">';
				}
			}
			echo'<h2 style="';
			if($donnees['img'] == 'undefined.jpg')
				echo'clear: both;';
			echo'">'.$donnees['title'].'</h2>';
			echo'<p class="normalP">'.$donnees['simple'].'';
			$donnees['more'] = trim($donnees['more']);
			if($donnees['more'] != "") {
				if($donnees['publication'] != "" AND $donnees['publication'] != "none")
					echo '<a onclick="seemorePubli(this);" class="more"> Learn More...</a></p>';
				else
					echo '<a onclick="seemore(this);" class="more"> Learn More...</a></p>';
				echo '<p style="display:none">'.$donnees['more'].'</p>'; }
			if($donnees['publication'] != "" AND $donnees['publication'] != "none")
				echo'<p><a href="'.$donnees['publication'].'" target="_blank"><span class="like_a_link">See further information in this publication.</span></a></p>';
			echo'<p style="clear: both;"></p></article>';
			echo'<article class="part" style="display: none;';
			if($donnees['border'] == '0' OR $donnees['border'] == '')
				echo 'border: none; padding: 5px; padding-left: 15px; padding-right: 15px;';
			echo '" >';
			if($donnees['img'] != 'undefined.jpg') {
				if($donnees['right'] != '0')
					echo'<img class="imageRight" src="img/'.$donnees['img'].'">';
				else
					echo'<img class="imageLeft" src="img/'.$donnees['img'].'">';
			}
			echo'<h2>'.$donnees['title'].'</h2>';
			echo'<p class="normalP">'.$donnees['simple'].'';
			if($donnees['more'] != "") {
				if($donnees['publication'] != "" AND $donnees['publication'] != "none")
					echo '<a onclick="seemorePubli(this);" class="more"> Learn More...</a></p>';
				else
					echo '<a onclick="seemore(this);" class="more"> Learn More...</a></p>';
				echo '<p style="display:none">'.$donnees['more'].'</p>'; }
			if($donnees['publication'] != "" AND $donnees['publication'] != "none")
				echo'<p><a href="'.$donnees['publication'].'" target="_blank"><span class="like_a_link">See further information in this publication.</span></a></p>';
			echo'<p style="clear: both;"></p></article>';
			echo'</div>';
		}}
		echo'</section>';
	}
	?>
		<article class="part" style="border: none; height: 100px; margin-left: 30px;">
			<div class="icone2" style="background-image: url('skin_1024/006.jpg');" onclick="changePage('index');"><div class="text_icone"><a href="#" style="color: #333333;">Home</a></div></div>
			<div class="icone2" style="background-image: url('skin_1024/007.jpg');" onclick="changePage('about us');"><div class="text_icone"><a href="#" style="color: #333333;">About us</a></div></div>
			<div class="icone2" style="background-image: url('skin_1024/008.jpg');" onclick="changePage('services');"><div class="text_icone"><a href="#" style="color: #333333;">Services</a></div></div>
			<div class="icone2" style="background-image: url('skin_1024/009.jpg');" onclick="changePage('news');"><div class="text_icone"><a href="#" style="color: white;">News</a></div></div>
			<div class="icone2" style="background-image: url('skin_1024/010.jpg');" onclick="changePage('partners');"><div class="text_icone"><a href="#" style="color: #333333;">Partners</a></div></div>
			<div class="icone2" style="background-image: url('skin_1024/008.jpg');" onclick="changePage('contact');"><div class="text_icone"><a href="#" style="color: #333333;">Contact</a></div></div>
			<div class="icone2" style="background-image: url('skin_1024/006.jpg');" onclick="changePage('the site');"><div class="text_icone"><a href="#" style="color: #333333;">Website</a></div></div>
		</article>
		<div style="clear: both;"></div>
	</section>
	<script language="javascript">
		var detect = navigator.userAgent.toLowerCase();
var OS,browser,version,total,thestring;

if (checkIt('konqueror'))
{
	browser = "Konqueror";
	OS = "Linux";
}
else if (checkIt('safari')) browser = "Safari"
else if (checkIt('omniweb')) browser = "OmniWeb"
else if (checkIt('opera')) browser = "Opera"
else if (checkIt('webtv')) browser = "WebTV";
else if (checkIt('icab')) browser = "iCab"
else if (checkIt('msie')) browser = "Internet Explorer"
else if (!checkIt('compatible'))
{
	browser = "Netscape Navigator"
	version = detect.charAt(8);
}
else browser = "An unknown browser";

if (!version) version = detect.charAt(place + thestring.length);

if (!OS)
{
	if (checkIt('linux')) OS = "Linux";
	else if (checkIt('x11')) OS = "Unix";
	else if (checkIt('mac')) OS = "Mac"
	else if (checkIt('win')) OS = "Windows"
	else OS = "an unknown operating system";
}

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}
	if(browser == "Netscape Navigator") // CSS CORRECTION
	{
		document.getElementById('title').style.position="absolute";
		document.getElementById('title').style.top ="0px";
	}
	if(browser == "Internet Explorer" && version <= 8) // CSS CORRECTION
	{
		alert("Internet Explorer is an unbearable navigator, that has stayed in the XXth century.\nPlease adopt something more decent to see this website correctly.")
	}



	var domainSelected = 'index';
	var stop_show = 0;
	var stop_shut = 0;
	document.getElementById('domain-'+ domainSelected).style.display = 'block';
	function shutArticle(type, domain, title)
	{
		setTimeout(function() {
		if(!stop_shut) {
		document.getElementById('show_article').innerHTML ='';
		} else {
		stop_shut = 0; }
		}, 100);
	}
	function shutWindow(type, domain, title)
	{
		setTimeout(function() {
		if(!stop_shut2) {
		document.getElementById('show_article').innerHTML ='';
		} else {
		stop_shut2 = 0; }
		}, 100);
	}
	function changePage(domain) {
		if(domain == 'the site') {
			setTimeout(function() {
			if(!stop_show) {
			document.getElementById('show_article').innerHTML = '<div class="show_article_bg" onclick="shutArticle();"><div class="show_article_window" onclick="stop_shut = \'1\';" style="background-color: #EEEEEE; box-shadow: 0px 10px 12px #9999DD;">'
						+ '<article class="part" style="border: none;">'
						+'<div class="table" id="table"><table><tr><td><a style="color: #333333;" onclick="window.top.window.shutArticle();">Close</a></td></tr></table></div>'
						+'<h2 style="margin-left: 2.4%; margin-top: 40px;" id="title">The Phenotec Website</h2>'
						+'<p style="margin-left: 50px;"><br />This is the new presentation of our website issued in July 2012.<br />Thank\'s to the collaboration of the Phenotec team, this new website has been brought up.<br />Design & creation by Théo Ryffel, with the help of external collaboration for the photos.<br />This is not a professional creation, and it might remain some possible failures, security breaches, or unexpectable damages in visiting it. PhenoTec is not responsible.<br /> There is no privacy policy, no terms of use, nevertheless,<br />All information here published by PhenoTec\'s team or another person on Phenotec.com remains PhenoTec\'s property and cannot be copy or sell.<br />Thanks for your cooperation.<br/>If you have any question, or remark, you can send us a message through the \'Contact us\' section.</p></article>'
						+ '</div></div>';
			} else {
			stop_show = 0; }
			}, 100);
	} else {
		document.getElementById('domain-'+ domainSelected).style.display = 'none';
		document.getElementById('domain-'+ domain).style.display = 'block';
		if(domainSelected != "index") {
			document.getElementById(domainSelected).className = "menu_title2"; }
		if(domain != "index")
			document.getElementById(domain).className = "selected";
		domainSelected = domain;
		if(domain != "index")
			document.getElementById('main_message').textContent = domain;
		else
			document.getElementById('main_message').textContent = 'Welcome';
		}
	}
	function seemore(article)
	{
		var para = article.parentNode;
		para.removeChild(para.lastChild);
		para.innerHTML += '<span><br />' + para.parentNode.lastChild.previousSibling.innerHTML + '</span>';
		para.parentNode.style.overflow = 'hidden';
		para.innerHTML += '<img src="skin_1024/reduce.jpg" class="reduce2" onclick="reduce(this)">';
	}
	function seemorePubli(article)
	{
		var para = article.parentNode;
		para.removeChild(para.lastChild);
		para.innerHTML += '<span><br />' + para.parentNode.lastChild.previousSibling.previousSibling.innerHTML + '</span>';
		para.parentNode.style.overflow = 'hidden';
		para.innerHTML += '<img src="skin_1024/reduce.jpg" class="reduce2" onclick="reducePubli(this)">';
	}
	function reduce(object)
	{
		var para = object.parentNode;
		var globalCadre = para.parentNode.parentNode;
		var para2 = globalCadre.lastChild.cloneNode(true);
		globalCadre.appendChild(para2);
		globalCadre.replaceChild(globalCadre.lastChild, globalCadre.firstChild);
		globalCadre.firstChild.style.display = "block";
	}
	function reducePubli(object)
	{
		var para = object.parentNode;
		var globalCadre = para.parentNode.parentNode;
		var para2 = globalCadre.lastChild.cloneNode(true);
		globalCadre.appendChild(para2);
		globalCadre.replaceChild(globalCadre.lastChild, globalCadre.firstChild);
		globalCadre.firstChild.style.display = "block";
	}

	var imageHeadCover = document.getElementById('imageHeadCover');
	(function() {
	setTimeout(function() {
		var screenShock = document.getElementById('screenShock');
		screenShock.parentNode.removeChild(screenShock);
		var opacity = 1;
		imageHeadCover.style.backgroundColor = 'rgba(237, 237, 237, 1)';
		changeTopDiapo();
	}, 5000);
	})();
	var intervalID4 = setInterval(function() {
	var opacity = 0;
	var intervalID6bis = setInterval(function() {
		if(opacity < 1) {
		imageHeadCover.style.backgroundColor = 'rgba(237, 237, 237, '+opacity+')';
		opacity += 0.05;
		}
		else {
		clearInterval(intervalID6bis);
		opacity = 1;
		changeTopDiapo();
		}
			}, 35);
	}, 10000);
  function changeTopDiapo() {//diaporama haut
   <?php
   echo 'var tableau = new Array(';
   $prems = 1;
	foreach($pathArray as $value) {
	if($prems) {
	$prems = 0;
	if(is_int($value))
		echo $value;
	  else
		echo '"'.$value.'"';
	}
	else {
	  if(is_int($value))
		echo ','.$value;
	  else
		echo ',"'.$value.'"'; }
	}
	echo '); var max = '.$maxHead.';';?>
		document.getElementById('imageHead').innerHTML = "";
		var path;
		var maxWidth = document.getElementById('imageHead').offsetWidth - 50;
		var width = 0;
		var imgWidthAdapted;
		var nb;
		var src;
		var id = 0;
		var rapport;
		var j = 0;
		var Img = tableau;
		for (var i = 0, c = Img.length; i < c; i++) {
			src = tableau[i];
			Img[i] = new Image();
			Img[i].src = src;
			rapport = 163/Img[i].height
			Img[i].height = '163';
			Img[i].width = Math.floor(Img[i].width*rapport);

			// alert(Img[i].height+'*'+Img[i].width);
			// alert(Img[i].width);
			// alert(src);
			// alert(tableau[i]);
			// tableau[i] = src;
			// alert(tableau[i]);
			// document.getElementById('imageHead').innerHTML += Img[i].src + '<br />';
		}
		while(width < maxWidth && j<Img.length*3) {
			j++;
			nb = Math.floor(Math.random() * <?php echo $maxHead; ?>);
			// document.getElementById('imageHead').innerHTML += 'test de limage : '+nb;
			if(Img[nb] != "" && Img[nb].width != 0) { //secu permettant de supprimer des valeurs dans le tableau en les rendant inopérantes ET PREVENIR LES INCOMPATIBILITE SUR IMAGE
				width += Img[nb].width;
				// document.getElementById('imageHead').innerHTML += ' totalwidth = '+ width;
				if(width < maxWidth) {
					document.getElementById('imageHead').innerHTML += '<img id="image'+id+'" src="'+Img[nb].src+'" style="height: 163px; margin-right: 8px; border-radius: 8px;">';
					id++;
				}
				else {
					width -= Img[nb].width;
					// document.getElementById('imageHead').innerHTML += '<br />picture declined, total width ='+ width+'<br />';
				}
				Img[nb] = ""; //permet "d'enlever" l'image trop grande non-affichée ou celle affichée
			}
			else {
			// document.getElementById('imageHead').innerHTML += '<br />image inexistante<br />';
			}
		}
		imgWidthAdapted = maxWidth/width;
		// alert(maxWidth+'/'+width+' = '+imgWidthAdapted);
		for (var i = 0; i < id; i++) {
			document.getElementById('image'+i).style.width =  Math.floor(document.getElementById('image'+i).offsetWidth * imgWidthAdapted) +'px';
		}
		var opacity = 1;
		var intervalID5bis = setInterval(function() {
			if(opacity > 0) {
			imageHeadCover.style.backgroundColor = 'rgba(237, 237, 237, '+opacity+')';
			// alert(opacity);
			opacity -= 0.05;
			}
			else {
			clearInterval(intervalID5bis);
			opacity = 0;
			}
		}, 35);
	}
	</script>
	<div style="height: 7px; background-color: #555555; width: 100%;"></div>
	<img src="skin_1024/bottom.jpg" style="width: 100%; margin-bottom: -4px;">
	<div style="height: 7px; background-color: #555555; width: 100%;"></div>
	</body>
	</html>
