<?php
$methods = [
	'submitAmbassador' => [
		'params' => [
			[
				'name' => 'firstname',
				'source' => 'p',
				'pattern' => 'name_pattern',
				'required' => true
			],
			[
				'name' => 'secondname',
				'source' => 'p',
				'pattern' => 'name_pattern',
				'required' => true
			],
			[
				'name' => 'position',
				'source' => 'p',
				'default' => '',
				'required' => false
			],
			[
				'name' => 'phone',
				'source' => 'p',
				'pattern' => 'phone',
				'required' => true
			],
			[
				'name' => 'email',
				'source' => 'p',
				'pattern' => 'email',
				'default' => '',
				'required' => false
			],
		]
	]
];