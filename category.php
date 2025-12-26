<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");

$category = "no-category";
if(isset($_GET["title"])) {
    $category = str_replace(" ", "", htmlentities($_GET["title"]));
} 

$category_stmt = $pdo->prepare("SELECT * FROM categories WHERE category_title =  ? LIMIT ?, ?");
$category_stmt->execute([$category, 0, 1]);
$category_data = $category_stmt->fetch(PDO::FETCH_OBJ);

Index_Segments::header(); 
?>

<div class="main_body" style="margin-left:0;margin-right:0"><!-- .main_body starts -->
    <div class="site_content_and_menu"><!-- .site_content_and_menu starts -->

<?php
if ($category_data) {
    echo Index_Segments::site_menu();
?>

<div class="site_content"><!-- .site_content starts -->
<div class="product_image_div"><!-- .product_image_div starts -->
        <img class="product_image" src="/static/images/<?=$category_data->category_image1?>"/>
        <div class="upi_top_left">
            <a href="/" style="color:#000"><i class="fa fa-angle-left" style="font-size:18px;padding:6px 12px"></i></a>
            <span class="upi_bl_contents" style="font-size:14px;margin-left:6px;padding:5px"><!-- styled as .upi_bl_contents but placed in .upi_top_left location -->
                <i class="fa fa-star" style="font-size:12px"></i> Category: <b><?=ucfirst($category)?></b> &nbsp;
            </span>
        </div>
        <div class="upi_top_right">
            <i class="fa fa-search" style="margin-right:3px" onclick="show_div('header_search')"></i>
            <i class="fa fa-share-alt"></i>
        </div>

        <div class="upi_bottom_left">
            <div class="upi_bl_contents">
                <i class="fa fa-crosshairs"></i> 5000+ products &nbsp;
            </div>
            <div class="upi_bl_contents">
                <i class="fa fa-empire"></i> over 1000+ sold
            </div>
        </div>
        <div class="upi_bottom_right">
            <i class="fa fa-heart"></i>
        </div>
    </div><!-- .product_image_div ends -->  

<?php
    echo <<<HTML
        <!-- All selling on Biloonline starts -->
        <!-- 1 to 6 -->
        <div class="topselling_div" style="flex-wrap:wrap"><!-- .flex_div starts(.topselling) --> 
HTML;
            $all_category_stmt = $pdo->prepare("SELECT * FROM products WHERE category LIKE ? ORDER BY product_id DESC LIMIT ?, ?");
            $all_category_stmt->execute(["%$category%",0,12]);
            $all_category_data = $all_category_stmt->fetchAll(PDO::FETCH_OBJ);

            if (count($all_category_data)>0) { 
                $i=0;
                foreach ($all_category_data as $all_cat) {
                    $i++;
                    $short_description = substr($all_cat->description,0,36);
                    $all_cat_price = number_format($all_cat->price);
                    $all_cat_former_price = number_format($all_cat->former_price);
                    echo <<<HTML
                        <div class="deal_div"><!-- .deal_div starts --> 
                            <a href="/product/$all_cat->product_url" style="color:inherit"><!-- link to product page starts -->
                            <img src="/static/images/$all_cat->image1" class="deal_img"/>   
                            <div class="below_deal_img"><!-- .below_deal_img starts -->
                                <div class="topselling_choice_and_title">
                                    <span class="topselling_choice"> Choice </span> &nbsp;
                                    <span>
                                        $short_description...
                                    </span>
                                </div>
                                <span class="deal_price_black">
                                    NG N$all_cat_price
                                </span>  &nbsp; 
                                <span class="deal_former_price">
                                    <s>NG N$all_cat_former_price</s>
                                </span> 
                                <div class="star_and_rating">
                                    <i class="fa fa-star"></i> <b>4.6</b> <span style="color:#888"> | </span> 1,000+ sold
                                </div>
            
                                <div class="topselling_text">
                                    <i class="fa fa-fire"></i> Top selling on BiloOnline
                                </div>
                                <i class="fa fa-motorcycle"></i> Free shipping
                            </div><!-- .below_deal_img ends -->
                            </a><!-- link to product page ends -->
                        </div><!-- .deal_div ends -->
HTML;
                }
            }

    echo <<<HTML
        </div><!-- .flex_div(.topselling_div) ends -->
        <!-- All selling on Biloonline ends -->
HTML;



} else { //if category does not exist:
    echo "<div style='font-weight:bold;text-align:center;margin:24px 6px'>Sorry, category not found, kindly select a category on the menu items to browse from.</div>";
}
?>
</div><!-- .site_content ends -->
</div><!-- .site_content_and_menu ends -->
</div><!-- .main_body ends -->

<?php

Index_Segments::footer(); 
?>