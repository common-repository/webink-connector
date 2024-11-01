<?php
/*
Version: 1.0
Author: WebINK
Author URI: http://www.webink.com
License: GPL2
*/

//////////////////////////////////////////////////////
// CSS AND LINK FUNCTIONS
//////////////////////////////////////////////////////
function buildSelectorCSSLink(){

	$result = '<link href="'.plugin_dir_url(__FILE__).'WebINK-css.php?css=selector'.'" rel="stylesheet" type="text/css"/>';
	echo $result;
}

function buildUserFontLinks(){
	$projects = getProject('','');
	$allFontLinks = "";
	$flOpen = '<link href="http://fnt.webink.com/wfs/webink.css?project=';
	$flClose = '" rel="stylesheet" type="text/css"/>';
	if($projects)
	{
		foreach($projects as $project)
		{
			if($project->Enabled == 1)
			{
				$projectfonts = getProjectFonts('',$project->GUID);
				if($projectfonts)
				{
					$fontCount = 1;
					foreach($projectfonts as $font)
					{
						if($font->Exclude == 0)
						{
							if($fontCount % 20 == 1){
								$allFontLinks .=$flOpen.$project->GUID.'&fonts=';
							}
							$allFontLinks .= $font->MasterGUID.':family='.$font->PSName;
							if($font->Weight != "" && $font->Weight != NULL) $allFontLinks .= ':weight='.$font->Weight;
							if($font->Style != "" && $font->Style != NULL) $allFontLinks .= ':style='.$font->Style;
							$allFontLinks .= ",";
							if($fontCount % 20 == 0 ){
								if(!endsWith($allFontLinks, $flClose)){
									$allFontLinks .= $flClose;
								}
							}
							$fontCount++;
						}
					}
					if(!endsWith($allFontLinks, $flClose)){
						$allFontLinks .= $flClose;
					}
				}
			}
			
		}
	}
	echo ($allFontLinks);
}


function getSelectorCSS(){
	$projects = getProject('','');
	$result = "";
	if($projects)
	{
		foreach($projects as $project)
		{
			if($project->Enabled == 1)
			{
				$projectfonts = getProjectFonts('',$project->GUID);
				if($projectfonts)
				{
					foreach($projectfonts as $font)
					{
						if($font->Exclude == 0 && $font->Selector != "")
						{
							$aSelectors = explode(";",$font->Selector);
							if(count($aSelectors)>0){
								foreach($aSelectors as $selector)
								{
									if(trim($selector) != ""){
										$result .= $selector ." {font-family: '";
										$result .= $font->PSName ."'";
										if($font->Fallback != "")
										{
											$result .= ",".stripslashes($font->Fallback);
										}
										$result .= ";} ";
									}
									
								}
							}
							
						}
					}	
				}
			}	
		}
	}
	return $result;
}

function getTinyMCEImportCSS(){
	$projects = getProject('','');
	$allFontLinks = "";
	if($projects)
	{
		$aProjects = array();
		foreach($projects as $project)
		{
			$pFontCount = 0;
			if($project->Enabled == 1)
			{
				$projectfonts = getProjectFonts('',$project->GUID);
				if($projectfonts)
				{
					//$projectFontLinks = '@import url("http://fnt.webink.com/wfs/webink.css?project=';
					$projectFontLinks = 'http://fnt.webink.com/wfs/webink.css?project=';
					$projectFontLinks .= $project->GUID.'&fonts=';
					foreach($projectfonts as $font)
					{
						if($font->Exclude == 0 && $font->TinyMCE == 1)
						{
							$pFontCount++;
							$projectFontLinks .= $font->MasterGUID.':family='.$font->PSName;
							if($font->Weight != "" && $font->Weight != NULL) $allFontLinks .= ':weight='.$font->Weight;
							if($font->Style != "" && $font->Style != NULL) $allFontLinks .= ':style='.$font->Style;
							$projectFontLinks .= ",";
							
						}
					}
					$projectFontLinks = rtrim($projectFontLinks, ",");
					//$projectFontLinks .= '"); ';
					
					
				}
			}
			if($pFontCount > 0){
				$allFontLinks .= $projectFontLinks;
				array_push($aProjects,$projectFontLinks);
			}
		}
	}
	//return $allFontLinks;
	
	return $aProjects;
	
}

