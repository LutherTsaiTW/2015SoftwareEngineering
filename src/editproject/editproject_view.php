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
    <br/>
    <div class="w3-row ">
        <div style="float:left">
            <img src="../imgs/pts_icon.png" alt="ICON" width="100" Height="30" />
        </div>
        <div class="w3-container fastAccount">
            Login
        </div>
        <div class="fastAccountBlock">
            <p/>
        </div>
        <div class="w3-container fastAccount">
            Register
        </div>
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
            <form action="" method="POST">
                <input type="hidden" name="pid" value="<?=$pid; ?>" />
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
