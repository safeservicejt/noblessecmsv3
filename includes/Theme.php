<?php

/*

Theme struct

- controllers

- models

- views

- shortcodes.php

- install.php

- uninstall.php

------------------------------------------------------------------------

Theme install & uninstall: Create file functions.php in root dir of theme then call to below functions

Theme::install(function(){
	
// Do you need

});

Theme::uninstall(function(){
	
// Do you need

});

Add shortcode use in theme: Create file shortcode.php in theme folder

// Shortcode::templateAdd('testv','simple_youtube_parse');


// function simple_youtube_parse($inputData=array())
// {
// 	$value=$inputData['value'];

// 	return '<a href="http://youtube.com?v='.$value.'">Click tosss watch video</a>';
// }



*/
class Theme
{

	public static $can_install='no';

	public static $can_uninstall='no';

	private static $data=array();

	public static $setting=array();

	public static $layoutPath='';

	public static function install($func)
	{
		if(self::$can_install=='no')
		{
			return false;
		}


        if (is_object($func)) {

            (object)$varObject = $func;

            $func = '';

            $varObject();

        } 
	}


	public static function uninstall($func)
	{
		if(self::$can_uninstall=='no')
		{
			return false;
		}

        if (is_object($func)) {

            (object)$varObject = $func;

            $func = '';

            $varObject();

        } 
	}



	public static function activate($themeName='')
	{

		$oldTheme=System::getThemeName();

		$oldThemePath=THEMES_PATH.$oldTheme.'/';

		$newThemePath=THEMES_PATH.$themeName.'/';

		if(file_exists($oldThemePath.'uninstall.php'))
		{
			include($oldThemePath.'uninstall.php');
		}

		if(file_exists($newThemePath.'install.php'))
		{
			include($newThemePath.'install.php');
		}

		System::saveSetting(array(
			'theme_name'=>$themeName
			));
	}

	public static function softActivate($themeName='')
	{
		$newThemePath=THEMES_PATH.$themeName.'/';

		if(file_exists($newThemePath.'install.php'))
		{
			include($newThemePath.'install.php');
		}
	}

	public static function get($inputData=array())
	{

		$limitQuery="";

		$limitShow=isset($inputData['limitShow'])?$inputData['limitShow']:1000;

		$limitPage=isset($inputData['limitPage'])?$inputData['limitPage']:0;

		$limitPage=((int)$limitPage > 0)?$limitPage:0;

		$limitPosition=$limitPage*(int)$limitShow;

		$listDir=Dir::listDir(THEMES_PATH);

		$total=count($listDir);

		$result=array();

		for($i=$limitPage;$i<$limitShow;$i++)
		{
			if(!isset($listDir[$i]))
			{
				continue;
			}

			if($listDir[$i]==THEME_NAME)
			{
				continue;
			}
			
			$path=THEMES_PATH.$listDir[$i].'/';
			$url=THEMES_URL.$listDir[$i].'/';

			if(!file_exists($path.'info.txt'))
			{
				continue;
			}

			$result[$listDir[$i]]=file($path.'info.txt');

			$result[$listDir[$i]]['thumbnail']=$url.'thumb.jpg';
			
			if(!file_exists($path.'thumb.jpg'))
			{
				$result[$listDir[$i]]['thumbnail']=System::getUrl().'bootstraps/images/thumb.jpg';
			}
			

		}

		return $result;
		
	}

	public static function settingUrl($controlName,$methodName='index')
	{
		$themeName='';


		if(!$match=Uri::match('theme\/setting\/(\w+)'))
		{	
			return $themeName;
		}

		$themeName=$match[1];

		$url=System::getAdminUrl().'theme/setting/'.$themeName.'/'.$controlName.'/'.$methodName;

		return $url;
	}

	public static function getDefault()
	{
		$path=ROOT_PATH.'contents/themes/'.System::getThemeName().'/';

		$resultData=array();

		$resultData=file($path.'info.txt');

		$resultData['image']=System::getThemeUrl().'thumb.jpg';

		if(!file_exists($path.'thumb.jpg'))
		{
			$resultData['image']=System::getUrl().'bootstraps/images/thumb.jpg';
		}		

		$resultData['name']=System::getThemeName();

		return $resultData;		
	}

	public static function getSetting($keyName='',$defaultVal='')
	{
		$result='';

		$result=isset(self::$setting[$keyName])?self::$setting[$keyName]:$defaultVal;

		return $result;
	}

	public static function loadSetting($themeName='')
	{
		$savePath=ROOT_PATH.'caches/theme/'.$themeName.'.cache';

		$themePath=THEMES_PATH.$themeName.'/';

		$result=false;

		if(file_exists($savePath))
		{
			$result=unserialize(base64_decode(Strings::decrypt(file_get_contents($savePath))));
		}
		else
		{
			if(is_dir($themePath) && file_exists($themePath.'install.php'))
			{
				include($themePath.'install.php');
				
				if(file_exists($savePath))
				{
					$result=unserialize(base64_decode(Strings::decrypt(file_get_contents($savePath))));
				}

			}
		}

		self::$setting=$result;



		System::defineVar('themeSetting',$result);

		return $result;

	}

	public static function makeSetting($themeName='',$inputData=array())
	{
		$savePath=ROOT_PATH.'caches/theme/'.$themeName.'.cache';

		$saveData=array();

		if(file_exists($savePath))
		{
			$saveData=trim(file_get_contents($savePath));

			if(isset($saveData[300]))
			{
				// $saveData=Strings::decrypt(base64_decode(unserialize($saveData)));
				$saveData=unserialize(base64_decode(Strings::decrypt($saveData)));
			}
			else
			{
				$saveData=array();
			}
			
		}

		$total=count($inputData);

		$keyNames=array_keys($inputData);

		for ($i=0; $i < $total; $i++) { 
			$theKey=$keyNames[$i];

			$saveData[$theKey]=$inputData[$theKey];
		}

		File::create($savePath,Strings::encrypt(base64_encode(serialize($saveData))));
	}
}