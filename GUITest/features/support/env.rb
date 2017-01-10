Before do
	@b = Watir::Browser.new
	@b.goto "https://luthertsai.com/2015softwareengineering/login.html"
end

After do
	@b.close
end
