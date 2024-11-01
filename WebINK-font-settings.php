<?php
/*
Version: 1.0
Author: WebINK
Author URI: http://www.webink.com
License: GPL2
*/
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	
	if( isset($_POST['refresh']) && $_POST['refresh' ] == 'refresh' ) {
        refreshFonts();
	}
	
	if( isset($_POST['save']) && $_POST['save' ] == 'save' ) {
		global $wpdb;
		$wpdb->show_errors();
		foreach($_POST as $key => $value) {
			
			if(strpos( $key,'_' )) {
				$tempArray = explode("_",$key);
				if($tempArray[0]=='Project') {
					$intval = intval($value);
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_projects
								SET Enabled = %d WHERE GUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					$value, 
					$tempArray[1]					
					) );
				}
				if($tempArray[0]=='Selector') {
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
								SET Selector = %s WHERE ProjectGUID = %s
								AND FontGUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					$value, 
					$tempArray[1],
					$tempArray[2]					
					) );
				}
				if($tempArray[0]=='Fallback') {
					
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
								SET Fallback = %s WHERE ProjectGUID = %s
								AND FontGUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					urldecode($value), 
					$tempArray[1],
					$tempArray[2]					
					) );
				}
				if($tempArray[0]=='Weight') {
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
								SET Weight = %s WHERE ProjectGUID = %s
								AND FontGUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					$value, 
					$tempArray[1],
					$tempArray[2]					
					) );
				}
				if($tempArray[0]=='Style') {
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
								SET Style = %s WHERE ProjectGUID = %s
								AND FontGUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					$value, 
					$tempArray[1],
					$tempArray[2]					
					) );
				}
				if($tempArray[0]=='TinyMCE') {
					$intval = intval($value);
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
								SET TinyMCE = %d WHERE ProjectGUID = %s
								AND FontGUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					$intval, 
					$tempArray[1],
					$tempArray[2]					
					) );
				}
				if($tempArray[0]=='Exclude') {
					$intval = intval($value);
					$sql = "UPDATE ".$wpdb->prefix . TABLE_PREPEND."_project_fonts
								SET Exclude = %d WHERE ProjectGUID = %s
								AND FontGUID = %s;";
					$wpdb->query( $wpdb->prepare( 
					$sql, 
					$intval, 
					$tempArray[1],
					$tempArray[2]					
					) );
				}
			}
		}
		
		$tinyCSS = getTinyMCEImportCSS();
		update_option('WebINK_tinycss', $tinyCSS);
		$selectorCSS = getSelectorCSS();
		update_option('WebINK_selectorcss', $selectorCSS);
		
	}
	

