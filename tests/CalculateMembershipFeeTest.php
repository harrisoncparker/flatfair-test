<?php
/**
 * Created by PhpStorm.
 * User: harrisonparker
 * Date: 01/06/2019
 * Time: 18:59
 */

namespace Tests;

use App\Calculator;
use App\Models\Branch;

use App\Exceptions\RentAmountTooSmallException;
use App\Exceptions\RentAmountTooLargeException;
use App\Exceptions\InvalidRentPeriodException;

use PHPUnit\Framework\TestCase;

class CalculateMembershipFeeTest extends TestCase
{
	/** @var Branch */
	private $branchWithoutSetFee;
	/** @var Branch */
	private $branchWithSetFee;
	/** @var Calculator */
	private $calculator;
	/** @var int */
	private $set_fee_amount = 34000;

	protected function setUp()
	{
		$this->branchWithoutSetFee = new Branch( 'branch_a' );

		$this->branchWithSetFee = new Branch( 'branch_b', [
			"fixed_membership_fee_amount" => $this->set_fee_amount
		] );

		$this->calculator = new Calculator();
	}

	/**
	 * Validation
	 */

	/** @test */
	public function validates_valid_rent_period()
	{
		$this->expectException( InvalidRentPeriodException::class );
		$this->expectExceptionCode( 400 );

		$this->calculator->calculate_membership_fee(
			24000,
			'invalid_rent_period',
			$this->branchWithoutSetFee
		);
	}

	/** @test */
	public function validates_minimum_rent()
	{
		$this->expectException( RentAmountTooSmallException::class );
		$this->expectExceptionCode( 400 );

		$this->calculator->calculate_membership_fee(
			10999,
			'monthly',
			$this->branchWithoutSetFee
		);
	}

	/** @test */
	public function validates_maximum_rent()
	{
		$this->expectException( RentAmountTooLargeException::class );
		$this->expectExceptionCode( 400 );

		$this->calculator->calculate_membership_fee(
			866001,
			'monthly',
			$this->branchWithoutSetFee
		);
	}

	/**
	 * Calculation
	 */

	/** @test */
	public function returns_set_membership_fee_if_branch_has_one()
	{
		$membership_fee = $this->calculator->calculate_membership_fee(
			10000,
			'weekly',
			$this->branchWithSetFee
		);

		$this->assertEquals( $this->set_fee_amount, $membership_fee );
	}

	/** @test */
	public function calculates_membership_fee()
	{
		$membership_fee = $this->calculator->calculate_membership_fee(
			100000,
			'weekly',
			$this->branchWithoutSetFee
		);

		$this->assertEquals( 120000, $membership_fee );
	}

	/** @test */
	public function returns_minimum_fee_if_weekly_rent_is_below_120_pounds()
	{
		$membership_fee = $this->calculator->calculate_membership_fee(
			10000,
			'weekly',
			$this->branchWithoutSetFee
		);

		$this->assertEquals( 14400, $membership_fee );
	}
}