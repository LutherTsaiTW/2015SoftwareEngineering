require 'watir-webdriver'
require 'rspec'

Given /^I am in Login page$/ do
	@b.text_field(:name => 'account_id').exists?.should == true
	@b.text_field(:name => 'password').exists?.should == true
	@b.button(:name => 'submit').exists?.should == true
end

When /^I entered (.+) in Username$/ do |username|
	@b.text_field(:name => 'account_id').set username
end

And /^I entered (.+) in Password$/ do |password|
	@b.text_field(:name => 'password').set password
end

And /^I submit the login form$/ do
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
