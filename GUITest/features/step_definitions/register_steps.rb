require 'watir-webdriver'
require 'rspec'

accountID = ''
password = 'a12345A'

def generate_account
	charset = Array('a' .. 'z')
	array = Array.new(7)  { charset.sample }
	array =  array << (rand 0 .. 10).to_s
	account = array.join
end

Given /^I am in register page$/ do
	@b.a(:href => "register.html").click()
	begin
		Watir::Wait.until(timeout = 10) {@b.url == 'https://luthertsai.com/2015softwareengineering/register.html'}
	rescue
		@b.url.should == 'https://luthertsai.com/2015softwareengineering/register.html'
	end
	@b.text_field(:name => 'AccountID').exists?.should == true
	@b.text_field(:name => 'Password').exists?.should == true
	@b.text_field(:name => 'PasswordConfirm').exists?.should == true
	@b.text_field(:name => 'Name').exists?.should == true
	@b.text_field(:name => 'Email').exists?.should == true
	@b.text_field(:name => 'Company').exists?.should == true
	@b.button(:name => 'submit').exists?.should == true
end

When /^I entered all the data$/ do
	accountID = generate_account
	@b.text_field(:name => 'AccountID').set accountID
	@b.text_field(:name => 'Password').set password
	@b.text_field(:name => 'PasswordConfirm').set password
	@b.text_field(:name => 'Name').set 'TestCase Account'
	@b.text_field(:name => 'Email').set accountID + '@testcase.com'
	@b.text_field(:name => 'Company').set 'Test Company'
end

And /^I submit the register form$/ do
	@b.button(:name => 'submit').click
end

Then /^I must see a success message$/ do
	@b.alert.exists?.should == true
	@b.alert.text.should == '註冊成功！！！'
end

When /^I cofirm it$/ do
	@b.alert.ok
end

Then /^Browser must go back to login page$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.url == 'https://luthertsai.com/2015softwareengineering/login.html'}
	rescue
		@b.url.should == 'https://luthertsai.com/2015softwareengineering/login.html'
	end
end

And /^I can successfully login$/ do
	@b.text_field(:name => 'account_id').set accountID
	@b.text_field(:name => 'password').set password
	@b.button(:name => 'submit').click
	begin
		Watir::Wait.until(timeout = 10) {@b.div(:id => 'userName').exists?}
		Watir::Wait.until(timeout = 10) {@b.div(:id => 'userName').text == 'Welcome, TestCase Account!'}
	rescue
		@b.div(:id => 'userName').text.should == 'Welcome, TestCase Account!'
	end
end
