Feature: Login sysytem
	Scenario: Register an account
		Given I am in register page
		When I entered all the data
		And I submit the register form
		Then I must see a success message
		When I cofirm it
		And Browser must go back to login page
		And I can successfully login
