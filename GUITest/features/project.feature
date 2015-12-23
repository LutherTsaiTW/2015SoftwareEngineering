Feature: Project System
    Scenario: After I login, I can see the project list
		Given I have an account
		When I login
		Then I can see the project list title
		And I can see the welcome message
		And I can see the add project button
    
    Scenario: In project list, I can add a new project
		Given I have an account
		When I login
		Then I can click the add project button
		And I can link to the add project page
		When I input data
		Then I can see the Expect days
		When I submit
		Then I can see my project on the project list
    
    Scenario: In add project page, I can cancel the addition of new project
		Given I have an account
		When I login
		Then I can click the add project button
		And I can link to the add project page
		When I input data
		Then I can see the expect data in input field
		When I click the cancel button to cancel
		Then I go to the project list page
		And I can not see the data input
    
    Scenario Outline: In project list page, I can edit the project detail
		Given I have an account
		And I have a project
		When I login
		And I click the edit button
		Then I can see the edit project page title
		And I can see the data of the project
		When I modify the <attribute>
		And I click edit button
		Then I go to the project list Page
		And I can see the <attribute> that has been modified

		Examples:
			| attribute       |
			| project_name    |
			| project_company |
			| date            |
			| status          |
