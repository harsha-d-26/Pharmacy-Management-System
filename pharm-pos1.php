<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="./CSS/nav2.css">
<link rel="stylesheet" type="text/css" href="./CSS/form3.css">
<link rel="stylesheet" type="text/css" href="./CSS/table2.css">
<title>
New Sales
</title>
</head>

<body>

		<div class="sidenav">
			
			
			<a href="pharmmainpage.php">Dashboard</a>
			
			<a href="pharm-inventory.php">View Inventory</a>
			<a href="pharm-pos1.php">Add New Sale</a>
			<button class="dropdown-btn">Customers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="pharm-customer.php">Add New Customer</a>
				<a href="pharm-customer-view.php">View Customers</a>
			</div>
	</div>

	<?php
		include "config.php";
		session_start();
	
		$sql="SELECT E_FNAME from EMPLOYEE WHERE E_ID='$_SESSION[user]'";
		$result=$conn->query($sql);
		$row=$result->fetch_row();
		
		$ename=$row[0];
	?>

	<div class="topnav">
	<h2 style="font-family:Arial; color:white;float:left;margin-left:20px"> Pharmacy Management System </h2>
		<a href="logout1.php">Logout(signed in as <?php echo $ename; ?>)</a>
	</div>
	
	<center>
	<div class="head">
	<h2> POINT OF SALE</h2>
	</div>
	</center>
	

	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<center>
		
		<select id="cid" name="cid">
        <option value="0">*Select Customer Name (only once for a customer's sales)</option>
					
					
	<?php	
			
		include "config.php";
		$qry="SELECT c_fname,c_lname,c_id FROM customer";
		$result= $conn->query($qry);
		echo mysqli_error($conn);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<option value=".$row["c_id"].">".$row["c_fname"]." ".$row["c_lname"]."</option>";
			}
		}
	?>
		
    </select>
	&nbsp;&nbsp;
	<input type="submit" name="custadd" value="Add to Proceed.">
	</form>
	
		
	<?php
	
					
			
			
		if(isset($_POST['custadd'])) {

			if(isset($_POST['cid'])){
				$cid=$_POST['cid'];	
				$_SESSION['c_id']=$cid;			
			}

				 if($cid!=0){				
				$qry2="INSERT INTO sales(c_id,e_id) VALUES ('$cid','$_SESSION[user]')"; 
				if(!($result2=$conn->query($qry2))) {
					echo "<p style='font-size:8; color:red;'>Invalid! Enter valid Customer ID to record Sales.</p>";
				}
				
			}
				else
				echo "<script>alert('Select Customer')</script>";

				
				
		}
		
	?>
			
		<form method="post">
			<select id="med" name="med">
			<option value="0" selected="selected">Select Medicine</option>
					
					
	<?php	
		$qry3="SELECT med_name FROM meds";
		$result3 = $conn->query($qry3);
		echo mysqli_error($conn);
		if ($result3->num_rows > 0) {
			while($row4 = $result3->fetch_assoc()) {
				
				echo "<option>".$row4["med_name"]."</option>";
			}
		}
	?>
		
    </select>
	&nbsp;&nbsp;
	<input type="submit" name="search" value="Search">
	</form>
	
	<br><br><br>
	</center>
	

	<?php
	
		if(isset($_POST['search'])) {
					if(! empty($_POST['med'])){
					$med=$_POST['med'];
					$qry4="SELECT * FROM meds where med_name='$med'";
					$result4=$conn->query($qry4); 
					$row4 = $result4 -> fetch_row();
					}		
					else
					echo "<script>alert('Select Medicine')</script>";
			}
			

	?>
	
			<div class="one row" style="margin-right:160px;">
			<form method="post">
					<div class="column">
					
					<label for="medid">Medicine ID:</label>
					<input type="number" name="medid" value="<?php echo $row4[0]; ?>"readonly ><br><br>
					
					<label for="mdname">Medicine Name:</label>
					<input type="text" name="mdname" value="<?php if(isset($row4[1])&& $_POST['med']!=0){ echo $row4[1];} else echo ""; ?>" readonly><br><br>
					
					</div>
					<div class="column">
					
					<label for="mcat">Category:</label>
					<input type="text" name="mcat" value="<?php if(isset($row4[3])&& $_POST['med']!=0){ echo $row4[3];} else echo ""; ?>" readonly><br><br>
					
					<label for="mloc">Location:</label>
					<input type="text" name="mloc" value="<?php if(isset($row4[5])&& $_POST['med']!=0){ echo $row4[5];} else echo "";; ?>" readonly><br><br>
					
					</div>
					<div class="column">
					
					<label for="mqty">Quantity Available:</label>
					<input type="number" name="mqty" value="<?php echo $row4[2]; ?>" readonly><br><br>
					
					<label for="mprice">Price of One Unit:</label>
					<input type="number" name="mprice" value="<?php echo $row4[4]; ?>" readonly><br><br>
					
					</div>
					<label for="mcqty">Quantity Required:</label>
					<input type="number" name="mcqty" default="0">
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="add" value="Add Medicine">&nbsp;&nbsp;&nbsp;		
		
		</form>
	</div>

