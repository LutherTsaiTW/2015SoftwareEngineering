Feature: Account System
	Scenario Outline: User login
		Given I am in Login page
		When I entered <username> in Username
		And I entered <password> in Password
		And I submit the login form
		Then I must see the <result> at navbar

		Examples:
			| username     | password | result                     |
			| fortestuse01 | a12345A  | Welcome, DontdeletePlease! |
			| fortestuse02 | a12345A  | Welcome, Dontdelete!       |
			| fortestuse03 | a12345A  | Welcome, TestCaseA!        |
		
	Scenario: Register an account
		Given I am in register page
		When I entered all the data
		And I submit the register form
		Then I must see a success message
		When I cofirm it
		And Browser must go back to login page
		And I can successfully login