function buildTinyMCEMenu(){
	$projects = getProject('','');
	$tinyFontArray = array("Andale Mono=andale mono,times;","Arial=arial,helvetica,sans-serif;","Arial Black=arial black,avant garde;","Book Antiqua=book antiqua,palatino;","Comic Sans MS=comic sans ms,sans-serif;","Courier New=courier new,courier;","Georgia=georgia,palatino;","Helvetica=helvetica;","Impact=impact,chicago;","Symbol=symbol;","Tahoma=tahoma,arial,helvetica,sans-serif;","Terminal=terminal,monaco;","Times New Roman=times new roman,times;","Trebuchet MS=trebuchet ms,geneva;","Verdana=verdana,geneva;");
	$tinyText = "";
	if($projects)
	{
		foreach($projects as $project)
		{
			if($project->Enabled == 1)
			{
				$projectfonts = getProjectFonts('',$project->GUID);
				if($projectfonts)
				{
					foreach($projectfonts as $font)
					{
						$tempText = "";
						if($font->Exclude == 0 && $font->TinyMCE == 1)
						{
							$tempText.=$font->Name."=".$font->Name.",".$font->PSName;
							if($font->Fallback != "" && $font->Fallback != NULL) $tempText .=",".str_replace('"','\'',$font->Fallback);
							$tempText .=";";
							$tinyFontArray[] = $tempText;
						}
					}
				}
			}			
		}
	}
	sort($tinyFontArray);
	$tinyText = substr(implode("",$tinyFontArray), 0, -1);
	return $tinyText;
}

function buildAdminFontLinks(){
	$projects = getProject('','');
	$allFontLinks = "";
	$flOpen = '<link href="http://fnt.webink.com/wfs/webink.css?project=';
	$flClose = '" rel="stylesheet" type="text/css"/>';
	if($projects)
	{
		foreach($projects as $project)
		{
			
			$projectfonts = getProjectFonts('',$project->GUID);
			if($projectfonts)
			{
				$fontCount = 1;
				foreach($projectfonts as $font)
				{
					if($fontCount % 20 == 1){
						$allFontLinks .=$flOpen.$project->GUID.'&fonts=';
					}
					$allFontLinks .= $font->MasterGUID.':family='.$font->PSName;
					if($font->Weight != "" && $font->Weight != NULL) $allFontLinks .= ':weight='.$font->Weight;
					if($font->Style != "" && $font->Style != NULL) $allFontLinks .= ':style='.$font->Style;
					$allFontLinks .= ",";
					if($fontCount % 20 == 0 ){
						if(!endsWith($allFontLinks, $flClose)){
							$allFontLinks .= $flClose;
						}
					}
					$fontCount++;
				}
				if(!endsWith($allFontLinks, $flClose)){
					$allFontLinks .= $flClose;
				}
			}
			
		}
	}
	echo ($allFontLinks);
}

//////////////////////////////////////////////////////
// TypeDrawer FUNCTIONS
//////////////////////////////////////////////////////

