<?php
namespace Application\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Db\Adapter\Adapter;
use Zend\I18n\Translator\Translator;

class TranslatePlugin extends AbstractPlugin
{
    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;
    function __construct()
    {
		$translator = new \Zend\I18n\Translator\Translator(); 

		$translator->setLocale("en");
		$translator->addTranslationFile("phparray",ROOT_PATH.'/vendor/translations/lang.php',"default","en");
        $this->translator = $translator;
    }
    public function translate($string)
    {
		if($_COOKIE['currentLang']=='en'){
			return $this->translator->translate($string);
		}else{
			return $this->translator->translate(strtolower($string));
		}
    }
	
}
?>