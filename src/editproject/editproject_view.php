<!DOCTYPE HTML>
<html>

<head>
    <title>Edit Project</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
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
            <img src="imgs/pts_icon.png" alt="ICON" width="100" Height="30" />
        </div>
        <div class="w3-container" id="userName" style="float:right;color:white;font-size:18">
			Welcome!
		</div>
		<script type="text/javascript" src="js/getUser.js"></script>
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
            <form action="EditProject.php" method="POST" id="editProject">
                <br>
                <div class="w3-row formBlock" style>
                    <div class="w3-col m2">
						<p></p>
                    </div>
                    <div class="w3-col m4" align="left">
						<input id="pid" name="pid" type="hidden" value="<?=$pid; ?>" />
						<?php
							/* Database Setting */
							$dburl = "";
							$dbuser = "";
							$dbpass = "";
							$db = "2015softwareengineering";
							
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
                        <input id="startTime" type="datetime" name="startTime" required style="border-radius: 3px" value="<?=$row["p_start_time"] ?>"/>
                        <br>
                        <font color="white">End Time:</font>
                        <br>
                        <input id="endTime" type="datetime" name="endTime" required style="border-radius: 3px" value="<?=$row["p_end_time"] ?>"/>
                        <br>
						<font color="gray"><span id="days" style="margin-left: 84px;">Except:</span></font>
						<script>
							(function() {
								getExceptDays();
								var startTimeObj = document.getElementById("startTime");
								var endTimeObj = document.getElementById("endTime");
								startTimeObj.onchange = getExceptDays;
								endTimeObj.onchange = getExceptDays;
							})();

							function getExceptDays() {
									var oneDay = 24*60*60*1000;
									var startTimeObj = document.getElementById("startTime");
									var startTime = startTimeObj.value;
									var endTimeObj = document.getElementById("endTime");
									var endTime = endTimeObj.value;
									var days = document.getElementById("days");
									startTime = new Date(startTime);
									endTime = new Date(endTime);
									var diffDays = Math.round(Math.abs((startTime.getTime() - endTime.getTime())/(oneDay)));
									days.innerHTML = "Except: " + diffDays.toString() + " Days";
							}
						</script>
                        <br>
                        <font color="white">Status:</font>
                        <br>
                        <select name="status" id="status" required style="border-radius: 3px" form="editProject">
						  <option value=1 <?php if($row["p_start_time"] == 1) echo("default"); ?>>進行中</option>
						  <option value=2 <?php if($row["p_start_time"] == 2) echo("default");  ?>>完成</option>
						  <option value=3 <?php if($row["p_start_time"] == 3) echo("default");  ?>>中止</option>
						</select>
                        <br>
                        <font color="white">Description:</font>
                        <br>
                        <textarea rows="4" name="des" id="des" width="322px">
							<?=$row["p_des"] ?>
						</textarea> 
                        <br>
                        <input type="submit" name="submit" value="Add" class="w3-teal">
                        <input type="button" name="exit" value="Exit" class="w3-teal" onclick="location.href='projectlist.html';">
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
