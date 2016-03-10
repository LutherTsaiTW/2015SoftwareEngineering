require 'watir-webdriver'
require 'rspec'

username = 'fortestuse01'
password = 'a12345A'
title = ''
modify = ''

def generate_title
  charset = Array('a' .. 'z')
  array = Array.new(7)  { charset.sample }
  title = array.join
end

Given /^I have a project$/  do
    @b.text_field(:name => 'account_id').set username
    @b.text_field(:name => 'password').set password
    @b.button(:name => 'submit').click
    Watir::Wait.until(timeout = 10) {@b.a(:href => 'addProject.html').exists?}
    @b.a(:href => 'addProject.html').click
    title = generate_title
    @b.text_field(:name => 'Name').set title
    @b.text_field(:name => 'Company').set 'Test Team'
    @b.execute_script("$('input#startTime').val('2015-01-01')")
    @b.execute_script("$('input#endTime').val('2015-01-02')")
    @b.select(:name => 'Status').select_value '1'
    @b.textarea(:name => 'Description').set title
    @b.button(:name => 'submit').click
end

When /^I login and click the edit button$/ do
  Watir::Wait.until(timeout = 10) {@b.url == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'}
  Watir::Wait.until(timeout = 10) {@b.table(:class => 'listTable').exists?}
  size = @b.table(:class => 'listTable').rows.length
  target = @b.table(:class => 'listTable')[size - 2]
  target[6].a.click
end

Then /^I can see the edit project page title$/ do
  @b.h1.text.should == 'Edit Project'
end

And /^I can see the data of the project$/ do
    @b.text_field(:name => 'name').value.should == title
    @b.text_field(:name => 'company').value.should == 'Test Team'
    @b.input(:name => 'startTime').value.should == '2015-01-01'
    @b.input(:name => 'endTime').value.should == '2015-01-02'
    @b.select(:name => 'status').value.should == '1'
    @b.textarea(:name => 'des').value.should == title
end

When /^I modify the (.+)$/ do |attribute|
  modify = generate_title
  case attribute
  when 'project_name'
    @b.text_field(:name => 'name').set modify
  when 'project_company'
    @b.text_field(:name => 'company').set modify
  when 'date'
    @b.execute_script("$('input#startTime').val('2015-01-06')")
    @b.execute_script("$('input#endTime').val('2015-01-07')")
  when 'status'
    @b.select(:name => 'status').select_value '0'
  end
end

And /^I click edit button$/ do
  @b.input(:id => 'submitBtn').click
end

Then /^I go to the project list Page$/ do
  begin
    Watir::Wait.until(timeout = 10) {@b.url == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'}
  rescue
    @b.url.should == 'http://luthertsai.com/2015softwareengineering/Project/projectList.html'
  end
end

And /^I can see the (.+) that has been modified$/ do |attribute|
  begin
    Watir::Wait.until(timeout = 10) {@b.table(:class => 'listTable').exists?}
  rescue
    @b.table(:class => 'listTable').exists?.should == true
  end
  size = @b.table(:class => 'listTable').rows.length
  target = @b.table(:class => 'listTable')[size - 2]
  case attribute
  when 'project_name'
    target[0].a.text.should == modify
  when 'project_company'
    target[1].text.should == modify
  when 'date'
    target[3].text.should == '2015/01/06'
    target[4].text.should == '2015/01/07'
  when 'status'
    target[5].text.should == 'Close'
  end
end
