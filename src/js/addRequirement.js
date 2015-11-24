// [BC] 這個function用來檢查有沒有重複的requirement名稱
function checkRequirementName(){
	// [BC] 取得form的資料
	var partOfForm = {
		'pid'				: $('input[id=pid]').val(),
		'requirementName'	: $('input[id=requirementName]').val(),
	};
	
	// [BC] 檢查requirement是否為空
	if(partOfForm.requirementName == null || partOfForm.requirementName == ""){
		
		$('div[id=name]').html("<span style='color: red'>Name:   <- Do not make name empty!!</span>");
		return false;
	}

	// [BC] 做POST
	var posting = $.post("checkRequirementName.php", partOfForm);
	// [BC] 完成POST之後，檢查response的內容
	posting.done(
		function(response){
			try {
	            var r = $.parseJSON(response);
	        } catch (err) {
	            alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + data);
	            return false;
	        }
	        // [BC] 檢查requirement name 是否在這一專案中重複
	        if(r.SUCCESS == 0){
	        	$('div[id=name]').html("<span style='color: red'>Name:   <- this name has been used</span>");
	        	return false
	        }

	        $('div[id=name]').html("Name:");
	        return true;
		}
	);

	// [BC] 檢查name的內容，來決定return的值
	if($('div[id=name]').text() == "Name:"){
		return true;
	}
	else{
		return false;
	}
}

// [BC] 這個function用來做addRequriement，重新導致addRequirement.php，並且避免重複輸入表單
function addRequirement(){
	// [BC] 取得form的資料
	var form = {
		'pid'				: $('input[id=pid]').val(),
		'uid'				: $('input[id=uid]').val(),
		'requirementName'	: $('input[id=requirementName]').val(),
		'typeName'			: $('SELECT[id=typeName]').val(),
		'priority'			: $('SELECT[id=priority]').val(),
		'requirementDes'	: $('textarea[id=requirementDes]').val()
	};

	// [BC] 做POST
	var posting = $.post("addRequirement.php", form);
	// [BC] 完成POST之後，檢查response的內容
	posting.done(
		function(response){
			try {
	            var r = $.parseJSON(response);
	        } catch (err) {
	            alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + response);
	            return;
	        }
	        if(r.SUCCESS == 1){
	        	document.location.href = "requirementListView.php?pid=" + form.pid;
	        } else {
	        	alert('adding requirement failed\nthe error message = ' + r.MESSAGOE);
	        }
		}
	);
}

function finalCheck(){
	// [BC] 取得form的資料
	var partOfForm = {
		'pid'				: $('input[id=pid]').val(),
		'requirementName'	: $('input[id=requirementName]').val(),
	};

	var posting = $.post("checkRequirementName.php", partOfForm);
	// [BC] 完成POST之後，檢查response的內容
	posting.done(
		function(response){
			try {
	            var r = $.parseJSON(response);
	        } catch (err) {
	            alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + data);
	            return false;
	        }
	        // [BC] 檢查requirement name 是否在這一專案中重複
	        if(r.SUCCESS == 0){
	        	$('div[id=name]').html("<span style='color: red'>Name:   <- this name has been used</span>");
	        	return false
	        }

	        $('div[id=name]').html("Name:");
	        addRequirement();
		}
	);
}