<?php
/**
 * Your local config here
 */

/* Snippet begin:
Initial snippet found on [tips:multilingual_content:local.php [DokuWiki]](https://www.dokuwiki.org/tips:multilingual_content:local.php), author seems to be [Johannes Vockeroth](mailto:jv525052@inf.tu-dresden.de). (DokuWiki.org CC-AS 4.0 license)
*/
/* Multilanguage Support
 * Paste these lines at the end of your local.php and have a look if it works! 
 * DokuWiki shall detect your user agents favorite language and switch interface language depending on leading namespace
 * Your (international) start page will still be 'start' by default, unless you create a page like en:start or de:start
 */

// Configuration 
$conf['lang_enabled'] = array();	//allowed languages (leave this array blank or comment out for auto-detection)

// Autodetect all languages your dokuwiki installation supports
$supportedLanguages = array();
if ($handle = opendir(DOKU_INC.'inc/lang')) {
   while (false !== ($file = readdir($handle))) {
      if (is_dir(DOKU_INC.'inc/lang/'.$file)) array_push($supportedLanguages,$file);
   }
   closedir($handle);
}
if (!isset($conf['lang_enabled'])) $conf['lang_enabled'] = array();
if (count($conf['lang_enabled'])==0) $conf['lang_enabled'] = $supportedLanguages;

// Set default language to the user agents most favorite one
$languages = split(',', preg_replace('/(;q=\d+.\d+)/i', '', getenv('HTTP_ACCEPT_LANGUAGE'))); 
foreach ($languages as $lang) if (in_array($lang, $conf['lang_enabled'])) {
    $conf['lang'] = $lang;
    break;
}

// Check, if language is set by namespace and overwrite choosed language
$lang = preg_replace('/^(..+)[:\/].*/i','$1',$_REQUEST['id']);
if (!in_array($lang, $conf['lang_enabled'])) $lang = preg_replace('/^(..+)[:\/].*/i','$1',$_REQUEST['ns']);
if (in_array($lang, $conf['lang_enabled'])) $conf['lang'] = $lang;

// prepend default start page with language namespace, if this page already exists.
if (file_exists($conf['savedir'].'/pages/'.str_replace(':','/',$conf['lang'].':'.$conf['start']).'.txt')) $conf['start'] = $conf['lang'].':'.$conf['start'];