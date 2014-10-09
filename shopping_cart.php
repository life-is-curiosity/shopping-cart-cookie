
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
       <title>A&L Shopping Cart</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="cartstyle/css/bootstrap.css" rel="stylesheet"/>
        <link href="cartstyle/css/main.css" rel="stylesheet"/>
        <link href="cartstyle/css/jquery.css" rel="stylesheet">
    </head>
    <body>
        

            <div class="row">

                <div class="span12">
                    <h1> Shopping Cart</h1><br />

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Remove</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Model</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="shopping_cart.php" method="get">
                            <?php
							session_start();
					
								 $con=mysqli_connect("localhost","admin","admin","final_year_project");

								// Check connection
								if (mysqli_connect_errno($con))
								{
								  echo "Failed to connect to MySQL: " . mysqli_connect_error();
								}
								$totalprice = 0;
								$cookie=$_COOKIE; // Throw all cookies on a value
								$pd_qty = 0;
								
								$n = 1;
								$i = 1;
								
								for($k = 1; $k < 20; $k++){
									if(!isset($_COOKIE[$k]))
										$_COOKIE[$k] =1;
								}
								
								foreach ($cookie as $key=>$val)
								{
									if($key != "PHPSESSID")
									{
										//echo "<br>$key--> $val";
										 
										$result = mysqli_query($con,"SELECT * FROM Products where pd_id = '".$key."';");

										if($row = mysqli_fetch_array($result))
										{
											echo "<tr>";
											echo "<td><input type='checkbox' name='".$row['pd_id']."' 
											id='optionsCheckbox'></td>";
											
											echo "<td class='muted center_text'>
											<img width='75' height='75' src='content/img/products/image/".$row["pd_id"].".jpg'/></td>";
											
											
											echo "<td>".$row['pd_name']."</td>";
											echo "<td>".$row['pd_name']."</td>";
											$pd_qty = $val;
											echo "<td>".$val."</td>";
											
											/*echo "<td><select name = '".$i."'>";
											for($p = 0; $p <= $row['pd_qty'] ; $p++)
											{
												if($p == 0)
												{
													echo "<option selected value='".$_COOKIE[$i]."'>".$_COOKIE[$i]."</option>";
												}
												else
												{
													echo "<option value='".$p."'>".$p."</option>";	
												}
											}
											echo "</select></td>";
											*/
											
											/*echo "<td><input type ='text' value ='".$_COOKIE[$i]."' name='".$i."' id = '".$i."'>";	 
											echo  "</input></td>";
											*/
											

											echo "<td>HKD ".$row['pd_price']."</td>";
											echo "<td>HKD ".$row['pd_price']* $val ."</td>";
											echo "</tr>";
										  	$totalprice = $totalprice + ($row['pd_price'] * $val);
											$i = $i + 1;
										}
									}
								}
								//$totalprice *= $_SESSION['qty'];
								echo "<tr>";
								echo "<td></td>";
								echo "<td></td>";
								echo "<td></td>";
								echo "<td></td>";
								echo "<td></td>";
								echo "<td><b>Total</b></td>";
								if(!isset($_COOKIE['discounted'])){
									echo "<td align='left'><b>HKD ".$totalprice."</b></td>";
								}else{
									echo "<td align='left'><b>HKD ".($totalprice *= $_COOKIE['discounted'])."</b></td>";
									//setcookie('discounted',"");
								}
								echo "</tr>";
						?>		  
                        </tbody>
                    </table>
						
                    
                        <fieldset>
                            <div class="accordion" id="accordion2">
                                <div class="accordion-group">
                                    <div class="accordion-heading">

                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                            <h3>Apply discount code</h3>
                                        </a>
                                    </div>
                                    <div id="collapseOne" class="accordion-body collapse in">
                                        <div class="accordion-inner">
                                            <div class="control-group">
                                                <label for="input01" class="control-label">Discount code: </label>
                                                <div class="controls">
                                                    <input type="text" id="input01" name = "discountcode" class="input-xlarge" placeholder="Enter your coupon here">
                                                    <p class="help-block">You can only use one discount code at a time</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                            <div class="row">
                                <div class="span5">
                                
                                 
								<input type = "submit" name = "refresh" value="Save Cart" class="btn btn-primary"/>
                     
							</form>
                            

								<script>
                                	function renewQty(i, val){
										var qty = document.getElementById(i);
										qty.value = val;
									}
                                </script>
								<?php
								
								 if(isset($_GET['refresh']) || isset($_POST['checkout']))
								 {
									foreach ($_COOKIE as $key => $val)
									{
										if(isset($_GET[$key]) && $key != "PHPSESSID")
										{
											setcookie($key,"");
										}
									}

									while($n <= 20){
										if(isset($_GET[$n])){
											setcookie($n,$_GET[$n]) ;
											echo "<script>renewQty(".$n.",".$_COOKIE[$n].");</script>";
											$n++;
										}else{
											break;	
										}
									}
									
									 $query = sprintf("SELECT * FROM discount_codes where discount_code = '%s' AND activate = 1",mysql_real_escape_string($_GET['discountcode']));
			
									$checkcode = mysqli_query($con, $query);
									
									if($row = mysqli_fetch_array($checkcode))
									{
										mysqli_query($con,"UPDATE discount_codes SET activate = 0 where discount_code = '".$_GET['discountcode']."'");
										setcookie("discounted",$row['discount']);
										//header("Location: shopping_cart.php");
									}
									if(isset($_GET['refresh']))
										header("Location: shopping_cart.php");
									elseif(isset($_POST['checkout']))
										header("Location: checkout.php");
								 } 
								
								 ?>
                                 
                                </div>		  
                                <div class="span2">
                                 
                                    <button class="btn btn-primary" type="submit" onClick="window.close();">Continue shopping</button> 
                                </div>		  
                                <div class="span5" align="right">
                                <?php
                                    echo "<a href='checkout.php' class='btn btn-primary pull-right'>Go to Checkout Page</a>";
                                ?>
                                </div>
                            </div>
                        </fieldset>
                        
                    
            
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
    </body>
</html>