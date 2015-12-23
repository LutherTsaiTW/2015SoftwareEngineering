require 'watir-webdriver'
require 'rspec'

@size = 0

And /^I count the rows$/ do
	Watir::Wait.until(timeout = 10) {@b.table(:class => 'listTable').exists?}
	@size = @b.table(:class => 'listTable').rows.length
end

Then /^I can see the expect data in input field$/ do
	@b.text_field(:name => 'Name').value.should == @title
	@b.text_field(:name => 'Company').value.should ==  'Test Team'
	@b.input(:name => 'StartTime').value.should == '2015-01-01'
	@b.input(:name => 'EndTime').value.should == '2015-01-02'
	@b.select(:name => 'Status').value.should ==  '1'
	@b.textarea(:name => 'Description').value.should ==  @title
end

When /^I click the cancel button to cancel$/ do
	@b.button(:name => 'exit').click
end

Then /^I go to the project list page$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.url == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'}
	rescue
		@b.url.should == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'
	end
end

And /^I can see there is no new row$/ do
	begin
		Watir::Wait.until(timeout = 10) {@b.table(:class => 'listTable').exists?}
	rescue
		@b.table(:class => 'listTable').exists?.should == true
	end
	@b.table(:class => 'listTable').rows.length.should == @size
end