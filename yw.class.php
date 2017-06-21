<?php

/**
	*	v.0.0.1
	*	Вспомагательный класс для работы с сео задачами
	*
	*	USAGE: 
	*	YW::getUrl();
	*	YW::getUrlPart("query");
	*	YW::matchSrcImgs("<img src='/uploads/asdf.png'>");
	*	YW::print_s("/uploads/asdf/");
	*	YW::print_r( array("/uploads/asdf/", "/uploads/2/") );
	*	YW::redirect("/uploads/asdf/", "/uploads/2/");
	*	YW::getRootPath();
	*	YW::getFilePath();
	*	
	*	homepage: https://store.youweb-studio.ru
	*	contacts: d.goryaev@hotmail.com
*/
class YW {
	private static $log_file = "log--yw.txt";
	private $cms = "static";

	public static function getFilePath() {
		return __DIR__;
	}
	public static function getRootPath() {
		return $_SERVER["DOCUMENT_ROOT"];
	}
	public static function getUrl($url = false) {
		$url = $url ? $url : $_SERVER["REQUEST_URI"];
		return parse_url($url, PHP_URL_PATH);
	}	
	/**
		*	scheme - например, http
		*	host
		*	port
		*	user
		*	pass
		*	path
		*	query - после знака вопроса ?
		*	fragment - после знака диеза #
	*/
	public static function getUrlPart($param = "path") {
		$arUri = parse_url( $_SERVER["REQUEST_URI"] );
		return $arUri[$param];
	}
	public static function info() {
		return get_class_methods(__CLASS__);
	}
	public static function log($message, $filename) {
		$logfile = $filename ? $filename : self::$log_file;
		$date = date("Y-m-d H:m:s");
		$message .= "\n";
		$message .= "Адрес вызова: " . $_SERVER["REQUEST_URI"] . "\n";
		$message .= $date . "\n";
		$message .= "\n";
		return file_put_contents( $logfile, $message, FILE_APPEND);
	}
	public static function matchSrcImgs($content) {
		if(!$content) {
			return false;
		}
		
		$pattern = '<img(.*)src=["|\'](.*)["|\']>';
		$subject = $content;
		preg_match_all($pattern, $subject, $out, PREG_SET_ORDER);

		return $out;
	}
	public static function print_s($a) {
		echo "<!--yw.class ";
		echo $a;
		echo " -->";
	}
	public static function print_r($ar) {
		echo "<!--yw.class ";
		print_r($ar);
		echo " -->";
	}
	public static function redirect($old, $new) {
		if( $old == self::getUrl() ) {
			header("Location: $new", true, 301);
		}
	}
}

/**
*	Не доработано
*	Не протестировано
*/
class YWBitrix extends YW {
	public static function sendMail($to, $from, $message) {
	    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	    // AddMessage2Log($email);
	    // AddMessage2Log($message);
	    $arEventFields = array(
	        "EMAIL_TO"    => $to,
	        "EMAIL_FROM"  => $from,
	        "ACTIVE"      => true,
	        "MESSAGE"     => $message
	    );
	    CEvent::SendImmediate("EVENT_NOTIFICATION", "s1", $arEventFields);
	    // CEvent::Send("REMEMBER", SITE_ID, $arEventFields);
	}
}

?>
