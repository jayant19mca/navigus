<?php

namespace Drupal\navigus\Controller;

class NavigusHomePageController {
	public function content() {
		return array(
			'#title' => 'Hello World!',
			'#markup' => 'Content for Homepage'
		);
	}
}

