<!DOCTYPE HTML>
<html>

<head>
    <title>Edit Project</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../css/w3.css">
    <link rel="stylesheet" href="../css/dateRangePicker.css">
    <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../js/moment-with-locales.js"></script>
    <script type="text/javascript" src="../js/jquery.daterangepicker.js"></script>
</head>
<style>
.fastAccount {
    background-color: grey;
    border-radius: 5px;
    float: right;
}

.fastAccountBlock {
    width: 10;
    float: right;
}
</style>

<body class="w3-container" style="background-color:rgb(61, 61, 61)">
    <?php $pid = $_GET['pid']; ?>
    <br>
    <div class="w3-row ">
        <div style="float:left">
            <img src="../imgs/ptsIcon.png" alt="ICON" width="100" Height="30" />
        </div>
        <div class="w3-container" id="userName" style="float:right;color:white;font-size:18">
			Welcome!
		</div>
		<script type="text/javascript" src="../js/getUser.js"></script>
    </div>
    <div class="w3-row " style="Height:30%;color:white;text-align:center">
        <h1 style="background-color:grey;border-radius:5px">
					Edit Project
			</h1>
    </div>
    <div class="w3-row " style="Height:40%">
        <div class="w3-col m4">
            <p></p>
        </div>
        <div class="w3-col m4 " style="background-color:rgb(40, 40, 40);border-radius: 15px">
            <div class="w3-col m4">
                <p></p>
            </div>
            <form action="editProject.php" method="POST" id="editProject">
                <br>
                <div class="w3-row formBlock" style>
                    <div class="w3-col m2">
						<p></p>
                    </div>
                    <div class="w3-col m4" align="left">
						<input id="pid" name="pid" type="hidden" value="<?=$pid; ?>" />
						<?php
							/* Database Setting */
							require_once '../assist/DBConfig.php';
							
							// Create connection
							$conn = mysqli_connect($dburl, $dbuser, $dbpass, $db);
							// Check connection
							if (!$conn)
							{
								die("Connection failed: " . mysqli_connect_error());
							}

							//Show Chinese Chracters Correctly
							mysqli_query($conn, "SET NAMES 'UTF8'");
							
							//Insert Course Criticize to SQL
							$select = "SELECT * FROM project WHERE p_id = " . $pid . ";";
							$result = mysqli_query($conn, $select);
							
							if ($row = $result->fetch_array(MYSQLI_ASSOC))
							{
								$feedback = $row;
								$feedback['success'] = '1';
							}
							else
							{
								echo "Error: " . $sql . "<br>" . mysqli_error($conn);
								exit;
							}
						?>
                        <br>
                        <font color="white">Name:</font>
                        <br>
                        <input id="name" type="text" name="name" required style="border-radius: 3px" placeholder="Enter a name" value="<?=$row["p_name"] ?>"/>
                        <font color="white">Company:</font>
                        <br>
                        <input id="company" type="text" name="company" required style="border-radius: 3px" placeholder="Enter the company's name" value="<?=$row["p_company"] ?>"/>
                        <br>
                        <font color="white">Start Time:</font>
                        <br>
                        <div id="date_picker">
							<input id="startTime" type="datetime" name="startTime" required style="border-radius: 3px" value="<?=$row["p_start_time"] ?>"/>
							<br>
							<font color="white">End Time:</font>
							<br>
							<input id="endTime" type="datetime" name="endTime" required style="border-radius: 3px" value="<?=$row["p_end_time"] ?>"/>
                        </div>
                        <script>
							function getExceptDays() {
									var startTimeObj = document.getElementById("startTime");
									var startTime = startTimeObj.value;
									var endTimeObj = document.getElementById("endTime");
									var endTime = endTimeObj.value;
									var days = document.getElementById("days");
									startTime = moment(startTime, "YYYY-MM-DD HH:mm:ss");
									endTime = moment(endTime, "YYYY-MM-DD HH:mm:ss");
									var diffDays = endTime.diff(startTime, 'days') + 1;
									days.innerHTML = "Except: " + diffDays.toString() + " Days";
							}
							
							$("#date_picker").dateRangePicker({
								separator : 'to',
								format: 'YYYY-MM-DD HH:mm:ss',
								getValue: function()
								{
									if ($('#startTime').val() && $('#endTime').val() )
										return $('#startTime').val() + ' to ' + $('#endTime').val();
									else
										return '';
								},
								setValue: function(s,s1,s2)
								{
									$('#startTime').val(s1);
									$('#endTime').val(s2);
									getExceptDays();
								}
							});
                        </script>
						<font color="gray"><span id="days">Expect:</span></font>
						<script>
							$(document).ready(function() {
								getExceptDays();
								var startTimeObj = document.getElementById("startTime");
								var endTimeObj = document.getElementById("endTime");
								startTimeObj.onchange = getExceptDays;
								endTimeObj.onchange = getExceptDays;
							});
						</script>
                        <br>
                        <font color="white">Status:</font>
                        <br>
                        <select name="status" id="status" required style="border-radius: 3px" form="editProject" <?php if($row["status"] == 3) echo("disabled='disabled'");  ?>>
						  <option value=0 <?php if($row["status"] == 0) echo("selected='selected'"); ?>>Close</option>
						  <option value=1 <?php if($row["status"] == 1) echo("selected='selected'");  ?>>Open</option>
						  <option value=2 <?php if($row["status"] == 2) echo("selected='selected'");  ?>>Terminated</option>
						  <option value=3 hidden="hidden">Delete</option>
						</select>
                        <br>
                        <font color="white">Description:</font>
                        <br>
                        <textarea rows="4" name="des" id="des" width="322px"><?=$row["p_des"] ?></textarea> 
                        <br>
                        <font color="red"><span id="error"></span></font>
                        <br>
                        <input id="submitBtn" type="button" value="Add" class="w3-teal">
                        <script>
							$('#submitBtn').click(function() {
								$.post("projectCheck.php",{Company:$("#company").val(), Project_Name:$("#name").val(), ProjectID: $("#pid").val()})
									.done(function(data) {
										var check_result = $.parseJSON(data);
										console.log("Check: " + check_result.SUCCESS);
										if(check_result.SUCCESS == "1")
										{
											$('#error').html("");
											console.log(document.getElementById("editProject").submit);
											document.getElementById("editProject").submit();
											console.log('OK');
										}
										else
										{
											$('#error').html("Update error!");
										}
									});
							});
                        </script>
                        <input type="button" name="exit" value="Exit" class="w3-teal" onclick="location.href='projectList.html';">
                        <br>
                        <br>
                </div>
            </form>
            <div class="w3-col m4">
                <p></p>
            </div>
        </div>
        <div class="w3-col m4">
            <p></p>
        </div>
    </div>
    <div class="w3-row " style="Height:rest">
        <p></p>
    </div>
</body>
<footer style="Height:rest;text-align:center">
    <span style="text-decoration:underline;color:white">About Us</span>
</footer>

</html>
