<?php
include("header.php");
?>
<style>
select.form-control {
    color: black;
}
textarea.form-control {
    height: 4rem;
}
.img{
	
    display: inherit;

}
.table td img {
    width: 40px;
    height: 50px;
    border-radius: 0;
}
.action {
    margin-top: 20px;
}
</style>


<?php
error_reporting(E_ERROR | E_PARSE);
$oid=$_GET["id"];
if(isset($_POST['takeaction']))
{

    $status=$_POST['ostatus'];
    $remark=$_POST['remark'];
    $actionby=$_SESSION['admin'];
    $canceledBy='Admin';

 if($status=='Cancelled'):
 $query="insert into ordertrackhistory(orderId,status,remark,actionBy,canceledBy) values('$oid','$status','$remark','$actionby',' $canceledBy');";
   
   $query.="update orders set orderStatus='$status' where id='$oid'";
else:
  $query="insert into ordertrackhistory(orderId,status,remark,actionBy) values('$oid','$status','$remark','$actionby');";
   $query.="update orders set orderStatus='$status' where id='$oid'";
endif;    
$result = mysqli_multi_query($con, $query);
    if ($result) {
    
    echo '<script>alert("Action has been updated successfully")</script>';
    echo "<script>window.location.href ='report_book.php'</script>";
  }
  else
    {
     echo '<script>alert("Something Went Wrong. Please try again.")</script>';
    }
}




?>    

<?php
$query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid AND a.id='$oid'";  
$result = mysqli_query($con, $query); 						  
$row = mysqli_fetch_array($result);				  
?>
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Order Details</h4>
               
           

 <div class="row">
                                    <div class="col-md-6">
                                <table class="table table-bordered" border='1' width="100%">
                            
                                        <tr>
                                            <th colspan="2" style="text-align:center;">Order Details</th>
                                        </tr>
                                        <tr>
                                            <th>Order No.</th>
                                            <td><?php echo htmlentities($row['orderNumber']);?></td>
                                            </tr>
                                            <tr>
                                            <th>Order Amount</th>
                                            <td> <?php echo htmlentities($row['totalAmount']);?></td>
                                            </tr>
                                            <tr>
                                            <th>Order Date</th>
                                            <td><?php echo htmlentities($row['orderDate']);?></td>
                                        </tr>
                                        <tr>
                                            <th>Order Status</th>
                                               <td><?php $ostatus=$row['orderStatus'];
                                               if($ostatus==''):
                                                echo "Not Processed Yet";
                                            else:
                                                echo $ostatus;
                                            endif;

                                           ?></td>
                                           </tr>                  

                               
                                       
                                    </tbody>
                                </table>
								</div>
<!--Cutomer /Users Details --->
 <div class="col-md-6">
        <table class="table table-bordered" border="1" width="100%">
                                        <tr>
                                            <th colspan="2" style="text-align:center;">User Details</th>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td><?php echo htmlentities($row['name']);?></td>
                                            </tr>
                                            <tr>
                                            <th>Email ID </th>
                                            <td> <?php echo htmlentities($row['email']);?></td>
                                            </tr>
                                            <tr>
                                            <th>Phone No</th>
                                            <td><?php echo htmlentities($row['phone']);?></td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                               <td><?php echo htmlentities($row['address']);?>
                                               </td>
                                           </tr>                 

                                    
                                       
                                    </tbody>
                                </table></div>
	</div>				
<!-- Products / Item Details --->
 <div class="row">
 <div class="col-md-12">
        <table class="table table-bordered table-responsive" border="1">
                                        <tr>
                                            <th colspan="6" style="text-align:center;">Book Details</th>
                                        </tr>
                                        <tr>
                                            <th>S.No</th>
											<th>Book Image</th>
                                            <th>Book Name</th>
                                            <th>Book Price </th>
                                            <th>Book Qty</th>
                                            <th>Total Amount</th>
                                        </tr>
<?php 
$x=1;
$query ="SELECT a.*,b.*,c.* FROM orders AS a, ordersdetails AS b, books AS c WHERE a.orderNumber=b.orderNumber AND b.productId=c.book_id AND a.id='$oid'";  
$result = mysqli_query($con, $query); 						  
while($row=mysqli_fetch_array($result))
{
?>  

             <tr>
					<td><?php echo $x; ?></td>
                    <td><img src="../images/<?php echo htmlentities($row['image']);?>" alt="<?php echo htmlentities($row['book']);?>" width="100px" height="82px"></td>
                    <td><?php echo htmlentities($row['book']);?></td>
					<td><?php echo htmlentities($row['price']);?></td>
                    <td><?php echo htmlentities($row['quantity']);?></td>
                    <td><?php echo htmlentities($totalamount=$row['quantity']*$row['price']);?></td>
             
                </tr>
<?php 
$grandtotalamount+=$totalamount;
$grandtshipping+=$tshipping;
$x++;
} ?>

<tr>
    <th colspan="4" style="text-align:right;">Grand-Total</th>
    <th colspan="2" style="text-align:center;"><?php echo htmlentities(round($grandtotalamount,2));?></th>
</tr>
                                </table>
								</div>
 </div>				  					
					
					

<?php if($ostatus==''|| $ostatus=='Packed' || $ostatus=='Dispatched' || $ostatus=='In Transit' || $ostatus=='Out For Delivery'): ?>
<div align="center" class="action"><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Take Action</button>
</div>
<?php endif;?>
                </div>
              </div>
            </div>
         
          </div>
        </div>
        <!-- content-wrapper ends -->


<?php
include("footer.php");
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
<form method="post" name="takeaction">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update the Order Status</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<p><select name="ostatus" class="form-control" required>
    <option value="">Select</option>
    <?php if($ostatus==''): ?>
        <option value="Cancelled">Rejected</option>
    <option value="Packed">Accepted</option>
    <option value="In Transit">In Transit</option>
    <option value="Delivered">Delivered</option>
    <?php elseif($ostatus=='Packed'):?>
    <option value="In Transit">In Transit</option>
    <option value="Delivered">Delivered</option>
    <?php elseif($ostatus=='In Transit'):?>
    <option value="Delivered">Delivered</option>
        <?php endif;?>
</select></p>
<p>
<textarea class="form-control" required name="remark" placeholder="Remark"></textarea></p>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" name="takeaction">Save changes</button></div>
        </div>
    </form>
    </div>
</div>
</div>

        <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
               <script>
function CallPrint(strid) {
var prtContent = document.getElementById("print");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
}

</script>