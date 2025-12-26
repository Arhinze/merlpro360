<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");

if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){
    $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
        //that means admin is logged in
        admin_Segments::header();
?>
        <div class="dashboard_div" style="margin:-30px 3% 10% 3%;">

        <h1 style="margin:12px 6px">Customers - <?=$site_name?></h2>

        <!-- Add new User div starts -->
        <!--
        <div>
            <div onclick="show_div('new_product1')" style="background-color:green;color:#fff;font-weight:bold;padding:9px 12px;border-radius:6px;margin:12px 0 18px 0;width:fit-content"><span>Add New Product</span> <i class="fa fa-angle-down" style="margin-left:12px;font-size:21px"></i></div>

            <div id="new_product1" style="display:none;padding:9px;background-color:#f3f3f3;border-radius:6px;border:1px dotted #000">
                <form method="post" action="">
                    <div style="position:relative"><input type="text" class="edit_product_input" name="new_product_name" placeholder="Enter Product Name"/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Name </span></div> 

                    <div style="position:relative"><input type="text" class="edit_product_input"  name="new_url" placeholder="Enter Product URL"/>
                    <span style="position:absolute;left:6px;top:6px;color:#fff">Url </span></div> 
                    <span>Only letters, numbers and hyphen (-) allowed.</span>
                    <div style="font-size:18px;font-weight:bold;margin:15px 0 9px 0">Add Images: </div>
                    <div class="additional_product_images_div_container">
                        <div class="additional_product_images_div">
                            <img src = "/static/images/" class="additional_product_image"/>
                        </div>
                        <div class="additional_product_images_div">
                            <img class="additional_product_image"/>
                        </div>
                        <div class="additional_product_images_div">
                            <img class="additional_product_image"/>
                        </div>
                    </div>

                    <div style="font-size:18px;margin:15px 0 9px 0"><b>Product Description:</b></div>
                    <textarea style="width:75%;height:100px;border-radius:4px" name="new_product_description" placeholder="sell this product in a maximum of 50 words."></textarea>

                    <div style="margin:12px 0">
                        <input type="submit" class="edit_product_action_button" style="background-color:green"/>
                        
                        <span class="edit_product_action_button" style="background-color:#ff9100" onclick="show_div('new_product1')">Cancel</span>
                    </div>
                    <input type="hidden" name="new_product" value="new_product"/>
                </form>
            </div>
        </div>
        -->
        <!-- Add new User div ends -->
<?php
        //check if admin is searching for someone:
?>
        <div style="margin-top:18px;padding:12px 9px;border:1px solid #000;border-radius:12px;background-color:#f3f3f3">
            <input type="text" onkeyup="ajax_search()" id="search_input" class="input" placeholder="Enter username: try: abc" style="border:1px solid #000;width:75%"/>
        
            <i class="fa fa-search" onclick ="search_icon()" style="padding:9px;border-radius:4px;font-size:16px;color:#fff;background-color:#000"></i>

            <div id="search" style="position:absolute;width:75%"></div>
        </div>
        
        <div style="margin-top:12px">    <!-- 'main' div starts -->
            <div class="table_row_div" style="margin-bottom:18px">
                <div class="table_row" style="width:8%">#</div>
                <div class="table_row">Email</div>
                <div class="table_row">View / Delete</div>
            </div>
<?php
        //To Delete User:
        if(isset($_POST["remove_user"])){
            //check if User still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
            $ds_stmt->execute([$_POST["remove_user"]]);
    
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM customers WHERE customer_id = ?");
                $dd_stmt->execute([$_POST["remove_user"]]);

                echo "<h4 style='color:red'>User: ", $ds_data->customer_username, " has been deleted successfully</h4>";
            } else {
                echo "<h4 style='color:red'>Error: User does not exist.</h4>";
            }
        }

        //Mail Customer:


        //Select and view all users for easy decision making:

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
        $u_search_stmt = $pdo->prepare("SELECT * FROM customers ORDER BY customer_id DESC LIMIT ?, ?");
        $u_search_stmt->execute([0, 1000]);

        $num_of_users = count($u_search_stmt->fetchAll(PDO::FETCH_OBJ));

        $max = ceil($num_of_users/$num_of_rows);
        // -- end of pagination algorithm --

        
        //first check if admin searched for a product in particular
        if(isset($_GET["product"])){
            $search_q = htmlentities($_GET["product"]);

            $u_search_stmt = $pdo->prepare("SELECT * FROM customers WHERE product_name LIKE ? ORDER BY customer_id DESC LIMIT ?, ?");
            $u_search_stmt->execute(["%$search_q%",$page_to_call, $num_of_rows]);

            $u_data = $u_search_stmt->fetchAll(PDO::FETCH_OBJ);
        }  else {
            //if no particular person is searched for, call out everyone:
            $u_stmt = $pdo->prepare("SELECT * FROM customers ORDER BY customer_id DESC LIMIT ?, ?");
            $u_stmt->execute([$page_to_call, $num_of_rows]);
    
            $u_data = $u_stmt->fetchAll(PDO::FETCH_OBJ);
        }

        if(count($u_data)>0){     
            $i = 0;
            foreach($u_data as $d){
                $i += 1;
?>
        <div class="everything-both-buttons-nd-hidden-divs"> 
                <div class="table_row_div">
                    <div class="table_row" style="width:8%"><?=$i + (($p - 1)*$num_of_rows)?>. </div>
                    
                    <div class="table_row"><b><?=substr($d->customer_email, 0, 12)."..."?> </b></div>
                    
                    <div class="table_row">
                        <button onclick = "create_content('view',<?=$i?>)" style="background-color:green"
                    class="table_row_ED">
                            View &nbsp; <i class="fa fa-eye"></i> 
                        </buton>

                        <button onclick = "create_content('remove',<?=$i?>)" style="background-color:red" class="table_row_ED">
                            <i class="fa fa-warning"> </i>&nbsp; Remove </buton>
                    </div>
                </div>


            <div class="clear">
            <div style="margin-top:12px;">

            <!-- To make all hidden div content to appear in the same spot on display: -->
            <div id="content_space<?=$i?>" class="calculator" style="display:none"> 
                <!-- style="display:block creates undesirable problems like making the div not to appear even onclick" -->
            </div>

            <!--hidden section 1: View -->
            <div id="view<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;padding:9px;">
                    <!-- -->
                    <div style="line-height:27px">
                        <div><b>Name: </b> <?=$d->customer_realname?></div>
                        <!--<div><b>Username: </b> <$d->customer_username?></div>-->
                        <div><b>Email: </b> <?=$d->customer_email?></div>
                        <div><b>Password: </b> <?=$d->password?></div>
                        <div><b>Joined Bilo on: </b> <?=$d->date_joined?></div>
                    </div>

                    <div style="margin:12px 0">
                        <span class="edit_product_action_button" style="background-color:#ff9100" onclick="hide_content_space('view',<?=$i?>)">Close &nbsp; <i class="fa fa-ban"></i></span>
                    </div>
            </div>
            <!--End of Edit div-->



            <!-- hidden section 2: Remove Product -->
            
            <div id="remove<?=$i?>" style="display:none;border:2px solid red;border-radius:6px;margin-top:12px;padding:3px">

            <form method="post" action="" id="message_form<?=$i?>" class="pop_up">
            <span style="text-align:center">Are you sure you want to remove user: <b style="font-size:18px;color:red;border-bottom:2px solid #fff"><?=$d->customer_username?>?</b> &nbsp;

            <b>This can't be Undone</b></span><br /><br />

            <input type="hidden"  name="remove_user" value="<?=$d->customer_id?>"/>

            <input type="submit" value="Remove" style="background-color:red;
                    padding:3px;margin:3px;border-radius:6px;color:#fff;border:none;height:24px;"/> 

            <!--Cancel "Remove Product" (Don't remove):-->
            <!--onclick = "show_div('remove <= $i >')"-->
            <span onclick="hide_content_space('remove',<?=$i?>)" style="background-color:#ff9100;
                    padding:3px;border-radius:6px;color:#fff;
                    margin-left:6px;text-align:center;height:24px;border:none">
                    Cancel 
            </span>    
            </form>

            </div> <!--End of remove<=i> div-->

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

<?php
    }else{
        //redirect
        header("location:/admin");
    }

    echo "</div>"; //end of 'main' div
    admin_Segments::footer();
} else {
    //redirect
    header("location:/admin");
}

?>