<?php
/**
 * Created by PhpStorm.
 * User: harrisonparker
 * Date: 01/06/2019
 * Time: 18:07
 */

namespace App;

use App\Exceptions\InvalidRentPeriodException;
use App\Exceptions\RentAmountTooLargeException;
use App\Exceptions\RentAmountTooSmallException;
use App\Models\OrganisationUnit;

class Calculator
{

	/**
	 * Valid Rent Periods
	 * minimum and maximum rent periods are recorded in pence
	 */
	private $rent_periods = [
		'weekly'  => [
			'min_rent' => 2500,
			'max_rent' => 200000
		],
		'monthly' => [
			'min_rent' => 11000,
			'max_rent' => 866000
		]
	];

	private $minimum_rent_for_membership_fee = 12000;

	/**
	 * @param int $rent_amount
	 * The amount of rent for given rent period in pence
	 *
	 * @param string $rent_period
	 * The period for which the rent is payed can be either 'weekly' or 'monthly'
	 *
	 * @param OrganisationUnit $organisation_unit
	 *
	 * @return int
	 * @throws InvalidRentPeriodException
	 * @throws RentAmountTooSmallException
	 * @throws RentAmountTooLargeException
	 */
	public function calculate_membership_fee(
		int $rent_amount,
		string $rent_period,
		OrganisationUnit $organisation_unit
	): int {

		/**
		 * Validation
		 */

		// Check if rent period is valid
		if ( ! key_exists( $rent_period, $this->rent_periods ) ) {
			throw new InvalidRentPeriodException( "Invalid rent period.", 400 );
		}

		// Store rent period array
		$rent_period_array = $this->rent_periods[ $rent_period ];

		// Check if rent is valid amount
		if ( $rent_amount < $rent_period_array['min_rent'] ) {
			throw new RentAmountTooSmallException( "The rent is below the minimum amount.", 400 );
		}

		if ( $rent_amount > $rent_period_array['max_rent'] ) {
			throw new RentAmountTooLargeException( "The rent is above the maximum amount.", 400 );
		}

		/**
		 * Logic
		 */

		// return fixed membership fee if exists
		if ( $organisation_unit->has_fixed_membership_fee() ) {

			$membership_fee = $organisation_unit->get_fixed_membership_fee();
		} else {

			// Calculate one week of rent based on specified period
			$one_week_of_rent = $rent_period === 'monthly' ? $rent_amount / 4 : $rent_amount;

			// Return the minimum membership fee if rent is lower than
			// the minimum rent for membership fee calculations
			if ( $one_week_of_rent < $this->minimum_rent_for_membership_fee ) {

				$membership_fee = $this->minimum_rent_for_membership_fee * 1.2;
			} else {

				$membership_fee = $one_week_of_rent * 1.2;
			}
		}

		return ceil( $membership_fee );
	}
}