<?php
	if(isset($_POST['add'])) {
					// if($_POST['med']!=0){
			
					$qry5="select sale_id from sales ORDER BY sale_id DESC LIMIT 1";
					$result5=$conn->query($qry5); 
					$row5=$result5->fetch_row();
					$sid=$row5[0];
					echo mysqli_error($conn);
			
					$mid=$_POST['medid'];
					$aqty=$_POST['mqty'];
					$qty=$_POST['mcqty'];
					
					if($qty>$aqty||$qty==0||empty($qty))
					{echo "<script>alert('Quantity Invalid')</script>";
						
					}
					else {
					$price=$_POST['mprice']*$qty;
					$qry6="INSERT INTO sales_items(`sale_id`,`med_id`,`sale_qty`,`tot_price`) VALUES($sid,$mid,$qty,$price)";
					$result6 = mysqli_query($conn,$qry6);                    
					echo mysqli_error($conn);
					
					
					}
				}	
				?>

	<div class="one row" style="margin-right:160px;">
				
		<?php

			if(isset($_POST['custadd'])||isset($_POST['add'])||isset($_POST['search'])){
			$q="SELECT c_fname, c_lname FROM CUSTOMER WHERE C_ID=$_SESSION[c_id]";
			$r=$conn->query($q);
			$rw=$r->fetch_row();
			$c_name=$rw[0]." ".$rw[1];

			echo "<label for='custname'>Customer Name:</label>
			<input type='text' name='custname' value='$c_name' readonly><br><br>";
			}
			if(empty($sid))
			{
				$sql ="SHOW TABLE STATUS LIKE 'sales'";
	
				if(!$result = $conn->query($sql)){
				die('There was an error running the query [' . $conn->error . ']');
				}
				$row = $result->fetch_assoc();
				$sid=$row['Auto_increment']-1;
			}
				
				echo "<table align='left' id='table1'>
				<tr>
					<th>Medicine ID</th>
					<th>Medicine Name</th>
					<th>Quantity</th>
					<th>Price</th>
					<th>Total Price</th>
					<th>Action</th>
				</tr>";

				$qry1 = "SELECT med_id,sale_qty,tot_price FROM sales_items where sale_id=$sid";
				$result1 = $conn->query($qry1);
				$sum=0;

				if ($result1->num_rows > 0) {
		
				while($row1 = $result1->fetch_assoc()) {
			
				$medid=$row1["med_id"];
				$qry2 = "SELECT med_name,med_price FROM meds where med_id=$medid";
				$result2 = $conn->query($qry2);
				$row2=$result2->fetch_row();
				
				echo "<tr>";
					echo "<td>" . $row1["med_id"]. "</td>";
					echo "<td>" . $row2[0] . "</td>";
					echo "<td>" . $row1["sale_qty"]. "</td>";
					echo "<td>" . $row2[1] . "</td>";
					echo "<td>" . $row1["tot_price"]. "</td>";
					echo "<td align=center>";
					echo "<a name='delete' class='button1 del-btn' href=pos-delete.php?mid=".$row1['med_id']."&slid=".$sid.">Delete</a>";
					echo "</td>";
				echo "</tr>";
				}
				echo "</table>";
			}


			
				$res=mysqli_query($conn,"SET @p0=$sid;");
				$res=mysqli_query($conn,"CALL `TOTAL_AMT`(@p0,@p1);") or die(mysqli_error($conn));
				$res=mysqli_query($conn,"SELECT @p1 as TOTAL;");
				
				while($row3=mysqli_fetch_array($res))
				{
					$tot=$row3['TOTAL'];
				}
				
				echo "<table align='left' id='table1'>
					
				<tr style='background-color: #f2f2f2;'>
				<td>Total</td>
				<td>";echo $tot;
				echo "</td>
				</tr>
				</table>";	
		?>

	</div>

	<div class="one" style="background-color:white;">
		<form method=post>
		
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<a name='cancel' class='button1 view-btn' href=pharm-bill.php>Complete order</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<input type='submit' name='cancel' value='Cancel Order'><br>
		
		</form>
		</div>

	<?php
		if(isset($_POST['cancel']))
		{
			$qr1="DELETE FROM `SALES` WHERE SALE_ID=$sid";
			$del_res=$conn->query($qr1);
			if($del_res)
			{
				echo("<meta http-equiv='refresh' content='0'>");
			}
			else
			echo "Failed to cancel";
		}
	?>
	
</body>

<script>
		var dropdown = document.getElementsByClassName("dropdown-btn");
		var i;

			for (i = 0; i < dropdown.length; i++) {
			  dropdown[i].addEventListener("click", function() {
			  this.classList.toggle("active");
			  var dropdownContent = this.nextElementSibling;
			  if (dropdownContent.style.display === "block") {
			  dropdownContent.style.display = "none";
			  } else {
			  dropdownContent.style.display = "block";
			  }
			  });
			}		
</script>

</html>