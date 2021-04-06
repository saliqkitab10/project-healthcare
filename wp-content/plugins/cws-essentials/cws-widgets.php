<?php

class Metamax_Widgets{
	//Register custom widgets
	public function __construct( $cws_widgets ) {

		foreach ($cws_widgets as $w) {
			$php = WP_PLUGIN_DIR . '/cws-essentials/widgets/' . strtolower($w) . '.php';
			if (file_exists($php)) {
				require_once $php;
				register_widget($w);
			}
		}
	}
}

?>