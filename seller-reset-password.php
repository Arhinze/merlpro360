<?php
//seller_Segments would include connections
include_once($_SERVER["DOCUMENT_ROOT"]."/views/seller_Segments.php");
if(isset($_COOKIE["seller_username"]) && isset($_COOKIE["seller_password"])){

    $stmt = $pdo->prepare("SELECT * FROM `sellers` WHERE seller_username = ? AND seller_password = ?");
    $stmt->execute([$_COOKIE["seller_username"], $_COOKIE["seller_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
    //that means seller is logged in:
        seller_Segments::header();
        echo "<div class='dashboard_div' style='padding:6px'><h1 style='font-size:24px'>seller Reset Username/Password</h1><hr />";

        if(isset($_POST["seller_username"])) {
            if($_POST["password1"] == $_POST["password2"]){
                $us = $pdo->prepare("UPDATE `seller` SET seller_password=?, seller_username = ? WHERE seller_id = ?");
                $us->execute([$_POST["password1"],$_POST["seller_username"],$_POST["id"]]);

                echo "Passwords and Username Updated Successfuly. <b><a href='/seller'>Click here</a></b> to login again";
            } else {
                echo "<h2>Passwords don't match. Please try again</h2>";
            }
        }
?>
    <form method="post" action="">
        <h5>Username </h5>
        <input type="text" name="seller_username" class="input" value="<?=$data->seller_username?>"/>
        <h5>Password</h5>
        <input type="text" name = "password2" class="input" value="<?=$data->seller_password?>"/>
        <h5>Repeat Passsword</h5>
        <input type="text" name = "password1" class="input" value="<?=$data->seller_password?>"/>
        <input name="id" type="hidden" value="<?=$data->seller_id?>"/>
        <br />
        <div style="margin:12px 3px">
            <input type="submit" value="Submit" class="long-action-button"/>
        </div>
    </form>  

    </div> <!-- End of dashboard_div -->

<?php
    }
}
?>