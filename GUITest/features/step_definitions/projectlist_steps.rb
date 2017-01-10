require 'watir-webdriver'
require 'rspec'

username = ''
password = ''

Then /^I can see the project list title$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.url == "https://luthertsai.com/2015softwareengineering/Project/projectList.html"}
	rescue
		@b.url.should == ""
	end
	@b.divs(:class => 'w3-row ')[1].h1.text.should == "Project List"
end

And /^I can see the welcome message$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.div(:id => "userName").text == "Welcome, Dont Delete!"}
	rescue
		@b.div(:id => "userName").text.should == "Welcome, Dont Delete!"
	end
end

And /^I can see the add project button$/ do
	@b.a(:href => "addProject.html").exist?.should == true
end
	