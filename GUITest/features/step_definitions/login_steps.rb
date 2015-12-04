require 'watir-webdriver'
require 'rspec'

Given /^I am in Login page$/ do
	@b.goto "http://luthertsai.com/2015softwareengineering/login.html"
end

When /^I entered (.+) in Username$/ do |username|
	@b.text_field(:name => 'account_id').set username
end

And /^I entered (.+) in Password$/ do |password|
	@b.text_field(:name => 'password').set password
end

And /^I submit$/ do
	@b.button(:name => 'submit').click
end

Then /^I must see the (.+) at navbar$/ do |result|
	begin
		Watir::Wait.until(timeout = 10) {@b.div(:id => 'userName').exists?}
		Watir::Wait.until(timeout = 10) {@b.div(:id => 'userName').text == result}
	rescue
		@b.div(:id => 'userName').text.should == result
	end
end
