<?php $print = $_GET['print']; ?>

<html>
	<head>
		<title>Print Report</title>
	</head>
	<body>
		<?php
			if($print == 0 || $print == 1)
			{
				echo('<div style="font-size:30px"><b>Requirements</b></div>');
				require_once('reqListTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 2)
			{
				echo('<div style="font-size:30px"><b>Requirement Relation</b></div>');
				require_once('reqRelationTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 3)
			{
				echo('<div style="font-size:30px"><b>Test Case Requirement Relation</b></div>');
				require_once('reqTestcaseTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 4)
			{
				echo('<div style="font-size:30px"><b>Requirements without Test Case</b></div>');
				require_once('reqNoTestcaseTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 5)
			{
				echo('<div style="font-size:30px"><b>Test Case in special condition</b></div>');
				require_once('testcaseNoReqTable.php');
				echo('<br><br>');
			}
		?>
		<script>
			print();
		</script>
	</body>
</html>