function getTypeDrawers(){
	try{
		$options = get_option('WebINK_account-settings');
		if($options['password'] == '' || $options['email'] == ''){
			echo ('<div class="error"><p><strong>WebINK Error:</strong></p><p>No Email or Password saved.</p><p>Unable to connect to the WebINK Service.</p></div>');
		}else{
			$hash = md5($options['password']);
			$url = "http://acl.webink.com/ws/DrawerManager?wsdl"; //http://acl.webink.com/ws/DrawerManager?wsdl //http://conifer-stg.extensis.com/ws/DrawerManager?wsdl
			$client = new SoapClient($url, array("trace" => 1, "exception" => 0));

			$att_login = array("name" => "extensis.customer.accountlogin", "value" => $options['email']);
			$att_pw = array("name" => "credentials.esp.password", "value" => $hash);
			$creds = array ($att_login, $att_pw);
			$uuid = $client->login($creds);
			$typeDrawers = $client->getTypeDrawers($creds,array(),TRUE);
			//echo("<pre>");
			//var_dump($typeDrawers);
			//echo("</pre>");
			return $typeDrawers;
		}
	} catch(SoapFault $e){
		echo ('<div class="error"><p><strong>WebINK SOAP Error:</strong></p><p>'.$e->getMessage().'</p></div>');
	}
}

function refreshFonts(){
	try{
		$typeDrawers = getTypeDrawers();
		$projects = getProject('','');
		if($typeDrawers !== FALSE && $typeDrawers !== NULL){
			foreach($projects as $project){
				$projectFound = false;
				for($j=0;$j<count($typeDrawers->item);$j++) {
					if($typeDrawers->item[$j]->entityDef == "extensis.type-drawer"){
						if($project->GUID == $typeDrawers->item[$j]->guid){
							for($i=0;$i<count($typeDrawers->item[$j]->attributes);$i++) {
								if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.type-drawer.deleted"){
									if($typeDrawers->item[$j]->attributes[$i]->value == "false"){
										$projectFound = true;	
									}else{
										$projectFound = false;
									}
								}
							}
						}
					}
				}
				if(!$projectFound){
					deleteProjectFonts($projectguid);
					deleteProject($project->GUID);
				}
			}
		
			for($j=0;$j<count($typeDrawers->item);$j++) {
				if($typeDrawers->item[$j]->entityDef == "extensis.type-drawer"){
					$projectempty = 0;
					$projectdeleted = 0;
					$projectreferrers = "";
					$projectguid = $typeDrawers->item[$j]->guid;
					for($i=0;$i<count($typeDrawers->item[$j]->attributes);$i++) {
						if($typeDrawers->item[$j]->attributes[$i]->name == "esp.group.name"){
							$projectname = $typeDrawers->item[$j]->attributes[$i]->value;
						}
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.type-drawer.enabled"){
							if($typeDrawers->item[$j]->attributes[$i]->value == "false"){
									$projectenabled = 0;
								}else{
									$projectenabled = 1;
								}
						}
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.type-drawer.referrers"){
							
							if($typeDrawers->item[$j]->attributes[$i]->value != ""){
									$projectreferrers = $typeDrawers->item[$j]->attributes[$i]->value;	
							}
						}
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.type-drawer.deleted"){
							if($typeDrawers->item[$j]->attributes[$i]->value == "false"){
								$projectdeleted = 0;	
							}else{
								$projectdeleted = 1;
							}
						}
					}
					
					if($projectdeleted == 0){
						if(is_array($typeDrawers->item[$j]->relations)){
							$existingProject = getProjectFonts("", $projectguid);
							foreach($existingProject as $projectFont){
								$found = false;
								for($i=0;$i<count($typeDrawers->item[$j]->relations);$i++) {
									if($typeDrawers->item[$j]->relations[$i]->name == "extensis.type-drawer.webfont"){
										if($projectFont->FontGUID == $typeDrawers->item[$j]->relations[$i]->targetEntityGuid){
											$found = true;
										}
									}
								}
								if(!$found){
									deleteProjectFont($projectguid, $projectFont->FontGUID);
								}
							}
							for($i=0;$i<count($typeDrawers->item[$j]->relations);$i++) {
								
								if($typeDrawers->item[$j]->relations[$i]->name == "extensis.type-drawer.webfont"){
									if(count($existingProject)==0){
										insertProjectFont($projectguid,$typeDrawers->item[$j]->relations[$i]->targetEntityGuid);
										
									}else{
										insertProjectFont($projectguid,$typeDrawers->item[$j]->relations[$i]->targetEntityGuid);
										
									}
								}	
							}							
						}else{
							$projectempty = 1;
							deleteProjectFonts($projectguid);
						}
						
						insertProject($projectname,$projectguid,$projectenabled,$projectdeleted,$projectempty,$projectreferrers);
					}
				}
				if($typeDrawers->item[$j]->entityDef == "extensis.webfont"){
					$fontguid = $typeDrawers->item[$j]->guid;
					for($i=0;$i<count($typeDrawers->item[$j]->attributes);$i++) {
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.webfont.master-name"){
							$fontname = $typeDrawers->item[$j]->attributes[$i]->value;
						}
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.webfont.master-family"){
							$fontfamily = $typeDrawers->item[$j]->attributes[$i]->value;
						}
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.webfont.master-psname"){
							$fontpsname = $typeDrawers->item[$j]->attributes[$i]->value;
						}
						if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.webfont.master-guid"){
							$fontmasterguid = $typeDrawers->item[$j]->attributes[$i]->value;
						}
					}
					insertFont($fontname,$fontguid,$fontfamily,$fontpsname,$fontmasterguid);
				}
				
			}
			
		}else{
			foreach($projects as $project){
				deleteProject($project->GUID);
			}	
		}
		
		
		echo '<script language="javascript" type="text/javascript">var wise = document.getElementById("webINKSyncError"); wise.style.display = "none";</script>';
		echo '<script language="javascript" type="text/javascript">var refresh_url = document.location; window.location.href=refresh_url;</script>';
		
	} catch(SoapFault $e){
		echo ('<div class="error"><p><strong>WebINK Plugin Error:</strong></p><p>'.$e->getMessage().'</p></div>');
	}
}

