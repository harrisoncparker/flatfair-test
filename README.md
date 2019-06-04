## Flatfair Coding Challenge

This is my submission for the FlatFair coding challenge. 
I've taken a vanilla PHP approach to display that I understand 
certain concepts beyond their implementation within any specific
frameworks or libraries. The only library used is PHPUnit 
to write and run tests.

The core of this app is the `./src/Caculator` class which contains 
the calculate_membership_fee function.

### Testing
I have written tests for this method in `./tests/CalculateMembershipFeeTest.php` and it's results. 

To execute the tests, from the root directory run:
* `composer install`
* `./vendor/phpunit/phpunit/phpunit ./tests/CalculateMembershipFeeTest.php`
