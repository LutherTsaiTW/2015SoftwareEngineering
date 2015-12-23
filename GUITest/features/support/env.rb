Before do
	@b = Watir::Browser.new
	@b.goto "http://luthertsai.com/2015softwareengineering/login.html"
end

After do
	@b.close
end
