Feature: Login System
	Scenario Outline: User login
		Given I am in Login page
		When I entered <username> in Username
		And I entered <password> in Password
		And I submit
		Then I must see the <result> at navbar

		Examples:
			| username    | password | result          |
			| andymememe1 | a12345A  | Welcome, 陳亮宇! |
			| aaaaaaa1    | Aa1111   | Welcome, aaa!   |