function syncCheckFonts(){
	$result = TRUE;	
	$typeDrawers = getTypeDrawers();
	$errCount = 0;
	if($typeDrawers !== FALSE && $typeDrawers !== NULL){
		for($j=0;$j<count($typeDrawers->item);$j++) {
			if($typeDrawers->item[$j]->entityDef == "extensis.type-drawer"){
				
				$projectguid = $typeDrawers->item[$j]->guid;
				$existingProject = getProjectFonts("", $projectguid);
				$projectdeleted = 0;
				for($i=0;$i<count($typeDrawers->item[$j]->attributes);$i++) {

					if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.type-drawer.deleted"){
						if($typeDrawers->item[$j]->attributes[$i]->value == "false"){
							$projectdeleted = 0;	
						}else{
							$projectdeleted = 1;
						}
					}
				}
				if($projectdeleted == 0){
					if(property_exists($typeDrawers->item[$j], 'relations')){
						
						if(is_array($typeDrawers->item[$j]->relations)){
							
							if(count($existingProject) > 0){
								foreach($existingProject as $projectFont){
									$found = false;
									for($i=0;$i<count($typeDrawers->item[$j]->relations);$i++) {
										if($typeDrawers->item[$j]->relations[$i]->name == "extensis.type-drawer.webfont"){
											if($projectFont->FontGUID == $typeDrawers->item[$j]->relations[$i]->targetEntityGuid){
												$found = true;
											}
										}
									}
									if(!$found){
										$errCount++;
									}
								}
							}
							for($i=0;$i<count($typeDrawers->item[$j]->relations);$i++) {
								if($typeDrawers->item[$j]->relations[$i]->name == "extensis.type-drawer.webfont"){
									if(isProjectFontMissing($projectguid, $typeDrawers->item[$j]->relations[$i]->targetEntityGuid))
									{
										$errCount++;
									}	
								}					
							}
						}else{
							if(!isProjectEmpty($projectguid)){
								$errCount++;
							}
						}
					}
				}
			}
		}
		
		$projects = getProject('','');
		foreach($projects as $project){
			$projectFound = false;
			for($j=0;$j<count($typeDrawers->item);$j++) {
				if($typeDrawers->item[$j]->entityDef == "extensis.type-drawer"){
					if($project->GUID == $typeDrawers->item[$j]->guid){
						for($i=0;$i<count($typeDrawers->item[$j]->attributes);$i++) {
							if($typeDrawers->item[$j]->attributes[$i]->name == "extensis.type-drawer.deleted"){
								if($typeDrawers->item[$j]->attributes[$i]->value == "false"){
									$projectFound = true;	
								}else{
									$projectFound = false;
								}
							}
						}
						
					}
				}
			}
			if($projectFound == false){
				$errCount++;
			}
		}
		
	}else{
	
		$projects = getProject('','');
		foreach($projects as $project){
			$errCount++;
		}	
	}
	
	if($errCount == 0 ) $result = FALSE;
	return $result;
}

