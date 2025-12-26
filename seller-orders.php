<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/seller_Segments.php");

if(isset($_COOKIE["seller_username"]) && isset($_COOKIE["seller_password"])){
    $stmt = $pdo->prepare("SELECT * FROM `sellers` WHERE (seller_username = ? OR seller_email = ?) AND seller_password = ?");
    $stmt->execute([$_COOKIE["seller_username"], $_COOKIE["seller_username"], $_COOKIE["seller_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){//that means seller is logged in
        //~~(placing this here so that changes in "pending orders" can be reflected the tab on top of seller pages on updating status)
        //To Update Order Status:
        if(isset($_POST["order_id"])){
            //check if order still exists
            $edit_stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
            $edit_stmt->execute([$_POST["order_id"]]);
    
            $edit_data = $edit_stmt->fetch(PDO::FETCH_OBJ);
            if($edit_data){ 
                //then edit:
                $edd_stmt = $pdo->prepare("UPDATE orders SET `status` = ? WHERE order_id = ?");

                $edd_stmt->execute([htmlentities($_POST["order_status"]), htmlentities($_POST["order_id"])]);

                echo "<h4 style='color:green'>Order Status for: Bilo000", $edit_data->order_id, " updated successfully</h4>";
            } else {
                echo "<h4 style='color:gred'>Error: Order not found.</h4>";
            }
        } 

        //that means seller is logged in
        seller_Segments::header();
?>
        <div class="site_content"><!-- .site_content starts -->       
        <div class="dashboard_div" style="margin:-30px 3% 10% 3%;">

        <h1 style="margin:12px 6px">Orders - <?=$site_name?></h2>

        <!-- Add new product div starts -->
        <div>
            <div onclick="show_div('filter_options')" style="background-color:green;color:#fff;font-weight:bold;padding:6px 12px;border-radius:6px;margin:12px 0 18px 0;width:fit-content"><span>Filter By:</span> <i class="fa fa-angle-down" style="margin-left:12px;font-size:21px"></i></div>

            <div id="filter_options" style="display:none;padding:9px;background-color:#f3f3f3;border-radius:6px;border:1px dotted #000;position:fixed;top:30%;width:80%;line-height:24px">
                <div><a href="/orders?filter=processing" style="color:#f3d111">Processing</a></div>
                <div><a href="/orders?filter=delivered" style="color:green">Delivered</a></div>
                <div><a href="/orders?filter=cancelled" style="color:red">Cancelled</a></div>
                <div><a href="/orders" style="color:#000">-- View All --</a></div>
                <div class="edit_product_action_button" style="background-color:#ff9100;margin-top:15px;width:fit-content" onclick="show_div('filter_options')"><i class="fa fa-ban"></i>&nbsp; Close</div>
            </div>
        </div>
        <!-- Add new product div ends -->
<?php
        //check if seller is searching for someone:
?>
        <div style="margin-top:18px;padding:12px 9px;border:1px solid #000;border-radius:12px;background-color:#f3f3f3">
            <input type="text" onkeyup="ajax_search()" id="search_input" class="input" placeholder="Enter Order Title/Order ID: try: abc" style="border:1px solid #000;width:75%"/>
        
            <i class="fa fa-search" onclick ="search_icon()" style="padding:9px;border-radius:4px;font-size:16px;color:#fff;background-color:#000"></i>

            <div id="search" style="position:absolute;width:75%"></div>
        </div>
        
        <div style="margin-top:12px">    <!-- 'main' div starts -->
            <div class="table_row_div" style="margin-bottom:18px">
                <div class="table_row" style="width:8%">#</div>
                <div class="table_row">Product ID</div>
                <div class="table_row">Status</div>
                <div class="table_row">View</div>
            </div>
<?php
        //To Delete Product:
        /*
        if(isset($_POST["remove_product"])){
            //check if product still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
            $ds_stmt->execute([$_POST["remove_product"]]);
    
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
                $dd_stmt->execute([$_POST["remove_product"]]);

                echo "<h4 style='color:red'>Product: ", $ds_data->product_name, " has been deleted successfully</h4>";
            } else {
                echo "<h4 style='color:red'>Error: Product not found.</h4>";
            }
        }
        */
        //Mail Customer:


        //Select and view all orders for easy decision making:

        //A Simple Pagination Algorithm:
        $p = 1;
        $num_of_rows = 10;

        if(isset($_GET["page"])){
            $p = htmlentities($_GET["page"]);
            if(!is_numeric($p) || $p < 1){
                $p = 1;
            }
        }
        
        $page_to_call = ($p - 1)*$num_of_rows;

        //count entire users:
        $u_search_stmt = $pdo->prepare("SELECT * FROM orders ORDER BY order_id DESC LIMIT ?, ?");
        $u_search_stmt->execute([0, 1000]);

        $num_of_users = count($u_search_stmt->fetchAll(PDO::FETCH_OBJ));

        $max = ceil($num_of_users/$num_of_rows);
        // -- end of pagination algorithm --

        
        //first check if seller filtered the expected output
        if(isset($_GET["filter"])){
            $u_filter_stmt = $pdo->prepare("SELECT * FROM orders WHERE `status` = ?");
            $u_filter_stmt->execute([htmlentities($_GET["filter"])]);

            $u_data = $u_filter_stmt->fetchAll(PDO::FETCH_OBJ);
        
        //then check if seller searched for a product in particular
        }  else if(isset($_GET["product"])) {
            $search_q = htmlentities($_GET["product"]);

            $u_search_stmt = $pdo->prepare("SELECT * FROM orders WHERE product_name LIKE ? ORDER BY order_id DESC LIMIT ?, ?");
            $u_search_stmt->execute(["%$search_q%",$page_to_call, $num_of_rows]);

            $u_data = $u_search_stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            //if no filter is applied / no particular peroduct is searched for, list out all orders:
            $u_stmt = $pdo->prepare("SELECT * FROM orders ORDER BY order_id DESC LIMIT ?, ?");
            $u_stmt->execute([$page_to_call, $num_of_rows]);
    
            $u_data = $u_stmt->fetchAll(PDO::FETCH_OBJ);
        }

        function status_color($state){
            if($state == "processing") {
                return "#f3d111"; //yellow(a darker variant)
            } else if($state == "delivered") {
                return "green";
            } else if($state == "cancelled") {
                return "red";
            }
        }

        if(count($u_data)>0){     
            $i = 0;
            foreach($u_data as $d){
                $i += 1;
?>
        <div class="everything-both-buttons-nd-hidden-divs"> 
            <div class="table_row_div">
                <div class="table_row" style="width:8%"><?=$i + (($p - 1)*$num_of_rows)?>. </div>
                
                <div class="table_row"><b><a href="/product/<?=$d->product_url?>">Bilo000<?=$d->order_id?></a></b></div>
                <div class="table_row" style="color:<?=status_color($d->status)?>"><?=$d->status?></div>
                <div class="table_row" style="font-size:fit-content">
                    <button onclick = "create_content('edit',<?=$i?>)" style="background-color:green"
                class="table_row_ED">
                        View &nbsp; <i class="fa fa-eye"></i> 
                    </buton>
                </div>
            </div>


            <div class="clear">
            <div style="margin-top:12px;">

            <!-- To make all hidden div content to appear in the same spot on display: -->
            <div id="content_space<?=$i?>" class="calculator" style="display:none"> 
                <!-- style="display:block creates undesirable problems like making the div not to appear even onclick" -->
            </div>

            <!--hidden section 1: Order Details -->
            <div id="edit<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;padding:9px;">

                <form method="post" action="">
                    <!-- -->
                    <div>
                        <p><b>Order ID:</b> Bilo000<?=$d->order_id?></p>
                        <p><b>Order Title:</b> <?=$d->product_name?></p>
                        <p><b>Price:</b> N<?=$d->price?></p>
                        <p style="margin-top:-14px"><b>x <?=$d->qty?></b> = N<?=$d->price*$d->qty?></p>
                    </div>

                    <div class="additional_product_images_div_container">
                        <div class="additional_product_images_div">
                            <img src = "/static/images/<?=$d->image1?>" class="additional_product_image"/>
                        </div>
                        <div class="additional_product_images_div">
                            <img src = "/static/images/<?=$d->image2?>" class="additional_product_image"/>
                        </div>
                        <div class="additional_product_images_div">
                            <img src = "/static/images/<?=$d->image3?>" class="additional_product_image"/>
                        </div>
                    </div>

                    <div>
                        <p style="font-size:21px"><b>Contact Details</b> </p>
                        <p><b>Name:</b> <?=$d->customer_realname?></p>
                        <p><b>Phone Number:</b> +234<?=$d->phone_number?></p>
                        <p><b>Email:</b> <?=$d->customer_email?></p>
                        <p><b>Detailed Location:</b></p>
                        <p>Country: <?=ucfirst($d->country)?>, State: <?=ucfirst($d->state)?></p>
                        <p>LGA: <?=ucfirst($d->LGA)?></p>
                        <p>Address: <?=ucfirst($d->address)?></p>
                        <p>Postal Code: <?=$d->postal_code?></p>
                    </div>

                    <div>
                        <span style="margin-right:9px;font-size:18px">Status:</span> 
                        <select name="order_status" style="width:165px;height:39px;">
                            <option value="<?=$d->status?>"><?=$d->status?></option>
                            <option value="processing">processing</option>
                            <option value="delivered">delivered</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                    </div>

                    <div style="margin:12px 0">
                        <input type="submit" value="Update Status" class="edit_product_action_button" style="background-color:green"/>
                        
                        <span class="edit_product_action_button" style="background-color:#ff9100" onclick="hide_content_space('edit',<?=$i?>)">Cancel</span>
                    </div>
                    <input type="hidden" name="order_id" value="<?=$d->order_id?>"/>
                </form>
            </div>
            <!--End of Order Details div-->



            <!-- hidden section 2: Remove Product -->
            <!--
            <div id="remove<=$i?>" style="display:none;border:2px solid red;border-radius:6px;margin-top:12px;padding:3px">

            <form method="post" action="" id="message_form<=$i?>" class="pop_up">
            <span style="text-align:center">Are you sure you want to remove user: <b style="font-size:18px;color:red;border-bottom:2px solid #fff"><=$d->product_name?>?</b> &nbsp;

            <b>This can't be Undone</b></span><br /><br />

            <input type="hidden"  name="remove_product" value="<=$d->order_id?>"/>

            <input type="submit" value="Remove" style="background-color:red;padding:3px;margin:3px;border-radius:6px;color:#fff;border:none;height:24px;"/> 
            <span onclick="hide_content_space('remove',<=$i?>)" style="background-color:#ff9100;
                    padding:3px;border-radius:6px;color:#fff;
                    margin-left:6px;text-align:center;height:24px;border:none">
                    Cancel 
            </span>    
            </form>
    
            </div> -->
            <!--End of remove<=i> div-->

            </div><!--End of hidden divs-->
            </div><!--End of hidden divs clear class-->   
        </div> <hr /><br /><!--End of Both Buttons and hidden divs-->
        
        <!--End of all - both buttons hidden divs-->

<?php
            }
        }
?>

        <!--Paginator-->
        <div class="clear" style="font-weight:bold;font-size:18px; margin-bottom:12px">
            <?php if($p > 1) { ?> 
                <div style="float:left">
                    <b>
                       <a href="?page=<?=$p-1?>" style="color:#000;font-weight:bold"><i class="fa fa-angle-left"></i> &nbsp; Previous</a>
                    </b>
                </div> 
            <?php } ?>

            <?php if($p < $max) { ?> 
                <div style="float:right">
                    <b>
                        <a href="?page=<?=$p+1?>" style="color:#000">Next &nbsp;<i class="fa fa-angle-right"></i></a>
                    </b>
                </div> 
            <?php } ?>
        </div> <!-- End of Paginator -->

        </div> <!-- End of class 'main_div' -->
        </div><!-- .site_content ends -->
        </div><!-- .site_content_and_menu ends -->
        </div><!-- .site_content_and_menu_sellers ends -->

<?php
    }else{
        //redirect
        header("location:/seller");
    }

    echo "</div>"; //end of 'main' div
    seller_Segments::footer();
} else {
    //redirect
    header("location:/seller");
}

?>