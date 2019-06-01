<?php

require_once __DIR__ . '/vendor/autoload.php';

$calculator = new App\Calculator();
$branch = new \App\Models\Branch('branch_a');

try {
	$calculator->calculate_membership_fee(120000, 'monthly', $branch);
} catch (Exception $exception) {
	print_r($exception);
}