//////////////////////////////////////////////////////
// SQL DB FUNCTIONS
//////////////////////////////////////////////////////

function getProjectFonts($projectName, $projectGuid){
	global $wpdb;
	if($projectName != "" && $projectGuid == "")
	{
		$sql = "SELECT pf.ProjectGUID, pf.FontGUID, f.Name, f.Family, f.PSName, f.MasterGUID, pf.TinyMCE, pf.Fallback, pf.Selector, pf.Weight, pf.Style, pf.Exclude FROM ".$wpdb->prefix . TABLE_PREPEND."_project_fonts AS pf 
					INNER JOIN ".$wpdb->prefix . TABLE_PREPEND."_fonts AS f ON pf.FontGUID = f.GUID WHERE pf.ProjectGUID IN (SELECT GUID FROM ".$wpdb->prefix . TABLE_PREPEND."_projects WHERE Name = %s);";
		$psql = $wpdb->prepare($sql,$projectName);
	}
	elseif($projectName == "" && $projectGuid != "")
	{
		$sql = "SELECT pf.ProjectGUID, pf.FontGUID, f.Name, f.Family, f.PSName, f.MasterGUID, pf.TinyMCE, pf.Fallback, pf.Selector, pf.Weight, pf.Style, pf.Exclude FROM ".$wpdb->prefix . TABLE_PREPEND."_project_fonts AS pf 
					INNER JOIN ".$wpdb->prefix . TABLE_PREPEND."_fonts AS f ON pf.FontGUID = f.GUID WHERE pf.ProjectGUID = %s;";
		$psql = $wpdb->prepare($sql,$projectGuid);
	}
	else
	{
		$psql = "SELECT pf.ProjectGUID, pf.FontGUID, f.Name, f.Family, f.PSName, f.MasterGUID, pf.TinyMCE, pf.Fallback, pf.Selector, pf.Weight, pf.Style, pf.Exclude FROM ".$wpdb->prefix . TABLE_PREPEND."_project_fonts AS pf
					INNER JOIN ".$wpdb->prefix . TABLE_PREPEND."_fonts AS f ON pf.FontGUID = f.GUID ;";
		//$psql = $wpdb->prepare($sql, array());
	}
	$results = $wpdb->get_results($psql);
	return $results;
}

function getProject($projectName, $projectGuid){
	global $wpdb;
	if($projectName != "" && $projectGuid == "")
	{
		$sql = "SELECT * FROM ".$wpdb->prefix . TABLE_PREPEND."_projects 
			WHERE Name = %s ORDER BY Name ASC";
		$psql = $wpdb->prepare($sql,$projectName);
	}
	elseif($projectName == "" && $projectGuid != "")
	{
		$sql = "SELECT * FROM ".$wpdb->prefix . TABLE_PREPEND."_projects 
			WHERE GUID = %s ORDER BY Name ASC";
		$psql = $wpdb->prepare($sql,$projectGuid);
	}
	else
	{
		$psql = "SELECT * FROM ".$wpdb->prefix . TABLE_PREPEND."_projects ORDER BY Name ASC";
		//$psql = $wpdb->prepare($sql);
	}
	$results = $wpdb->get_results($psql);
	return $results;
}


