<?php

include_once("views/Index_Segments.php");
if(isset($_COOKIE["seller_username"]) && isset($_COOKIE["seller_password"])){

    $stmt = $pdo->prepare("SELECT * FROM sellers WHERE seller_username = ? AND seller_password = ?");
    $stmt->execute([$_COOKIE["seller_username"], $_COOKIE["seller_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
        header("location:/seller-products");
    }
}

$check_seller = "";
$remember_seller = "";

if(isset($_POST["seller_username"])){
    $remember_seller = $_POST["seller_username"];   
}

if(isset($_POST["user_code"])){
    $sellercode = $_POST["user_code"];
    if($sellercode == $_POST["xsrf_code"]){
        $stmt = $pdo->prepare("SELECT * FROM `sellers` WHERE (seller_username = ? OR seller_email = ?) AND seller_password = ?");
        $stmt->execute([$_POST["seller_username"], $_COOKIE["seller_username"], $_POST["seller_password"]]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($data)>0){
            setcookie("seller_username", $_POST["seller_username"], time()+(24*3600), "/");
            setcookie("seller_password", $_POST["seller_password"], time()+(24*3600), "/");

            header("location:/seller-products");

        } else{
            setcookie("seller_username", $_POST["seller_username"], time()-(24*3600), "/");
            setcookie("seller_password", $_POST["seller_password"], time()-(24*3600), "/"); 

            $check_seller = "<div class='invalid'>Wrong Username/password Combination</div>";
        }

    } else if(empty($sellercode)){
        echo '<div class="invalid"><i class="fa fa-warning"></i> Please Enter the 6 Digit Code</div>';
    } else {
        echo '<div class="invalid"><i class="fa fa-warning"></i> Wrong Captcha</div>';
    }
} else {
   // 
}

Index_Segments::header();
?>

<div class="dashboard_div" style="margin-top:90px">
    <div class="sign-in-welcome">
        <h2><i class="fa fa-bank"></i> &nbsp; <?=$site_name?> Sellers Platform</h2>
    </div>

    <?=$check_seller?>

    <form method="post" action=""> 
        <br />Username:<br />
        <input type="text" placeholder="Username" class="input" name="seller_username" value="<?=$remember_seller?>" required/>    
           
        <br />Password:<br />
        <input type = "password" placeholder = "Password: *****" name = "seller_password" class="input" required/>

       <?php include($_SERVER["DOCUMENT_ROOT"]."/views/captcha.php"); ?>
    
        <button type="submit" class="button" style='background-color:#0bee3ccc;border-radius:3px'>Login &nbsp;<i class="fa fa-telegram"></i> </button> <br />
    </form>
</div>

<?php
Index_Segments::footer();
?>