?>
<style>
	.wiProject {border-collapse:collapse; width:98%; padding:5px; margin-bottom:30px;}
	.wiProject td{text-align:left; padding:5px;}
	.wiProject tr{border-bottom: 1px solid #000000;}
	.wiFontName{min-width:200px;width:auto; }
	.wiFontName span {margin-left:10px;}
	.wiFontSelector{min-width:200px;width:auto;  text-align:center; }
	.wiFontFallback{min-width:200px;width:auto;  text-align:center; }
	.wiFontFallback select{width:250px;}
	.wiFontTiny{width:70px;  text-align:center;}
	.wiFontExclude{width:30px;}
	.wiFont td input{width:100%;}
	.wiProjectOverview{background-color:#a6a6a6;color:#FFFFFF;}
	.wiProject td.wiProjectEnable{text-align:right;}
	.wiProjectName{font-size:16px;}
	.wiProjectHeaderRow td{background-color:#f9f9f9; font-weight:bold; border-top:2px solid #000;}
	.wiFont{background-color:#f9f9f9;}
	.wiProjectExpand {width:10px;}
	.wiProjectReferrers{background-color:#baebf9;}
	#wiHeaderTop{display:block; border-bottom: 1px solid #000000; width:75%; min-width:900px; height:auto; }
	#wiHeaderBox{float:left; width:135px; height:135px; margin-right:10px;}
	#wiHeaderText{display:block;float:left; width:auto; min-width:450px;}
	#wiHeaderText ol li{word-wrap: break-word; width:auto; width:450px;font-size:11px; padding:0px; margin:0px;}
	#wiAccountConnection{display:block; width:205px; height:130px; margin:0 0 10px 0; padding:5px 0 0 0; background-color:#FEFEFE; border:2px solid #e4e4e4; padding-left:10px; padding-right:10px; padding-bottom:5px; border-radius:10px;}
	#wiAccountConnection h2{margin:0px; padding:0px; padding-top:5px; margin-bottom:2px;}
	#wiAccountConnectionButton{float:right; }
	
	#wiFontArea{display:inline-block; clear:both;}
	#wiFormTable{padding-top:5px;}
	#wiFormTable input{width:136px;}
	#wiFontSettingsHeader{display:block; width:75%; min-width:900px; height:40px;}
	#wiFontSettingsHeader h2{float:left; margin-top:0px; padding-top:12px;}
	#wiFontSettingsRefresh{display:block;float:left;min-width:250px; margin-top:10px; margin-left:30px; vertical-align:middle; height:20px; line-height:20px;}
	#wiFontSettingsRefresh img{vertical-align:middle;}
	#wiFontSettings{width:75%; min-width:900px;}
	#wiConnect{display:block; width:400px; margin-right:30px;padding-top:10px; }
</style>
<script language="javascript" type="text/javascript">
	function enableFontMenu(pguid){
		var checked = 0;
		var unChecked = 0;
		jQuery(".TinyMCE_"+pguid).each(function(){
			if(jQuery(this).val() == "1" ){
				if( jQuery(this).is(':checked'))
				{
					checked++;
				}else{
					unChecked++;
				}
			}
		});
		if( unChecked == 0){
			jQuery(".TinyMCE_"+pguid).each(function(){
				jQuery(this).prop("checked",false);
			});
		}else {
			jQuery(".TinyMCE_"+pguid).each(function(){
				if(jQuery(this).val() == "1" ){
					jQuery(this).prop("checked",true);
				}
			});
		}
	}
	jQuery(document).ready( function() {
		var warning = false;
		jQuery('#wiProjectForm').change(function() {
			warning = true;
		});
		jQuery('#wiProjectFormSubmit').click(function() {
		   warning = false;
		});
		window.onbeforeunload = function() {
		  if (warning === true) {
			return "You have made changes on this page that you have not yet confirmed. If you navigate away from this page you will lose your unsaved changes";
		  }
		}
	});


</script>
	<div id="wiHeaderTop" align="right">
		<img id="wiHeaderBox" src="<?php echo(plugin_dir_url(__FILE__));?>/img/WebINKBox135x135.png"/>
		<div id="wiHeaderText" align="left">
			<h1>WordPress Connector</h1>
			<ol>
				<li>To get started add your WebINK account login information to the Account Connection area.</li>
				<li>Press the Refresh Project List below to retrieve your Projects from your account.</li>
				<li>Enable the fonts you want to appear in the font menu for posts.</li>
				<li>Remember to <strong>SAVE</strong> your settings and changes by clicking on the &quot;Save Font Settings&quot; button at the bottom of the page.</li>
			</ol>
		</div>
		<div id="wiAccountConnection" >
			<div align="left" style="width:100%;">
				<h2>Account Connection</h2>
				Enter your WebINK account login info
				
				<form method="post" action="options.php">
					<?php settings_fields('WebINK_account_settings'); ?>
					<?php $options = get_option('WebINK_account-settings'); ?>
					<table id="wiFormTable" cellspacing="1" cellpadding="1">
						
						<tr valign="top"><th scope="row" align="left">Email:</th>
							<td><input type="text" name="WebINK_account-settings[email]" value="<?php echo $options['email']; ?>" /></td>
						</tr>
						<tr valign="top"><th scope="row" align="left">Password:</th>
							<td><input type="password" name="WebINK_account-settings[password]" value="<?php echo $options['password']; ?>" /></td>
						</tr>
						
					</table>
					<input type="submit" id="wiAccountConnectionButton" class="button-primary" value="<?php _e('Save') ?>" />
					
				</form>
			</div>
		</div>
	</div>
	<div id="wiFontSettingsHeader" align="right">
		<h2>WebINK Fonts</h2>
		<div id="wiFontSettingsRefresh" align="left">
			<form method="post" action="" name="wiRefresh" id="wiRefresh">
				<input type="hidden" name="refresh" value="refresh" />
				<img src="<?php echo(plugin_dir_url(__FILE__));?>/img/Refresh20x20.png"/>
				<a href="javascript:document.wiRefresh.submit();" title="Refresh Project List">Refresh Project List</a>				
			</form>
		</div>
		<div id="wiConnect">
			<a href="https://www.webink.com/login" target="_new" title="Login to your WebINK account">
			Login to your WebINK account
			</a> to manage available fonts
		</div>
	</div>

	<div id="wiFontSettings">

		<form method="post" action="" id="wiProjectForm">
		<input type="hidden" name="save" value="save" />
		<?php
		$projects = getProject('','');
		if($projects)
		{
			
			foreach($projects as $project)
			{
				?>
					<br />
					<table class="wiProject" wiprojectid="<?php echo($project->GUID); ?>">
						<tr class="wiProjectOverview">
							<td class="wiProjectName" colspan="3">
								Project: <?php echo($project->Name); ?>
							</td>
							<td class="wiProjectEnable" colspan="2">
								<?php
									if($project->Empty == 0)
									{
										if($project->Enabled == 0)
										{
								?>
											<input type="hidden" class="wiProjectEnable" name="Project_<?php echo($project->GUID) ?>" value="0"/>
											<input type="checkbox" class="wiProjectEnable" name="Project_<?php echo($project->GUID) ?>" value="1"/>
											<label for="Project_<?php echo($project->GUID) ?>">Enable Project Fonts</label>
									<?php
										}else{
									?>
											<input type="hidden" class="wiProjectEnable"name="Project_<?php echo($project->GUID) ?>" value="0"/>
											<input type="checkbox" class="wiProjectEnable" name="Project_<?php echo($project->GUID) ?>" checked value="1"/>
											<label for="Project_<?php echo($project->GUID) ?>">Enable Project Fonts</label>
								<?php
										}
									}else{
										echo('&nbsp;');
									}
								?>
								
								
							</td>
							<td>&nbsp;</td>
						</tr>
						<?php
							if($project->Empty == 1)
							{
						?>
							<tr class="wiProjectHeaderRow">
								<td colspan="5" class="wiFontName" ><span>This project has no fonts</span></td>
							</tr>
						<?php
							}else{
						?>
							<tr class="wiProjectHeaderRow" valign="bottom">
								<td >&nbsp;</td>
								<td align="center">&nbsp;<a href="javascript:enableFontMenu('<?php echo($project->GUID) ?>');">Enable in Font Menu</a></td>
								<td>Selector, Class or ID</td>
								<td>Fallback Stack</td>
								<td>Exclude</td>
								<td>&nbsp;</td>
							</tr>
						<?php
							}
								$projectfonts = getProjectFonts('',$project->GUID);
								if($projectfonts)
								{
									foreach($projectfonts as $font)
									{
							?>
										<tr class="wiFont" valign="middle">
											<td class="wiFontName" style="font-family: <?php echo($font->PSName) ?>;" wifontid="<?php echo($font->FontGUID) ?>">
											<span><?php echo($font->Name); ?></span></td>
											<?php
												if($font->TinyMCE == 0) {
											?>
													<td class="wiFontTiny">
														<input type="hidden" class="TinyMCE_<?php echo($font->ProjectGUID) ?>" name="TinyMCE_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="0"/>
														<input type="checkbox" class="TinyMCE_<?php echo($font->ProjectGUID) ?>" name="TinyMCE_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="1"/>
													</td>
											<?php
												} else {
											?>
													<td class="wiFontTiny">
														<input type="hidden" class="TinyMCE_<?php echo($font->ProjectGUID) ?>" name="TinyMCE_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="0" />
														<input type="checkbox" class="TinyMCE_<?php echo($font->ProjectGUID) ?>" name="TinyMCE_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" checked value="1"/>
													</td>
											<?php
												}
											?>
											<td class="wiFontSelector">
												<input type="text" name="Selector_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="<?php echo($font->Selector); ?>" title="Seperate multiple selector items with semi-colons">
											</td>
											
											<td class="wiFontFallback">
												<select name="Fallback_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>">
													<option value="" <?php if($font->Fallback == "") echo("selected"); ?> >Select</option>
													<option value="Arial, %22Helvetica Neue%22, Helvetica, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "Arial, %22Helvetica Neue%22, Helvetica, sans-serif") echo("selected"); ?> >Arial, "Helvetica Neue", Helvetica, sans-serif</option>
													<option value="Baskerville, %22Times New Roman%22, Times, serif" <?php if(str_replace('"','%22',$font->Fallback) == "Baskerville, %22Times New Roman%22, Times, serif") echo("selected"); ?> >Baskerville, "Times New Roman", Times, serif</option>
													<option value="Cambria, Georgia, Times, %22Times New Roman%22, serif" <?php if(str_replace('"','%22',$font->Fallback) == "Cambria, Georgia, Times, %22Times New Roman%22, serif") echo("selected"); ?> >Cambria, Georgia, Times, "Times New Roman", serif</option>
													<option value="%22Century Gothic%22, %22Apple Gothic%22, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Century Gothic%22, %22Apple Gothic%22, sans-serif") echo("selected"); ?> >"Century Gothic", "Apple Gothic", sans-serif</option>
													<option value="Consolas, %22Lucida Console%22, Monaco, monospace" <?php if(str_replace('"','%22',$font->Fallback) == "Consolas, %22Lucida Console%22, Monaco, monospace") echo("selected"); ?> >Consolas, "Lucida Console", Monaco, monospace</option>
													<option value="%22Copperplate Light%22, %22Copperplate Gothic Light%22, serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Copperplate Light%22, %22Copperplate Gothic Light%22, serif") echo("selected"); ?> >"Copperplate Light", "Copperplate Gothic Light", serif</option>
													<option value="%22Courier New%22, Courier, monospace" <?php if(str_replace('"','%22',$font->Fallback) == "%22Courier New%22, Courier, monospace") echo("selected"); ?> >"Courier New", Courier, monospace</option>
													<option value="%22Franklin Gothic Medium%22, %22Arial Narrow Bold%22, Arial, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Franklin Gothic Medium%22, %22Arial Narrow Bold%22, Arial, sans-serif") echo("selected"); ?> >"Franklin Gothic Medium", "Arial Narrow Bold", Arial, sans-serif</option>
													<option value="%22Century Gothic%22, AppleGothic, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Century Gothic%22, AppleGothic, sans-serif") echo("selected"); ?> >"Century Gothic", AppleGothic, sans-serif</option>
													<option value="Garamond, %22Hoefler Text%22, Times New Roman, Times, serif" <?php if(str_replace('"','%22',$font->Fallback) == "Garamond, %22Hoefler Text%22, Times New Roman, Times, serif") echo("selected"); ?> >Garamond, "Hoefler Text", Times New Roman, Times, serif</option>
													<option value="Geneva, %22Lucida Sans%22, %22Lucida Grande%22, %22Lucida Sans Unicode%22, Verdana, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "Geneva, %22Lucida Sans%22, %22Lucida Grande%22, %22Lucida Sans Unicode%22, Verdana, sans-serif") echo("selected"); ?> >Geneva, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", Verdana, sans-serif</option>
													<option value="Georgia, Palatino,%22 Palatino Linotype%22, Times, %22Times New Roman%22, serif" <?php if(str_replace('"','%22',$font->Fallback) == "Georgia, Palatino,%22 Palatino Linotype%22, Times, %22Times New Roman%22, serif") echo("selected"); ?> >Georgia, Palatino," Palatino Linotype", Times, "Times New Roman", serif</option>
													<option value="%22Gill Sans%22, Calibri, %22Trebuchet MS%22, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Gill Sans%22, Calibri, %22Trebuchet MS%22, sans-serif") echo("selected"); ?> >"Gill Sans", Calibri, "Trebuchet MS", sans-serif</option>
													<option value="%22Helvetica Neue%22, Arial, Helvetica, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Helvetica Neue%22, Arial, Helvetica, sans-serif") echo("selected"); ?> >"Helvetica Neue", Arial, Helvetica, sans-serif</option>
													<option value="Impact, Haettenschweiler, %22Arial Narrow Bold%22, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "Impact, Haettenschweiler, %22Arial Narrow Bold%22, sans-serif") echo("selected"); ?> >Impact, Haettenschweiler, "Arial Narrow Bold", sans-serif</option>
													<option value="%22Lucida Sans%22, %22Lucida Grande%22, %22Lucida Sans Unicode%22, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Lucida Sans%22, %22Lucida Grande%22, %22Lucida Sans Unicode%22, sans-serif") echo("selected"); ?> >"Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif</option>
													<option value="Palatino, %22Palatino Linotype%22, Georgia, Times, %22Times New Roman%22, serif" <?php if(str_replace('"','%22',$font->Fallback) == "Palatino, %22Palatino Linotype%22, Georgia, Times, %22Times New Roman%22, serif") echo("selected"); ?> >Palatino, "Palatino Linotype", Georgia, Times, "Times New Roman", serif</option>
													<option value="Tahoma, Geneva, Verdana, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "Tahoma, Geneva, Verdana, sans-serif") echo("selected"); ?> >Tahoma, Geneva, Verdana, sans-serif</option>
													<option value="Times, %22Times New Roman%22, Georgia, serif" <?php if(str_replace('"','%22',$font->Fallback) == "Times, %22Times New Roman%22, Georgia, serif") echo("selected"); ?> >Times, "Times New Roman", Georgia, serif</option>
													<option value="%22Trebuchet MS%22, %22Lucida Sans Unicode%22, %22Lucida Grande%22,%22 Lucida Sans%22, Arial, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "%22Trebuchet MS%22, %22Lucida Sans Unicode%22, %22Lucida Grande%22,%22 Lucida Sans%22, Arial, sans-serif") echo("selected"); ?> >"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande"," Lucida Sans", Arial, sans-serif</option>
													<option value="Verdana, Geneva, Tahoma, sans-serif" <?php if(str_replace('"','%22',$font->Fallback) == "Verdana, Geneva, Tahoma, sans-serif") echo("selected"); ?> >Verdana, Geneva, Tahoma, sans-serif</option>
												</select>
											</td>
											
											
											<?php
												if($font->Exclude == 0) {
											?>
													<td class="wiFontExclude">
														<input type="hidden" name="Exclude_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="0" />
														<input type="checkbox" name="Exclude_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="1"/>
													</td>
											<?php
												} else {
											?>
													<td class="wiFontExclude">
														<input type="hidden" name="Exclude_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" value="0" />
														<input type="checkbox" name="Exclude_<?php echo($font->ProjectGUID) ?>_<?php echo($font->FontGUID) ?>" checked value="1"/>
													</td>
											<?php
												}
											?>
											<td>&nbsp;</td>
										</tr>
							<?php
									}
								}
								if($project->Referrers != "")
									{
								?>
									<tr class="wiProjectReferrers">
										<td colspan="6" ><strong>Domains authorized for this project: </strong><?php echo($project->Referrers);?></td>
									</tr>
								<?php
									}
							?>
						
						</table>
					<br />
				<?php
			}
			?>
			<input type="submit" name="submit" id="wiProjectFormSubmit" class="button-primary" value="<?php _e('Save Font Settings') ?>" />
			<?php
		}
		?>	
		
		</form>
	</div>

<?php

?>
	
	
	
	
	
	
	
	
	
	
