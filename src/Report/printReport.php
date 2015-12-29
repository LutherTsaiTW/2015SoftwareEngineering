<?php $print = $_GET['print']; ?>

<html>
	<head>
		<title>Print Report</title>
	</head>
	<body>
		<?php
			if($print == 0 || $print == 1)
			{
				require_once('reqListTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 2)
			{
				require_once('reqRelationTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 3)
			{
				require_once('reqTestcaseTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 4)
			{
				require_once('reqNoTestcaseTable.php');
				echo('<br><br>');
			}
			if($print == 0 || $print == 5)
			{
				require_once('testcaseNoReqTable.php');
				echo('<br><br>');
			}
		?>
		<script>
			print();
		</script>
	</body>
</html>
