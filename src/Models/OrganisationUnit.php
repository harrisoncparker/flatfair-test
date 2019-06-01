<?php

namespace App\Models;

class OrganisationUnit
{
	/** @var string  */
	protected $type = '';

	/** @var string  */
	private $name = '';
	/** @var int  */
	private $fixed_membership_fee_amount = 0;

	public function __construct( string $name, array $config = [] )
	{
		$this->name = $name;

		if ( isset( $config['fixed_membership_fee_amount'] ) ) {
			$this->fixed_membership_fee_amount = $config['fixed_membership_fee_amount'];
		}
	}

	public function has_fixed_membership_fee(): bool
	{
		return $this->fixed_membership_fee_amount > 0;
	}

	public function get_fixed_membership_fee(): int
	{
		return $this->fixed_membership_fee_amount;
	}
}