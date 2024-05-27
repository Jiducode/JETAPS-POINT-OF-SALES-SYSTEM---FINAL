<?php

try{
$pdo = new PDO("mysql:host=localhost;dbname=pos_db","root","");
//echo "connection Sucessfull";

}catch(PDOException $f){
    
    echo $f->getmessage();
    
}




//include_once"conectdb.php";
session_start();
if ($_SESSION["useremail"]=="" ) {
    
    header("location:index.php");
    
}
include_once"headeruser.php";







?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">

          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">Recent Order</a></li>
              <li class="breadcrumb-item"><a href="order_user.php">Point of Sales</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

   
   
   
   
   
   
    <!-- Main content -->
    <section class="content container-fluid">
              <div class="row">
              
               <!-- left column -->
         
            

          <!--/.col (left) -->
          <!-- right column -->


       <div class="col-md-12">
            
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Order Data</h3>
              </div>
              <div class="card-body">
        
      <!--  <form  action="" method="POST"> -->
        <table id="example2" class="table table-bordered table-hover">
            
        <thead class="bg-lightblue">
             <tr>
                 
                 <td>Invoice ID</td>
                 <td>Customer name</td>
                 <td>Order Date</td>
                 <td>Total</td>
                 <td>Paid</td>
                 
                 
                <!-- <td>EDIT</td> 
                
                 also add this in td below
                 
                 <td><button input type=\"submit\" value=\".$row->catid.\" class=\"btn btn-success\" name=\"btnedit\">EDIT</button></td>
                  
                   
                     -->
                 <td>Change</td>
                 <td>Payment Type</td>
                 <td>Action</td>
                 <!--<td>Edit</td>-->
                 
             </tr>  
      
           </thead>
       
            <tbody>
     
           <?php
                
                $select=$pdo->prepare("select * from tbl_invoice order by invoice_id desc");
                $select->execute();
                
                while($row=$select->fetch(PDO::FETCH_OBJ)){
                        $total_paid = number_format($row->paid, 2);
                        $total_sales = number_format($row->total, 2);
                        $total_due = number_format($row->due, 2);
                  echo "
                    
                    <tr>
                    <td  class='justify-center' style='text-align: center;'>$row->invoice_id</td>
                    <td  class='justify-center' style='text-align: center;'>$row->customer_name</td>
                    <td  class='justify-center' style='text-align: center;'>$row->order_date</td>
                    <td class='justify-center' style='text-align: center;'>₱$total_sales</td>
                    <td class='justify-center' style='text-align: center;'>₱$total_paid</td>
                    <td class='justify-center' style='text-align: center;'>₱$total_due</td>
                    <td  class='justify-center' style='text-align: center;'>$row->payment_type</td>
                    
                   <td  class='justify-center' style='text-align: center;'>
                    <a href=\"invoice_80mm.php?id=".$row->invoice_id."\" 
                    class= \"btn btn-success\" role=\"button\" target=\"blank\" ><span class=\"fas fa-print\" name=\"PrintBtn\"    style=\"color:#ffffff\" data-toggle=\"tooltip\" title=\"Print Invoice\"></span>
                    </a>
                    </td>
                    
                    
                    
                    
                    
                    </tr>";
                    
                }
                
                
                
                
                ?>
                    
            
                
            </tbody>
            
            
        </table>
                  <!-- </form> -->
         
             </div>
               </div>
           
                
              
            </div>
        
        
        <!-- /.right column -->
        
          </div> 

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->












<script>
    
    
    
    $(document).ready(function () {
    var table = $("#example2").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "order": [[0, "desc"]],
        "buttons": [
            {
                extend: "csv",
                text: '<i class="fas fa-file-csv" style="color: green;"></i> CSV',
            },
            {
                extend: "excel",
                text: '<i class="fas fa-file-excel" style="color: orange;"></i> Excel',
            },
            {
                extend: "pdf",
                text: '<i class="fas fa-file-pdf" style="color: red;"></i> PDF',
            },
            "colvis",
        ],
    });

    table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    $("[data-toggle='tooltip']").tooltip();
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});

    
   
</script>





  <!-- Control Sidebar -->
 
 <?php
include_once "footer.php";


?>