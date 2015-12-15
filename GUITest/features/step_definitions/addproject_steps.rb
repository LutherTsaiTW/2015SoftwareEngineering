require 'watir-webdriver'
require 'rspec'

username =''
password =''
title = ''

def generate_title
	charset = Array('a' .. 'z')
	array = Array.new(7)  { charset.sample }
	title = array.join
end

Given /^I have an account$/ do
	username = 'fortestuse01'
	password = 'a12345A'
end

When /^I login$/ do
	@b.goto "http://luthertsai.com/2015softwareengineering/login.html"
	@b.text_field(:name => 'account_id').set username
	@b.text_field(:name => 'password').set password
	@b.button(:name => 'submit').click
end

Then /^I can click the add project button$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.a(:href => 'addProject.html').exists?}
	rescue
		@b.a(:href => 'addProject.html').exists?.should == true
	end
end

And /^I can link to the add project page$/ do
	@b.a(:href => 'addProject.html').click
	@b.url.should == 'http://luthertsai.com/2015softwareengineering/Project/addProject.html'
	@b.text_field(:name => 'Name').exists?.should == true
	@b.text_field(:name => 'Company').exists?.should == true
	@b.input(:name => 'StartTime').exists?.should == true
	@b.input(:name => 'EndTime').exists?.should == true
	@b.select(:name => 'Status').exists?.should == true
	@b.select(:name => 'Status').option(:value => '0').exists?.should == true
	@b.select(:name => 'Status').option(:value => '0').text.should == 'Close'
	@b.select(:name => 'Status').option(:value => '1').exists?.should == true
	@b.select(:name => 'Status').option(:value => '1').text.should == 'Open'
	@b.select(:name => 'Status').option(:value => '2').exists?.should == true
	@b.select(:name => 'Status').option(:value => '2').text.should == 'Terminated'
	@b.textarea(:name => 'Description').exists?.should == true
	@b.button(:name => 'submit').exists?.should == true
	@b.button(:name => 'exit').exists?.should == true
end

When /^I input data$/ do
	title = generate_title
	@b.text_field(:name => 'Name').set title
	@b.text_field(:name => 'Company').set 'Test Team'
	@b.execute_script("$('input#startTime').val('2015-01-01')")
	@b.execute_script("$('input#endTime').val('2015-01-02')")
	@b.select(:name => 'Status').select_value '1'
	@b.textarea(:name => 'Description').set title
end

Then /^I can see the Expect days$/ do
	@b.span(:id => 'days').text.should == 'Expect: 1 Days'
end

When /^I submit$/ do
	@b.button(:name => 'submit').click
end

Then /^I can see my project on the project list$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.url == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'}
		Watir::Wait.until(timeout = 10) {@b.table(:class => 'listTable').exists?}
	rescue
		@b.url.should == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'
		@b.table(:class => 'listTable').exists?.should == true
	end
	size = @b.table(:class => 'listTable').rows.length
	target = @b.table(:class => 'listTable')[size - 2]
	target[0].a.text.should == title
	target[1].text.should == 'Test Team'
	target[2].text.should == 'Dont Delete'
	target[3].text.should == '2015/01/01'
	target[4].text.should == '2015/01/02'
	target[5].text.should == 'Open'
	target[6].a.text.should == 'edit'
	target[7].a.text.should == 'delete'
end
