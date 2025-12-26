<?php
ini_set("display_errors", '1'); //for testing purposes..

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$categories_stmt = $pdo->prepare("SELECT * FROM categories LIMIT ?, ?");
$categories_stmt->execute([0, 50]);
$categories_data = $categories_stmt->fetchAll(PDO::FETCH_OBJ);

if(count($categories_data) == 0){
    header("location: /404.php");
}

Index_Segments::header();
?>
    <div class="main_body">
        <h1>CATEGORIES:</h1>
        <div style="margin:12px 6px"><!-- .categories starts -->
    <?php
        foreach($categories_data as $cat_d) {
    ?>
            <div style="display:flex;margin:12px">
                <div class="cart_image_div"><a href="/category/<?=$cat_d->category_title?>"><img src="/static/images/<?=$cat_d->category_image1?>" class="cart_image"/></a></div>

                <div style="margin-top:12px"><h2><a href="/category/<?=$cat_d->category_title?>" style="color:#000;font-size:18px"><?=ucfirst($cat_d->category_title)?></a></h2></div>
            </div>
    <?php
        }
    ?>
       </div><!-- .categories ends -->
    </div>
<?php
Index_Segments::footer($site_name = SITE_NAME_SHORT, $site_url = SITE_URL, $additional_scripts = Index_Segments::index_scripts(),$whatsapp_chat = "on");
        
    echo <<<HTML
            <!--this div is only meant to bring up the footer section of product page so that it's not covered by the fixed 'add_to_my_picks' div-->
            <div style="margin-top:45px"></div>
HTML;
?>