function isProjectFontMissing($projectGuid, $fontGuid){
	global $wpdb;
	$result = FALSE;
	$wpdb->show_errors();
	$sql = "SELECT COUNT(*) as Count FROM ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
				WHERE ProjectGUID = %s AND FontGUID = %s;";
	$dbresult = $wpdb->get_row( $wpdb->prepare( 
	$sql,
	$projectGuid,
	$fontGuid) );
	if($dbresult->Count == "0") $result = TRUE;
	return $result;
}

function isProjectEmpty($projectGuid){
	global $wpdb;
	$result = FALSE;
	$wpdb->show_errors();
	$sql = "SELECT Empty FROM ".$wpdb->prefix . TABLE_PREPEND."_projects
				WHERE GUID = %s;";
	$dbresult = $wpdb->get_row( $wpdb->prepare( 
	$sql,
	$projectGuid) );
	if(strval($dbresult->Empty) == "1" ) $result = TRUE;
	return $result;
}

function insertProject($name,$guid,$enabled,$deleted,$empty,$referrers){
	global $wpdb;
	$wpdb->show_errors();
	$project = getProject('',$guid);
	
	if(!$project){
		$sql = "INSERT IGNORE INTO ".$wpdb->prefix . TABLE_PREPEND."_projects
					(Name,GUID,Enabled,Deleted,Hidden,Empty,Referrers)
					VALUES (%s, %s, %d, %d, %d, %d, %s)";
		$wpdb->query( $wpdb->prepare( 
		$sql, 
		$name, 
		$guid, 
		$enabled,
		$deleted,
		0,
		$empty,
		$referrers	
		) );
	}else{
		$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_projects SET Enabled = %d,
			Deleted = %d, Hidden = %d, Empty = %d, Referrers = %s
			 WHERE Name = %s AND GUID = %s";
		$wpdb->query( $wpdb->prepare( 
		$sql, 
		$enabled,
		$deleted,
		0,
		$empty,
		$referrers,
		$name, 
		$guid		
		) );
	}
	
	
}

function insertFont($name,$guid,$family,$psname,$masterguid){
	global $wpdb;
	$wpdb->show_errors();
	$sql = "INSERT IGNORE INTO ".$wpdb->prefix . TABLE_PREPEND."_fonts
				(GUID,Name,Family,PSName,MasterGUID)
				VALUES (%s, %s, %s, %s, %s)
			ON DUPLICATE KEY UPDATE 
			Name=%s, Family=%s, PSName=%s, MasterGUID=%s ";
	$wpdb->query( $wpdb->prepare( 
	$sql, 
    $guid, 
	$name, 
	$family,
	$psname,
	$masterguid,	
	$name, 
	$family,
	$psname,
	$masterguid	
	) );
}

function insertProjectFont($pguid, $fguid){
	global $wpdb;
	$sql = "INSERT IGNORE INTO ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
				(ProjectGUID,FontGUID)
				VALUES (%s, %s)";
	$wpdb->query( $wpdb->prepare( 
	$sql, 
    $pguid, 
	$fguid	
	) );
}

function deleteProject($pguid){
	global $wpdb;
	$sql = "DELETE FROM ".$wpdb->prefix . TABLE_PREPEND."_projects
			WHERE GUID = %s";
	$wpdb->query( $wpdb->prepare( 
	$sql, 
    $pguid	
	) );
}


function deleteProjectFont($pguid, $fguid){
	global $wpdb;
	$sql = "DELETE FROM ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
			WHERE ProjectGUID = %s AND FontGUID = %s";
	$wpdb->query( $wpdb->prepare( 
	$sql, 
    $pguid, 
	$fguid	
	) );
}

function deleteProjectFonts($pguid){
	global $wpdb;
	$sql = "DELETE FROM ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
			WHERE ProjectGUID = %s";
	$wpdb->query( $wpdb->prepare( 
	$sql, 
    $pguid	
	) );
}

//////////////////////////////////////////////////////
// UTILITY FUNCTIONS
//////////////////////////////////////////////////////

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    $start  = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}



?>