<?php
class olark extends Module
{
	function __construct()
	{
		$this->name = 'olark';
		$this->tab = floatval(substr(_PS_VERSION_,0,3))<1.4?'Designhaus42':'front_office_features';
		$this->version = '1.4';
		if (floatval(substr(_PS_VERSION_,0,3)) >= 1.4)
			$this->author = 'Designhaus42';
		$this->version = '0.1';
		parent::__construct(); // The parent construct is required for translations
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Olark Chat');
		$this->description = $this->l('Adds your Olark chat code to your website');
	}
	function install()
	{
		if (!parent::install())
			return false;
		if (!$this->registerHook('footer'))
			return false;
		return true;
	}
	function hookFooter($params)
	{
		if (file_exists(dirname(__FILE__).'/values.xml'))
		{
			if ($xml = simplexml_load_file(dirname(__FILE__).'/values.xml'))
			{
				global $cookie, $smarty;
				$smarty->assign(array(
					'olark_identity' => $xml->{'OLI'},
					'bheight' => $xml->{'height'},
					'bwidth' => $xml->{'width'},
					'text_right' => $xml->{'right_'},
					'cenabled' => $xml->{'hide'},
					'this_path' => $this->_path
				));
				return $this->display(__FILE__, 'olark.tpl');
			}
		}
		return false;
	}
	function getContent()
	{
		/* display the module name */
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		/* update the editorial xml */
		if (isset($_POST['submitUpdate']))
		{
			// Forbidden key
			$forbidden = array('submitUpdate');
			// Generate new XML data
			$newXml = '<?xml version=\'1.0\' encoding=\'utf-8\' ?>'."\n";
			$newXml .= '<html>'."\n";
			// Making header data
			foreach ($_POST AS $key => $field)
			{
				if (_PS_MAGIC_QUOTES_GPC_)
					$field = stripslashes($field);
				if ($line = $this->putContent($newXml, $key, $field, $forbidden, 'header'))
					$newXml .= $line;
			}
			$newXml .= "\n</html>\n";
			/* write it into the editorial xml file */
			if ($fd = @fopen(dirname(__FILE__).'/values.xml', 'w'))
			{
				if (!@fwrite($fd, $newXml))
					$this->_html .= $this->displayError($this->l('Unable to write to the text file.'));
				if (!@fclose($fd))
					$this->_html .= $this->displayError($this->l('Can\'t close the text file.'));
			}
			else
				$this->_html .= $this->displayError($this->l('Unable to update the text file.<br />Please check the text file\'s writing permissions.'));
		}


		/* display the editorial's form */

		$this->_displayForm();



		return $this->_html;

	}



	private function _displayForm()

	{
$v1 = '';
$v2 = '';
$v3 = '';
		/* Languages preliminaries */

		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));

		$languages = Language::getLanguages();

		$iso = Language::getIsoById($defaultLanguage);

		$divLangName = 'text_left¤text_right';


		/* xml loading */

		$xml = false;

		if (file_exists(dirname(__FILE__).'/values.xml'))

				if (!$xml = @simplexml_load_file(dirname(__FILE__).'/values.xml'))

					$this->_html .= $this->displayError($this->l('Your text file is empty.'));

$posvalue = $xml->{'right_'};
if (!strcmp($posvalue,"TL")){
	$v1="selected=selected";
}else if (!strcmp($posvalue,"TR")){
	$v2="selected=selected";
}else if (!strcmp($posvalue,"BR")){
	$v3="selected=selected";
}
$chathide ="olark('api.box.hide')";
		$this->_html .= '

		

		<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
		
			<fieldset style="width:800px;margin:auto;">
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->displayName.'</legend><a href="http://www.olark.com/?r=hkva4810"><p style="font-size:14px;"">Chat with your Customers Create trust, loyalty, & happiness with Olark. If you do not have an account, <b>click here to
					sign up for one.</b><p/></a>
				<label>'.$this->l('Site ID').'</label>
				<div class="margin-form">';
					$this->_html .= '
					<div id="text_left_" style="float: left;">
						<input type="text" id="OLI" name="OLI" value="'.($xml ? stripslashes(htmlspecialchars($xml->{'OLI'})) : '').'">
					</div>';
				$this->_html .= '
					<p class="clear">'.$this->l('Your site ID is found in your OLARK account and looks like 7452-323-10-2717').'</p>
				</div>
				<label>'.$this->l('Chat Position').'</label>
				<div class="margin-form">';
			

					$this->_html .= '

					<div id="text_right_" style="float: left;">

						
<select id="right_" name="right_">
  <option value="BL"'. $posvalue .'>Bottom Left</option>
  <option value="BR"'.$v3 .'>Bottom Right</option>
  <option value="TL"'.$v1 .'>Top Left</option>
  <option value="TR"'.$v2 .'>Top Right</option>
</select></div>';
					

				$this->_html .= '
					<p class="clear">'.$this->l('Chat box position does not work with all themes').'</p>
				</div>
				<label>'.$this->l('Chat Box Height').'</label>
				<div class="margin-form">';
					$this->_html .= '
					<div id="text_center_" style=";float: left;">
						<input type="text" id="height" name="height" value="'.($xml ? stripslashes(htmlspecialchars($xml->{'height'})) : '').'">
					</div>';
				
				$this->_html .= '
					<p class="clear">'.$this->l('Chat box height does not work with all themes').'</p>
				</div>
				<label>'.$this->l('Chat Box Width').'</label>
				<div class="margin-form">';
					$this->_html .= '
					<div id="text_center_" style=";float: left;">
						<input type="text" id="width" name="width" value="'.($xml ? stripslashes(htmlspecialchars($xml->{'width'})) : '').'">
					</div>';
				$this->_html .= '
					<p class="clear">'.$this->l('Chat box width does not work with all themes').'</p>
				</div>
<label>'.$this->l('Chat Enabled').'</label>
				<div class="margin-form">';
			

					$this->_html .= '

					<div id="text_right_" style="float: left;">

						
<select id="hide" name="hide">
  <option value="">Enabled</option>
  <option value="'. $chathide . '">Disabled</option>
</select></div>';
$this->_html .= '
					<p class="clear">'.$this->l('Enable or disable your chat').'</p>
				</div>

				<div class="clear pspace"></div>
				<div class="margin-form clear"><input type="submit" name="submitUpdate" value="'.$this->l('Update the Settings').'" class="button" /></div>
			</fieldset>
		</form>';

	}
	function putContent($xml_data, $key, $field, $forbidden)
	{
		foreach ($forbidden AS $line)
			if ($key == $line)
				return 0;
		$field = htmlspecialchars($field);
		if (!$field)
			return 0;
		return ("\n".'		<'.$key.'>'.$field.'</'.$key.'>');
	}
}
?>