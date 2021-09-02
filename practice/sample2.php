
<?php

function itinerary_view() {
  if(!isset($_SESSION['traveldoor_countries']))
  {
           //API URL
    $url = $GLOBALS["api_base_url"].'/api/getCountries';
//create a new cURL resource
    $ch = curl_init($url);

//set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
    $result = curl_exec($ch);

//close cURL resource
    curl_close($ch);
    $countries=json_decode($result,true);
    $_SESSION['traveldoor_countries']=$countries;
}
else
{
  $countries=$_SESSION['traveldoor_countries'];
}

?>
<style>


/*.icon{*/
/*    position: absolute !important;*/
/*    left: 590px !important;*/
/*    top: 78px !important;*/
/*    z-index: 9999 !important;*/
/*}*/
    #table-itinerary-loader svg{
        width: 100px;
        height: 100px;
        display:inline-block;
    }
    #table-transfer-loader svg{
        width: 100px;
        height: 100px;
        display:inline-block;
    }
    .gdlr-core-pbf-section:first-child {
        padding-top: 10px !important; 
    }
    .tourmaster-tour-search-title
    {
       display: none;
   }
   .tourmaster-tour-info.tourmaster-tour-info-duration-text p {
    width: 70%;
}
.tourmaster-tour-grid.tourmaster-tour-frame .tourmaster-tour-price-wrap {
    top: 0 !IMPORTANT;
    right: 25px;
}
.tourmaster-tour-grid-style-2 .tourmaster-tour-info-wrap .tourmaster-tour-info i {
    font-size: 18px;
    width: 18px;
    margin-right: 9px;
}
i.fa.fa-star-o {
    margin-right: 0 !important;
}
span.inclusion-item {
    margin-right: 15px;
    background: #61d092;
    color: aliceblue;
    padding: 5px 10px;
    display: inline-block;
    border-radius: 5px;
}
.tourmaster-tour-info-wrap .tourmaster-tour-info i {
    color: #128444;
}
.tourmaster-lightbox-content {
    max-height: 600px;
    overflow: auto;
}
.tourmaster-tour-grid-inner.itinerary-row {
    padding: 10px;
}
</style>

<div class="traveltour-page-wrapper" id="traveltour-page-wrapper">
    <div class="gdlr-core-page-builder-body">
        <div class="gdlr-core-pbf-section">
            <div class="gdlr-core-pbf-section-container gdlr-core-container clearfix">
              <div class="gdlr-core-pbf-element">
                <div class="tourmaster-tour-search-item clearfix tourmaster-style-column tourmaster-column-count-4 tourmaster-item-pdlr tourmaster-input-style-transparent-bottom-border" style="padding-bottom: 10px;"><div class="tourmaster-tour-search-wrap ">
                	<h3 class="tourmaster-tour-search-title" >Search For Tour</h3>
                    <form class="tourmaster-form-field tourmaster-transparent-bottom-border tourmaster-small" action="<?php echo get_site_url(); ?>/tour-search/" method="GET" id="hotel_search_form">
                        <input name="tour-search" type="hidden" value=""><div class="tourmaster-tour-search-field tourmaster-tour-search-field-tax">
                            <div class="tourmaster-combobox-wrap">
                             <label>Arrival Country</label>
                             <input type="hidden" id="service_type" value="package">
                             <select name="tax-tour-country" id="activity_country" >
                              <option selected="selected">SELECT COUNTRY</option>
                              <?php
                              foreach($countries as $country)
                              {
                               if($country['country_name']=="Georgia")
                               {
                                echo '<option value="'.$country['country_id'].'" selected>'.$country['country_name'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                            }
                        }

                        ?>
                    </select>
                </div>
            </div>
            <div class="tourmaster-tour-search-field tourmaster-tour-search-field-tax">
                <div class="tourmaster-combobox-wrap">
                    <label>Arrival City</label>
                    <select name="tax-tour-city itinerary_city"  id="activity_city">
                       <option selected="selected">SELECT ARRIVAL CITY</option>
                   </select>
               </div>
           </div>
           <div class="tourmaster-tour-search-field tourmaster-tour-search-field-date">
            <div class="tourmaster-datepicker-wrap">
              <label>Starting Date</label>
              <input type="text" class="tourmaster-datepicker " readonly name="date_from" id="date_from" placeholder="DEPARTURE DATE"  required="required" />
              <input type="hidden" name="tour-date" class="tourmaster-datepicker-alt" />
          </div>
      </div>
      <div class="tourmaster-tour-search-field tourmaster-tour-search-field-tax">
        <div class="tourmaster-combobox-wrap">
            <label>Duration</label>
            <select name="tax-tour-city tour_days"  id="tour_days">
               <option selected="selected" value="">ANY DURATION</option>
               <?php
               for($i=2;$i<=29;$i++)
               {
                echo '<option value="'.$i.'">'.$i.' Nights / '.($i+1).' Days</option>';
            }
            ?>

        </select>
    </div>
</div>
<input type="hidden" name="itinerary_offset" value="0">

<input type="button" id="search_itinerary" class="tourmaster-tour-search-submit" value="Search">
</form>
</div>
</div>
</div>




<div class="gdlr-core-pbf-element">
  <div class="tourmaster-tour-item clearfix  tourmaster-tour-item-style-grid tourmaster-tour-item-column-3" style="padding-bottom: 10px;">
    <div id="itinerary_div" class="tourmaster-tour-item-holder gdlr-core-js-2 clearfix" data-layout="fitrows">

    </div>
    <div id="table-itinerary-loader" class="text-center" style="display: none">
      <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
      viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
      <circle fill="#ffa019" stroke="none" cx="6" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 15 ; 0 -15; 0 15" 
        repeatCount="indefinite" 
        begin="0.1"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="30" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 10 ; 0 -10; 0 10" 
        repeatCount="indefinite" 
        begin="0.2"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="54" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 5 ; 0 -5; 0 5" 
        repeatCount="indefinite" 
        begin="0.3"/>
    </circle>
</svg>
</div>
<div class="offset_div">
    <button class="tourmaster-tour-search-submit show_more_results" id="show_more_itinerary">SHOW MORE RESULTS</button>
    <button class="tourmaster-tour-search-submit no_more_results">NO MORE RESULTS</button>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
<span class="tourmaster-user-top-bar-loader" data-tmlb="selectLoaderModal" style="display:none"><i class="icon_lock_alt"></i></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectLoaderModal" style="background: none;
text-align: center;">

<div class="tourmaster-lightbox-content">
    <i class="tourmaster-lightbox-close icon_close modalCloseIcon loaderClose" style="display:none"></i>
    <h4 style="color:white">Searching Best Results For You....</h4>
    <div class="country-loader" style="width: 100px;
    height: 100px;
    display:inline-block;">

    <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
    viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
    <circle fill="#ffa019" stroke="none" cx="6" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 15 ; 0 -15; 0 15" 
        repeatCount="indefinite" 
        begin="0.1"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="30" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 10 ; 0 -10; 0 10" 
        repeatCount="indefinite" 
        begin="0.2"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="54" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 5 ; 0 -5; 0 5" 
        repeatCount="indefinite" 
        begin="0.3"/>
    </circle>
</svg>
</div>
</div>
</div>
<script>
    jQuery(document).ready(function()
    {
       jQuery('#activity_country').trigger('change')
       var dateToday = new Date();

       jQuery(".tourmaster-datepicker").datepicker({
           dateFormat: 'yy-mm-dd',
           minDate: 0,
       });
   });

</script>
<?php
}

add_shortcode('show_all_itineraries', 'itinerary_view');
function itinerary_details()
{
    $itinerary_id=$_GET['id'];

    if(isset($_GET['date_from']) && $_GET['date_from']!="")
    {
        $itinerary_date_from=$_GET['date_from'];

        $_SESSION['itinerary_date_from']=$itinerary_date_from;
    }
    else
    {
       $itinerary_date_from=$_SESSION['itinerary_date_from'];
   }

    //API URL
   $url = $GLOBALS["api_base_url"].'/api/itinerary/itineraryDetailView';

//create a new cURL resource
   $ch = curl_init($url);

   $data=array("itinerary_id"=>$itinerary_id,
    "itinerary_date_from"=>$itinerary_date_from);
   $itinerary= json_encode($data);

//attach encoded JSON string to the POST fields
   curl_setopt($ch, CURLOPT_POSTFIELDS, $itinerary);
//set the content type to application/json
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
   $result = curl_exec($ch);

//close cURL resource
   curl_close($ch);
   $result_data=json_decode($result,true);
   $_SESSION['itinerary_details']=$result_data;



/** Fetching hotel type
*/
$url = $GLOBALS["api_base_url"].'/api/getHotelType';

//create a new cURL resource
$ch = curl_init($url);

//set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
$result = curl_exec($ch);

//close cURL resource
curl_close($ch);
$hotel_type=json_decode($result,true);



/** Fetching restaurant type
*/
$url = $GLOBALS["api_base_url"].'/api/restaurant/getRestaurantType';

//create a new cURL resource
$ch = curl_init($url);

//set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
$result = curl_exec($ch);

//close cURL resource
curl_close($ch);
$restaurant_type=json_decode($result,true);
?>
<style>
    .t-tab {
        border: 1px solid #f5f5f5;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 30px;
        margin-left: 0;
        margin-top: 20px;
        box-shadow: 0px 0px 25px rgba(10, 10, 10, 0.08);
    }

    .des {
        margin-left: 10px;
    }

    p.days {
        color: black;
        font-size: 18px;
        font-weight: 700;
    }
    h6.gdlr-core-title-item-title.gdlr-core-skin-title {
        color: #234076;
    }
    p.title-1 {
        margin-bottom: 0;
        color: black;
        font-size: 16px;
    }

    p.title-2 {
        color: black;
        margin-bottom: 5px;
    }

    p.p-i {
        color: black;
    }

    span.span-i {
        color: gray;
        font-size: 12px;
    }

    a.remove {
        color: #234076;
        font-weight: 600;


    }

    a.chng {
        color: #234076;
        font-weight: 600;
    }

    .change {
        display: flex;
        justify-content: space-between;
    }

    .hr {
        border-right: 2px solid gainsboro;
    }

    .heading-div.c-h-div {
        display: flex;
        justify-content: space-between;
    }


    .hotel-div {
        width: 80%;
        float: left;

        display: flex;
        background: white;
    }

    .hotel-list-div {
        display: block;

        border-radius: 5px;
        position: relative;
        /* clear: both; */
        height: 101%;
    }

    .hotel-img-div {

        width: 45% !important;
        float: left;
        margin-left: 10px;
    }

    .hotel-details {
        float: left;
        width: 60%;
        padding: 0 15px;
    }

    .hotel-info-div {
        width: 20%;
        float: left;


        text-align: right;
        height: 100%;
    }

    .checked {
        color: orange;
    }

    span.hotel-s {
        border: 1px solid #F44336;
        padding: 2px 5px;
        display: inline-block;
        color: #f44336;
        font-size: 12px;
        margin: 0 10px 0 0;
    }

    p.hotel-name {
        font-size: 16px;
        margin: 10px 0 0;
        color: black;
        float: left;
    }


    .rate-no {
        float: right;
    }

    .rate-no {
        float: right;
        display: block;
        margin-top: 14px;
        background: #2d3134;
        color: white;
        padding: 1px 8px;
        font-size: 12px;
        border-radius: 5px;
    }

    .heading-div {
        clear: both;
    }

    .rating {
        display: block;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    p.r-number {
        float: right;
    }

    .row {
        display: flex;
    }

    .col-md-4 {
        flex: 0 0 30%;
        padding: 0 15px;
    }

    .col-md-5 {
        flex: 0 0 45%;
        padding: 0 15px;
    }

    .col-md-7
    {
        flex: 0 0 58.333333%;
        max-width: 58.333333%;
        padding: 0 15px;
    }

    .col-md-3 {
        flex: 0 0 25%;
        padding: 0 15px;
    }

    p.time-info {
        color: #4CAF50;
    }

    p.info {
        clear: both;
        margin: 0;
    }
    

    span.tag-item {
        background: #ffcbcd;
        padding: 5px 10px;
        border-radius: 5px;
        color: #ff4e54;
    }

    .inclusions {
        margin: 20px 0;
    }

    span.inclusion-item {
        padding: 10px 10px 0 0;
        color: #644ac9;
    }

    img.icon-i {
        width: auto;
        height: 20px;
    }

    p.include-p {
        color: black;
    }

    .inclusion-p {
        color: green
    }

    p.price-p span {
        background: #ee2128;
        color: white;
        padding: 3px 5px 3px 9px;
        border-radius: 5px;
        position: relative;
        z-index: 9999;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        margin-left: 20px;
        border-bottom-left-radius: 4px;
        font-size: 12px;
    }

    p.price-p span:before {
        content: "";
        width: 15px;
        height: 14.5px;
        background: #ee2128;
        position: absolute;
        transform: rotate(45deg);
        top: 3.5px;
        left: -6px;
        border-radius: 0px 0px 0px 3px;
        z-index: -1;
    }

    p.price-p span:after {
        content: "";
        background: white;
        width: 4px;
        height: 4px;
        position: absolute;
        top: 50%;
        left: 0px;
        transform: translateY(-50%);
        border-radius: 50%;
    }

    p.price-p {
        color: #ee2128;
    }

    p.tax {
        font-size: 12px;
        margin: 0;
    }

    p.days {
        font-size: 12px;
        margin: 0;
    }

    p.offer {
        font-size: 25px;
        color: black;
        font-weight: bold;
        margin: 0;
    }

    p.cut-price {
        margin: 0;
        text-decoration: line-through;
        font-size: 19px;
    }

    .login-a add-modal-btn {
        color: #0088ff;
        font-size: 14px;
        font-weight: 600;
        margin-top: 10px;
        display: block;
    }

    @media screen and (max-width:1200px) {
        p.hotel-name {
            font-size: 15px;
            margin: 10px 0 0;
            color: black;
            float: left;
        }

        p.r-number {
            float: right;
            font-size: 12px;
        }

        span.inclusion-item {
            padding: 10px 10px 0 0;
            color: #644ac9;
            display: block;
        }

        p.cut-price {
            margin: 0;
            text-decoration: line-through;
            font-size: 16px;
        }

        p.offer {
            font-size: 20px;
            color: black;
            font-weight: bold;
            margin: 0;
        }

        .login-a add-modal-btn {
            color: #0088ff;
            font-size: 13px;
            font-weight: 600;
            margin-top: 10px;
            display: block;
        }
    }

    @media screen and (max-width:1200px) {
        .hotel-div {
            width: 100%;
            float: left;

            display: flex;
            background: white;
        }

        .hotel-info-div {
            width: 100%;
            height: 100%;
            float: left;



            text-align: left;
        }

        span.inclusion-item {
            padding: 10px 10px 0 0;
            color: #644ac9;
            display: inline;
        }
    }

    @media screen and (max-width:992px) {
        p.hotel-name {
            font-size: 17px;
            margin: 10px 0 0;
            color: black;
            float: none;
        }

        .rate-no {
            float: none;
            display: inline;
            margin-top: 14px;
            background: #2d3134;
            color: white;
            padding: 1px 8px;
            font-size: 12px;
            border-radius: 5px;
        }

        .rating {
            display: block;
            float: none;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        p.r-number {
            float: none;
            font-size: 12px;
        }

        .hotel-details {
            float: none !important;
            width: 100% !important;
            padding: 0 15px;
            margin-top: 20px;
        }

        .hotel-list-div {
            display: block;

            border-radius: 5px;
            position: relative;
            /* clear: both; */
            height: 101%;
        }

        .hotel-div {
            width: 100%;
            float: none;

            display: block;
            background: white;
        }

        .hotel-img-div {
            width: 100% !important;
            float: none !important;
            display: block !important;
        }

        a.flex-prev,
        a.flex-next {
            display: none;
        }

        span.inclusion-item {
            padding: 10px 10px 0 0;
            color: #644ac9;
            display: block;
        }
    }

    .traveltour-page-title-wrap.traveltour-style-custom.traveltour-center-align {
        display: none;
    }

    .tourmaster-tour-style-2 .tourmaster-tour-booking-bar-wrap .tourmaster-header-price {
        padding-bottom: 38px;
    }

    .sightseeing-img {
        height: 150px !important;
    }

    .tourmaster-content-navigation-item-outer .tourmaster-content-navigation-tab.tourmaster-active {
        background: #ffa019;
        color: #FFF;
    }

    .tourmaster-content-navigation-item-outer .tourmaster-content-navigation-tab:hover {
        background: #ffa019;
        color: #FFF;
    }

    .tourmaster-content-navigation-item-outer .tourmaster-content-navigation-tab {
        background: #234076;
        color: #FFF;
        padding: 15px 25px 15px;
        border-radius: 5px 5px 0px 0px;
    }

    #hotels,
    #activities,
    #sightseeing,
    #transfer {
        display: none;
    }

    .tourmaster-tour-grid.tourmaster-tour-frame .tourmaster-tour-content-wrap {
        height: 100% !important;
    }
    label#add_more_rooms
    {
        font-size: 12px;
        cursor:pointer;
    }
    label.remove_more_rooms {
        font-size: 14px;
        cursor: pointer;
        background: #f44336;
        color: white;
        padding-bottom: 6px;
        width: 25px;
        height: 25px;
        text-align: center;
        display: inline-block;
        border-radius: 20px;
        margin-top: 15px;
    }
    .col-md-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }
    .col-md-5 {
        -ms-flex: 0 0 41.666667%;
        flex: 0 0 41.666667%;
        max-width: 41.666667%;
    }
    .col-md-8 {
        -ms-flex: 0 0 66.666667%;
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }
    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    .col-md-4 {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
    .text-right {
        text-align: right !important;
    }
    span.rooms_text {
        display: block;
        margin-top: 14px;
    }

    /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
    .active1, .collapsible:hover {
      background-color: #ccc;
  }

  .active2, .collapsible1:hover {
      background-color: #ccc;
  }

  /* Style the collapsible content. Note: hidden by default */
  .content {
      padding: 0 18px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
  }
  .content1 {
      padding: 0 18px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
  }

  .collapsible
  {
      width: 100%;
      text-align: left;
      margin-bottom: 10px !important;
  }
  .collapsible:after {
      content: '\02795'; /* Unicode character for "plus" sign (+) */
      font-size: 14px;
      color: white;
      float: right;
      margin-left: 5px;
  }

  .collapsible1
  {
      width: 100%;
      text-align: left;
      margin-bottom: 10px !important;
  }
  .collapsible1:after {
      content: '\02795'; /* Unicode character for "plus" sign (+) */
      font-size: 14px;
      color: white;
      float: right;
      margin-left: 5px;
  }

  .active1:after {
      content: "\2796"; /* Unicode character for "minus" sign (-) */
      color:#444;
  }

  .active2:after {
      content: "\2796"; /* Unicode character for "minus" sign (-) */
      color:#444;
  }
  .btn-sm
  {
   font-size: 0.7143rem;
   padding: 4px 12px;
}
.transfer_guide_remove
{
    background-color: red !important;
    border-radius: 5px;
}
.tourmaster-tour-style-2 .tourmaster-booking-tab-title {
    padding-top: 0px !important;
}
.tourmaster-lightbox-wrapper .tourmaster-lightbox-head {
    margin-bottom: 0px !important;
}
#second_step_hotel,#second_step_sightseeing
{
    display:none;
}
.include_hotel,.include_sightseeing,.include_transfer
{
    float: right;
    border-radius: 20px;
}
#go_back_hotel_btn,#go_back_sightseeing_btn
{
   margin-top: 20px;

}
#selectHotelModal,#selectSightseeingModal
{
    max-width: 1000px !important;
}

.bg-dark {

    height: 130px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    position: relative;
    background-position:center;
}
.vehicle_selected
{
  background-color:#daa520 !important;
}
.box .overlay {
    z-index: 50;
    background: rgba(103, 58, 183, 0.39);
    border-radius: 5px;
}  
p.font-size-26.text-center.color-tiles-text a {
    color: #673AB7 !IMPORTANT;
    font-size:10px;
}
p.font-size-26.text-center.color-tiles-text {
    background: #e1e1e1;
    width: 100%;
    padding: 5px 10px;
    height: auto;
    display: flex;
    justify-content: center;
    color: #0088ff !important;
    border-radius: 5px;
    align-items: center;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    margin-bottom:0px;
}
.profile-card-social__item
{
  color:white !important;
}
.para-h {
    color: #254870;
    font-size: 20px;
}
p.price {
    text-align: right;
    padding: 10px 30px;
    background: #ffffff;
    color: #133a67;
    width: auto;
    display: inline;
    float: right;
    border: 1px dashed #133a67;
}
span#sightseeing_total_price_text {
    display: block;
}
.change_remove_link
{
	cursor: pointer;
	color:#234076;
}
.text-center {
    margin-bottom: 5%;
    text-align: center;
}
.form-control-modal
{
    height: 40px;
    padding: 10px !important;
    width: 100% !important;
}
#restaurant_food_select_info_head
{
    display: none;
}
.food-selected-all-text
{
    padding: 0 !important;
}
.tourmaster-lightbox-content .error_text
{
    margin: 40px !important;
    text-align: center !important;
}
#change_date_button
{
    margin-top: 20px;
}
#change_date_link
{
    color:white;
}
.driver_div
{
    border: none !important;
}
.guide_div
{
    border: none !important;
}

</style>
<?php
if($result_data['errorCode']=="0")
{
    $markup=$result_data['markup'];
    $get_vehicles=$result_data['get_vehicles'];
    $activity_pax_count_array=$result_data['activity_pax_count_array'];
    ?>
    <script>
        jQuery(document).ready(function () {
            jQuery("#click-hotels").click(function () {
                jQuery("#hotels").show();
            });

            jQuery("#click-activities").click(function () {
                jQuery("#activities").show();
            });

            jQuery("#click-transfer").click(function () {
                jQuery("#transfer").show();
            });

            jQuery("#click-sightseeing").click(function () {
                jQuery("#sightseeing").show();
            });

            jQuery(".tourmaster-content-navigation-tab").click(function () {
                var id = this.id;
                jQuery('.navtab').hide();
                jQuery('.' + id).show();
                jQuery(".tourmaster-content-navigation-tab").removeClass("tourmaster-active");
                jQuery(this).addClass("tourmaster-active");
            });
        });
    </script>
    <?php
    $warning_count=0;
    ?>
    <div class="single-tour">
        <div class="traveltour-page-wrapper" id="traveltour-page-wrapper">
            <div class="tourmaster-page-wrapper tourmaster-tour-style-2 tourmaster-with-sidebar"
            id="tourmaster-page-wrapper">
            <div class="tourmaster-single-header"
            style="background-image: url(<?php echo get_site_url(); ?>/wp-content/uploads/2018/07/single-top-bg.jpg);">
            <div class="tourmaster-single-header-background-overlay"></div>
            <div class="tourmaster-single-header-top-overlay"></div>
            <div class="tourmaster-single-header-container tourmaster-container">
                <div class="tourmaster-single-header-container-inner">
                    <div class="tourmaster-single-header-title-wrap tourmaster-item-pdlr"></div>
                </div>
            </div>
        </div>
        <div class="tourmaster-template-wrapper">
           <form class="tourmaster-single-tour-booking-fields tourmaster-update-header-price tourmaster-form-field tourmaster-with-border" method="post" action="../itinerary-booking" id="traveldoor-activity-booking-fields" data-ajax-url="javascript:void(0);">
            <div class="tourmaster-tour-booking-bar-container tourmaster-container">
                <div class="tourmaster-tour-booking-bar-container-inner">
                    <div class="tourmaster-tour-booking-bar-anchor tourmaster-item-mglr"></div>
                    <div class="tourmaster-tour-booking-bar-wrap tourmaster-item-mglr"
                    id="tourmaster-tour-booking-bar-wrap">
                    <div class="tourmaster-tour-booking-bar-outer book-box-div">
                        <div class="tourmaster-header-price tourmaster-item-mglr" id="bookNowHref">
                            <div class="tourmaster-header-price-wrap">
                                <div class="tourmaster-tour-price-wrap tourmaster-discount">
                                    <span class="tourmaster-tour-price">
                                                <!-- <span class="tourmaster-head">From</span>
                                                    <span class="tourmaster-tail">$1,487</span> -->
                                                </span>
                                                <?php
                                                $markup_cost=round(($result_data['itinerary']['total_cost']*$result_data['markup'])/100);
                                                $total_cost=round(($result_data['itinerary']['total_cost']+$markup_cost));
                                                ?>
                                                <span class="tourmaster-tour-discount-price">GEL <span id="total_cost_text"><?php echo  $total_cost ?></span></span>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="tourmaster-tour-booking-bar-inner">
                                        <div class="tourmaster-booking-tab-title clearfix" id="tourmaster-booking-tab-title">
                                            <div class="tourmaster-booking-tab-title-item tourmaster-active" data-tourmaster-tab="booking">Booking Form</div>
                                        </div>
                                        <div class="tourmaster-booking-tab-content tourmaster-active" data-tourmaster-tab="booking">
                                           <input type="hidden" name="warning_count" id="warning_count" value="<?php echo $warning_count; ?>">
                                           <input type="hidden" name="total_cost" id="total_cost" value="<?php echo $total_cost ?>">
                                           <input type="hidden" name="markup_per" id="markup_per"  value="<?php echo $result_data['markup'] ?>">
                                           <input type="hidden" name="total_cost_w_markup" id="total_cost_w_markup" value="<?php echo $result_data['itinerary']['total_cost'] ?>">
                                           <div class="row rooms_div room-input-wrap" id="rooms_div__1" style="margin-top: 30px;">
                                            <div class="col-md-2">
                                                &nbsp; &nbsp;
                                       <!--  <span class="rooms_text">Room 1</span>
                                        <input type="hidden" name="rooms_count[]" class="rooms_count" id="rooms_count" value="1"> -->
                                    </div>
                                    <div class="col-md-5" >
                                        <label>Adults</label>
                                        <select name="select_adults[]" class="form-control select_adults" required="required">
                                            <option value="">Adults</option>
                                            <?php
                                            for($i=1;$i<=50;$i++)
                                            {
                                                if($i==1)
                                                {
                                                    echo '<option value="'.$i.'" selected>'.$i.'</option>'; 
                                                    
                                                }
                                                else
                                                {
                                                  echo '<option value="'.$i.'">'.$i.'</option>';
                                              }

                                          }
                                          ?>
                                      </select>
                                  </div>
                                  <div class="col-md-5">
                                   <label>Child</label>
                                   <select name="select_child[]" class="form-control select_child" required="required">
                                    <option value="">Child</option>
                                    <?php
                                    for($i=0;$i<=50;$i++)
                                    {
                                       if($i==0)
                                       {
                                        echo '<option value="'.$i.'" selected>'.$i.'</option>'; 

                                    }
                                    else
                                    {
                                      echo '<option value="'.$i.'">'.$i.'</option>';
                                  }

                              }
                              ?>
                          </select>
                      </div>
                                   <!--  <div class="col-md-3 add_more_div">
                                       
                                   </div> -->
                               </div>
                               <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4 text-right">
                                 <!--   <label id="add_more_rooms">+ Add room</label> -->
                             </div>
                         </div>
                         <div class="pd-tb-30 text-center book-div-wrap">
                           <input class="tourmaster-button" type="submit" value="BOOK PACKAGE" />
                           <input class="tourmaster-button forpdf" type="button" value="DOWNLOAD ITINERARY" style="margin-right:0;"/>
                           <input id="calculate_cost" type="button" style="display:none;">
                       </div>
                   </div>
               </div>
           </div>


<!-- Book Now Button -->
           
                    <a href="#tourmaster-tour-booking-bar-wrap"><img src="https://traveldoor.ge/wp-content/uploads/2021/03/Top-Scroll.png" class="book-now-button"></a>
                             
                             <!--- Script --->
                                  <script>
                                    jQuery(document).ready(function() { 
                                    fadeMenuWrap(); 
                                    jQuery(window).scroll(fadeMenuWrap);
                                });
                                
                                function fadeMenuWrap() { 
                                    var scrollPos = window.pageYOffset || document.documentElement.scrollTop; 
                                    if (scrollPos > 600) { 
                                        jQuery('.book-now-button').fadeIn(600); 
                                    } else { 
                                        jQuery('.book-now-button').fadeOut(600); 
                                    } 
                                } 
                                    </script>

                              <!--- Script Closed--->
            <!-- Book Now Button Closed -->


                     <!--    <script>
                     //Get the button
                   var mybutton = document.getElementById("booknowbtn");

                     // When the user scrolls down 20px from the top of the document, show the button
                     window.onscroll = function () { scrollFunction() };

                     function scrollFunction() {
                      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                         mybutton.style.display = "block";
                          } else {
                  mybutton.style.display = "none";
                         }
                         }

                             // When the user clicks on the button, scroll to the top of the document
                              function topFunction() {
                            document.body.scrollTop = 0;
                               document.documentElement.scrollTop = 0;
                                    }
                                </script> -->


            
                              <!--  <a href="#bookNowHref" id="booknowbtn" title="Go to top"><img src="<?php echo plugin_dir_url( __FILE__ ). "../include/images/BookNow.png"; ?>" class="book-now-button"></a> -->
                             
                                <div class="tourmaster-tour-booking-bar-widget  traveltour-sidebar-area booking-bar-hide1">
                                    <div id="text-12" class="widget widget_text traveltour-widget">
                                        <div class="textwidget"><span class="gdlr-core-space-shortcode"
                                            style="margin-top: -10px ;"></span>
                                            <div class="gdlr-core-widget-box-shortcode "
                                            style="color: #c9e2ff ;background-image: url(https://demo.goodlayers.com/traveltour/hiking/wp-content/uploads/2019/05/sidebar-bg.jpg) ;">
                                            <h3 class="gdlr-core-widget-box-shortcode-title" style="color: #ffffff ;">
                                            Get a Question?</h3>
                                            <div class="gdlr-core-widget-box-shortcode-content">
                                                <p>Do not hesitage to give us a call. We are an expert team and we are
                                                happy to talk to you.</p>
                                                <p><i class="fa fa-phone"
                                                    style="font-size: 20px ;color: #ffa11a ;margin-right: 10px ;"></i>
                                                    <span style="font-size: 20px; color: #ffffff; font-weight: 600;">
                                                    +995 322 35 35 99 </span></p>
                                                    <p><i class="fa fa-envelope-o"
                                                        style="font-size: 17px ;color: #ffa11a ;margin-right: 10px ;"></i>
                                                        <span
                                                        style="font-size: 14px; color: #fff; font-weight: 600;">office@traveldoor.ge</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="tourmaster-single-tour-content-wrap">
                        <div class="gdlr-core-page-builder-body">
                            <div class="gdlr-core-pbf-wrapper" style="padding: 45px 0px 20px 0px;">
                                <div class="gdlr-core-pbf-background-wrap"></div>
                                <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
                                    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">

                                        <div class="gdlr-core-pbf-element">
                                            <div
                                            class="tourmaster-tour-title-item tourmaster-item-pdlr tourmaster-item-pdb clearfix">
                                            <style type="text/css">

                                                /* The Modal (background) */
                                                .modal {
                                                  display: none; /* Hidden by default */
                                                  position: fixed; /* Stay in place */
                                                  z-index: 9999; /* Sit on top */
                                                  padding-top: 100px; /* Location of the box */
                                                  left: 0;
                                                  top: 0;
                                                  width: 100%; /* Full width */
                                                  height: 100%; /* Full height */
                                                  overflow: auto; /* Enable scroll if needed */
                                                  background-color: rgb(0,0,0); /* Fallback color */
                                                  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                                              }

                                              /* Modal Content */
                                              .modal-content {
                                                  position: relative;
                                                  background-color: #fefefe;
                                                  margin: auto;
                                                  padding: 0;
                                                  border: 1px solid #888;
                                                  width: 80%;
                                                  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
                                                  -webkit-animation-name: animatetop;
                                                  -webkit-animation-duration: 0.4s;
                                                  animation-name: animatetop;
                                                  animation-duration: 0.4s
                                              }

                                              /* Add Animation */
                                              @-webkit-keyframes animatetop {
                                                  from {top:-300px; opacity:0} 
                                                  to {top:0; opacity:1}
                                              }

                                              @keyframes animatetop {
                                                  from {top:-300px; opacity:0}
                                                  to {top:0; opacity:1}
                                              }

                                              /* The Close Button */
                                              .close {
                                                  color: white;
                                                  float: right;
                                                  font-size: 28px;
                                                  font-weight: bold;
                                              }

                                              .close:hover,
                                              .close:focus {
                                                  color: #000;
                                                  text-decoration: none;
                                                  cursor: pointer;
                                              }

                                              .modal-header {
                                               padding: 2px 16px;
                                               background-color: #0b233e;
                                               color: white;
                                               border-bottom: 1px solid #0b233e;
                                           }

                                           .modal-body {padding: 2px 16px;}

                                           .modal-footer {
                                              padding: 2px 7px;
                                              background-color: white;
                                              color: white;
                                              text-align: center;
                                          }
                                          .errors_text
                                          {
                                            text-align: center;
                                            padding: 20px;
                                        }
                                        .errors_text_title
                                        {
                                            text-align: center;
                                            color: white;
                                        }
                                        .loader_text
                                        {
                                            text-align: center;
                                            padding: 20px;
                                        }
                                        .loader_text_title
                                        {
                                            text-align: center;
                                            color: white;
                                        }
                                        div#myLoaderModal h3#loader_text_title {
                                            color: white;
                                        }
                                        div#myLoaderModal .modal-header {
                                            background: no-repeat;
                                            color: white !important;
                                            border: none;
                                        }
                                        div#myLoaderModal .modal-content {
                                            background: none !important;
                                            box-shadow: none !important;
                                            border: none !important;
                                        }
                                        div#myLoaderModal {
                                            position: fixed;
                                            z-index: 9999;
                                            padding-top: 100px;
                                            left: 0;
                                            top: 0;
                                            width: 100%;
                                            height: 100%;
                                            overflow: auto;
                                            background-color: rgb(0,0,0);
                                            background-color: rgb(0 0 0 / 82%) !important;
                                        }
                                        div#myLoaderModal {
                                            padding: 0;
                                            align-items: center;
                                        }
                                        div#myErrorModal h3#errors_text_title {
                                            color: white;
                                            margin: 0;
                                            padding: 15px;
                                            font-size: 23px;
                                        }
                                        div#myErrorModal button.close-modal {
                                            margin-bottom: 20px;
                                        }
                                        div#myErrorModal p#errors_text {
                                            margin-bottom: 0;
                                        }
                                        div#myErrorModal .modal-content {
                                            width: 50%;
                                            border: none;
                                            border-radius: 10px;
                                            overflow: hidden;
                                        }
                                        div#myErrorModal {
                                            padding: 0;
                                            background-color: rgb(0 0 0 / 82%) !important;
                                        }
                                        @media screen and (max-width:600px){
                                            div#myErrorModal .modal-content {
                                                width: 80%;

                                            }
                                        }
                                    </style>


                                    <!-- Trigger/Open The Modal -->
                                    <button id="myErrorModalBtn" type="button" style="display: none">Open Modal</button>

                                    <!-- Trigger/Open The Modal -->
                                    <button id="myLoaderModalBtn" type="button" style="display: none">Open Modal</button>

                                    <!-- The Modal -->
                                    <div id="myErrorModal" class="modal">

                                      <!-- Modal content -->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <span class="close">&times;</span>
                                          <h3 id="errors_text_title" class="errors_text_title"></h3>
                                      </div>
                                      <div class="modal-body">
                                       <p id="errors_text" class="errors_text" ></p>

                                   </div>
                                   <div class="modal-footer">
                                    <button type="button" class="close-modal">OK</button>
                                </div>
                            </div>

                        </div>

                        <!-- The Modal -->
                        <div id="myLoaderModal" class="modal" style="background: none;text-align: center;">

                          <!-- Modal content -->
                          <div class="modal-content">
                            <div class="modal-header">
                                <span class="close_loader" style="display: none">&times;</span>
                                <h3 id="loader_text_title" class="loader_text_title"> Calculating Total Cost For Your Package...</h3>
                            </div>
                            <div class="modal-body">
                               <div class="country-loader-new" style="width: 100px;height: 100px;display:inline-block;">

                                <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                <circle fill="#ffa019" stroke="none" cx="6" cy="50" r="6">
                                    <animateTransform 
                                    attributeName="transform" 
                                    dur="1s" 
                                    type="translate" 
                                    values="0 15 ; 0 -15; 0 15" 
                                    repeatCount="indefinite" 
                                    begin="0.1"/>
                                </circle>
                                <circle fill="#ffa019" stroke="none" cx="30" cy="50" r="6">
                                    <animateTransform 
                                    attributeName="transform" 
                                    dur="1s" 
                                    type="translate" 
                                    values="0 10 ; 0 -10; 0 10" 
                                    repeatCount="indefinite" 
                                    begin="0.2"/>
                                </circle>
                                <circle fill="#ffa019" stroke="none" cx="54" cy="50" r="6">
                                    <animateTransform 
                                    attributeName="transform" 
                                    dur="1s" 
                                    type="translate" 
                                    values="0 5 ; 0 -5; 0 5" 
                                    repeatCount="indefinite" 
                                    begin="0.3"/>
                                </circle>
                            </svg>
                        </div>

                    </div>

                </div>

            </div>

            <script>
// Get the modal
var modal = document.getElementById("myErrorModal");

// Get the modal
var loadermodal = document.getElementById("myLoaderModal");

// Get the button that opens the modal
var btn = document.getElementById("myErrorModalBtn");

// Get the button that opens the modal
var loaderbtn = document.getElementById("myLoaderModalBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Get the <span> element that closes the modal
var span_loader = document.getElementsByClassName("close_loader")[0];
// Get the <span> element that closes the modal
var span_close_modal = document.getElementsByClassName("close-modal")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "flex";
}

// When the user clicks the button, open the modal 
loaderbtn.onclick = function() {
  loadermodal.style.display = "flex";
}


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks on OK, close the modal
span_close_modal.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span_loader.onclick = function() {
  loadermodal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
}
}
</script>
<?php
if(isset($_GET['source']))
{

    ?>
    <div class="row main-i-row">
        <div class="col-md-6" style="padding: 20px;">
            <label for="package_date" class="pkg-label"><b class="package-start-date">Package Start Date</b></label><br>

           
        <?php
        $start_date =  $_SESSION['itinerary_date_from'];
        $total_days = $result_data['itinerary']['itinerary_tour_days'];
        ?>
        <input type="text" id="change_date_from" class="tourmaster-datepicker input-date-icon"   class="form-control-modal" value="<?php echo $_SESSION['itinerary_date_from']; ?>" />
            <!-- <input type="date" id="change_date_from" min="<?php //echo date('Y-m-d'); ?>" class="form-control-modal" value="<?php //echo $_SESSION['itinerary_date_from']; ?>"> -->
            <a style="pointer-events: none;cursor: default;" id="change_date_link"  href="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>"> <button type="button" class="btn btn-primary pkg-btn" id="change_date_button"  style="background-color:#DCDCDC;
            pointer-events: none;cursor: not-allowed">
            CHANGE DATE</button></a>
       
        </div>
       
        <div class="col-md-6 Edate">
            <label for="endDate" class="pkg-label"><b class="package-start-date">Package End Date</b></label><br>
        </div>
        <div class="Edate package-start-div">
        <input type="text" id="EndDate" class="tourmaster-datepicker" value="<?php echo date('Y-m-d', strtotime($start_date. ' + '.$total_days.' days'));?> " class="form-control-modal EndDate" disabled>
        </div>
    </div>
    <script>
        jQuery(document).ready(function() {

            jQuery(document).on("change","#change_date_from",function()
            {   
                alert()
                var date=jQuery(this).val();
                var url="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>";
                jQuery('#change_date_link').attr("href",url+"&date_from="+date);
                jQuery("#change_date_button").removeAttr("style");
            })
        })
    </script>
    <?php
}
else
{
    
    $start_date =  $_SESSION['itinerary_date_from'];
    $total_days = $result_data['itinerary']['itinerary_tour_days'];
    ?>
    <div class="row main-i-row">
        <div class="col-md-6" style="padding: 20px;">
            <label for="package_date" class="pkg-label"><b class="package-start-date">Package Start Date </b></label> 
            
            
        <input type="text" id="change_date_from" class="tourmaster-datepicker"   class="form-control-modal" value="<?php echo $_SESSION['itinerary_date_from']; ?>" />
     
            <!-- <input type="date" id="change_date_from" min="<?php echo date('Y-m-d'); ?>" class="form-control-modal" value="<?php echo $_SESSION['itinerary_date_from']; ?>"> -->
          
            <a style="pointer-events: none;cursor: default;"  id="change_date_link" href="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>"> 
            <button type="button" class="btn btn-primary pkg-btn" id="change_date_button"  style="background-color:#DCDCDC;
            pointer-events: none;cursor: not-allowed">
            CHANGE DATE</button></a>

        </div>
        <div class="col-md-6 Edate">
            <label for="endDate" class="pkg-label"><b class="package-start-date">Package End Date</b></label> 
       
        <div class="Edate package-start-div">
        <input type="text" id="EndDate" class="tourmaster-datepicker" value="<?php echo date('Y-m-d', strtotime($start_date. ' + '.$total_days.' days'));?> " class="form-control-modal EndDate" disabled>    
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {

            jQuery(document).on("change","#change_date_from",function()
            {   
                alert()
                var date=jQuery(this).val();
                var url="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>";
                jQuery('#change_date_link').attr("href",url+"&date_from="+date);
                jQuery("#change_date_button").removeAttr("style");
            })
        })
    </script>

    <?php
}
?>
<h1 class="tourmaster-tour-title-item-title tourpack_info"
style="font-size: 27px ;font-weight: 700 ;text-transform: none ;"><?php echo $result_data['itinerary']['itinerary_name'] ?></h1>

</div>
</div>
<div class="gdlr-core-pbf-element">
    <div
    class="gdlr-core-icon-list-item gdlr-core-item-pdlr gdlr-core-item-pdb clearfix  gdlr-core-left-align">
    <ul class="day-night12">
        <li
        class=" gdlr-core-skin-divider gdlr-core-column-20 gdlr-core-column-first clearfix">
        <span class="gdlr-core-icon-list-icon-wrap"
        style="margin-top: 0px ;margin-right: 18px ;"><i
        class="gdlr-core-icon-list-icon icon_clock_alt"
        style="color: #234076 ;font-size: 22px ;width: 22px ;"></i></span>
        <div class="gdlr-core-icon-list-content-wrap"><span
            class="gdlr-core-icon-list-content gettour_inf0"
            style="font-size: 15px ;"><?php echo $result_data['itinerary']['itinerary_tour_days'] ?> Nights <?php echo ($result_data['itinerary']['itinerary_tour_days']+1) ?> Days</span></div>
            <input type="hidden" value="<?php echo ($result_data['itinerary']['itinerary_tour_days']+1) ?>" id = "total_days">
        </li>
    </ul>
</div>
</div>

<div class="gdlr-core-pbf-element">
    <div
    class="gdlr-core-gallery-item gdlr-core-item-pdb clearfix gdlr-core-gallery-item-style-thumbnail gdlr-core-item-pdlr ">
    <div
    class="gdlr-core-gallery-with-thumbnail-wrap gdlr-core-below-slider gdlr-core-disable-hover">
    <div class="gdlr-core-flexslider flexslider gdlr-core-js-2"
    data-type="slider" data-effect="default" data-nav="none"
    data-thumbnail="1">
    <ul class="slides">
        <?php

        if(!empty($result_data['itinerary']['itinerary_images']))
        {
            if(!empty($result_data['itinerary']['itinerary_images']['itinerary']))
            {
             $get_itinerary_images=$result_data['itinerary']['itinerary_images']['itinerary'];
             for($count=0;$count<count($get_hotel_images);$count++)
             {
                ?>
                <li>
                    <div class="gdlr-core-gallery-list gdlr-core-media-image">
                        <a class="gdlr-core-lightgallery gdlr-core-js " href="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/itinerary_images/'.$get_itinerary_images[$count]; ?>" data-lightbox-group="gdlr-core-img-group-1"><img src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/itinerary_images/'.$get_itinerary_images[$count]; ?>" alt="" width="1500" style="height:500px" title="Landscape View" /><span class="gdlr-core-image-overlay  gdlr-core-gallery-image-overlay gdlr-core-center-align gdlr-core-no-hover"><span class="gdlr-core-image-overlay-content" ><span class="gdlr-core-image-overlay-title gdlr-core-title-font" ></span></span>
                        </span>
                    </a>
                </div>
            </li>

            <?php

        }
    }


    if(!empty($result_data['itinerary']['itinerary_images']['hotel']))
    {
     $get_hotel_images=$result_data['itinerary']['itinerary_images']['hotel'];
     for($count=0;$count<count($get_hotel_images);$count++)
     {
        ?>
        <li>
            <div class="gdlr-core-gallery-list gdlr-core-media-image">
                <a class="gdlr-core-lightgallery gdlr-core-js " href="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/hotel_images/'.$get_hotel_images[$count]; ?>" data-lightbox-group="gdlr-core-img-group-1"><img src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/hotel_images/'.$get_hotel_images[$count]; ?>" alt="" width="1500" style="height:500px" title="Landscape View" /><span class="gdlr-core-image-overlay  gdlr-core-gallery-image-overlay gdlr-core-center-align gdlr-core-no-hover"><span class="gdlr-core-image-overlay-content" ><span class="gdlr-core-image-overlay-title gdlr-core-title-font" ></span></span>
                </span>
            </a>
        </div>
    </li>

    <?php

}
}

if(!empty($result_data['itinerary']['itinerary_images']['sightseeing']))
{
 $get_sightseeing_images=$result_data['itinerary']['itinerary_images']['sightseeing'];
 for($count=0;$count<count($get_sightseeing_images);$count++)
 {
    ?>
    <li>
        <div class="gdlr-core-gallery-list gdlr-core-media-image">
            <a class="gdlr-core-lightgallery gdlr-core-js " href="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/sightseeing_images/'.$get_sightseeing_images[$count] ?>" data-lightbox-group="gdlr-core-img-group-1"><img src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/sightseeing_images/'.$get_sightseeing_images[$count] ?>" alt="" width="1500" style="height:500px" title="Landscape View" /><span class="gdlr-core-image-overlay  gdlr-core-gallery-image-overlay gdlr-core-center-align gdlr-core-no-hover"><span class="gdlr-core-image-overlay-content" ><span class="gdlr-core-image-overlay-title gdlr-core-title-font" ></span></span>
            </span>
        </a>
    </div>
</li>

<?php

}
}

if(!empty($result_data['itinerary']['itinerary_images']['transfer']))
{
 $get_transfer_images=array_unique($result_data['itinerary']['itinerary_images']['transfer']);
 for($count=0;$count<count($get_transfer_images);$count++)
 {
    ?>
    <li>
        <div class="gdlr-core-gallery-list gdlr-core-media-image">
            <a class="gdlr-core-lightgallery gdlr-core-js " href="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/vehicle_images/'.$get_transfer_images[$count] ?>" data-lightbox-group="gdlr-core-img-group-1"><img src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/vehicle_images/'.$get_transfer_images[$count] ?>" alt="" width="1500" style="height:500px" title="Landscape View" /><span class="gdlr-core-image-overlay  gdlr-core-gallery-image-overlay gdlr-core-center-align gdlr-core-no-hover"><span class="gdlr-core-image-overlay-content" ><span class="gdlr-core-image-overlay-title gdlr-core-title-font" ></span></span>
            </span>
        </a>
    </div>
</li>

<?php

}
}


if(!empty($result_data['itinerary']['itinerary_images']['activity']))
{
 $get_activity_images=$result_data['itinerary']['itinerary_images']['activity'];

 for($count=0;$count<count($get_activity_images);$count++)
 {
    ?>
    <li>
        <div class="gdlr-core-gallery-list gdlr-core-media-image">
            <a class="gdlr-core-lightgallery gdlr-core-js " href="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/activities_images/'.$get_activity_images[$count] ?>" data-lightbox-group="gdlr-core-img-group-1"><img src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/activities_images/'.$get_activity_images[$count] ?>" alt="" width="1500" style="height:500px" title="Landscape View" /><span class="gdlr-core-image-overlay  gdlr-core-gallery-image-overlay gdlr-core-center-align gdlr-core-no-hover"><span class="gdlr-core-image-overlay-content" ><span class="gdlr-core-image-overlay-title gdlr-core-title-font" ></span></span>
            </span>
        </a>
    </div>
</li>

<?php

}
}

}

?>


</ul>
</div>
<div class="gdlr-core-sly-slider gdlr-core-js-2">
    <ul class="slides">
        <?php
        if(!empty($result_data['itinerary']['itinerary_images']))
        {
           if(!empty($result_data['itinerary']['itinerary_images']['itinerary']))
           {
             $get_itinerary_images=$result_data['itinerary']['itinerary_images']['itinerary'];
             for($count=0;$count<count($get_hotel_images);$count++)
             {
                ?>
                <li class="gdlr-core-gallery-list gdlr-core-item-mglr">
                    <div class="gdlr-core-media-image"><img
                     src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/itinerary_images/'.$get_itinerary_images[$count]; ?>"
                     alt="" width="700" height="450"
                     title="Landscape View" /></div>
                 </li>
                 <?php

             }
         }


         if(!empty($result_data['itinerary']['itinerary_images']['hotel']))
         {
             $get_hotel_images=array_unique($result_data['itinerary']['itinerary_images']['hotel']);
             for($count=0;$count<count($get_hotel_images);$count++)
             {
                ?>
                <li class="gdlr-core-gallery-list gdlr-core-item-mglr">
                    <div class="gdlr-core-media-image"><img
                     src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/hotel_images/'.$get_hotel_images[$count]; ?>"
                     alt="" width="700" height="450"
                     title="Landscape View" /></div>
                 </li>
                 <?php

             }
         }

         if(!empty($result_data['itinerary']['itinerary_images']['sightseeing']))
         {
             $get_sightseeing_images=array_unique($result_data['itinerary']['itinerary_images']['sightseeing']);
             for($count=0;$count<count($get_sightseeing_images);$count++)
             {
                ?>

                <li class="gdlr-core-gallery-list gdlr-core-item-mglr">
                    <div class="gdlr-core-media-image"><img
                     src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/sightseeing_images/'.$get_sightseeing_images[$count] ?>"
                     alt="" width="700" height="450"
                     title="Landscape View" /></div>
                 </li>

                 <?php

             }
         }
         if(!empty($result_data['itinerary']['itinerary_images']['transfer']))
         {
             $get_transfer_images=array_unique($result_data['itinerary']['itinerary_images']['transfer']);
             for($count=0;$count<count($get_transfer_images);$count++)
             {
                ?>
                <li class="gdlr-core-gallery-list gdlr-core-item-mglr">
                    <div class="gdlr-core-media-image"><img
                     src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/vehicle_images/'.$get_transfer_images[$count]; ?>"
                     alt="" width="700" height="450"
                     title="Landscape View" /></div>
                 </li>
                 <?php

             }
         }

         if(!empty($result_data['itinerary']['itinerary_images']['activity']))
         {
             $get_activity_images=array_unique($result_data['itinerary']['itinerary_images']['activity']);
             for($count=0;$count<count($get_activity_images);$count++)
             {
                ?>

                <li class="gdlr-core-gallery-list gdlr-core-item-mglr">
                    <div class="gdlr-core-media-image"><img
                      src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/activities_images/'.$get_activity_images[$count] ?>"
                      alt="" width="700" height="450"
                      title="Landscape View" /></div>
                  </li>

                  <?php

              }
          }

      }

      ?>

  </ul>
</div>
</div>
</div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 45px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>

<div class="gdlr-core-pbf-element">
    <div
    class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align">
    <div class="gdlr-core-text-box-item-content packtour_desc" style="text-transform: none ;">
        <?php echo $result_data['itinerary']['itinerary_tour_description'] ?>
    </div>
</div>
</div>

<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 25px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>

<div class="gdlr-core-pbf-element">
    <div class="tourmaster-content-navigation-item-wrap clearfix"
    style="padding-bottom: 0px;">
    <div class="tourmaster-content-navigation-item-outer"
    id="tourmaster-content-navigation-item-outer"
    style="border-bottom-width: 1px;border-bottom-style: solid;">
    <div
    class="tourmaster-content-navigation-item-container tourmaster-container">
    <div
    class="tourmaster-content-navigation-item tourmaster-item-pdlr">
    <a class="tourmaster-content-navigation-tab tourmaster-active"
    href="javascript:void(0);" id="click-itinerary">Itinerary</a>
    <a class="tourmaster-content-navigation-tab"
    href="javascript:void(0);" id="click-hotels">Hotels</a>
    <a class="tourmaster-content-navigation-tab"
    href="javascript:void(0);"
    id="click-activities">Activities</a>
    <a class="tourmaster-content-navigation-tab"
    href="javascript:void(0);"
    id="click-sightseeing">Daily Tour</a>
    <a class="tourmaster-content-navigation-tab"
    href="javascript:void(0);"
    id="click-transfer">Transfers</a>
    <a class="tourmaster-content-navigation-tab"
    href="javascript:void(0);"
    id="click-restaurant">Restaurants</a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="gdlr-core-pbf-wrapper click-itinerary navtab" style="padding: 20px 0px 20px 0px;"
data-skin="Blue Icon" id="itinerary">
<div class="gdlr-core-pbf-background-wrap"></div>
<div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="gdlr-core-pbf-element">
            <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr"
            style="padding-bottom:35px;">
            <div class="gdlr-core-title-item-title-wrap">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size:24px; font-weight:600; letter-spacing:0px; text-transform:none;">
                Itinerary<span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6>
        </div>
    </div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-toggle-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-toggle-box-style-background-title gdlr-core-left-align"
    style="padding-bottom:15px;">
    <div class="gdlr-core-toggle-box-item-tab clearfix">
        <div
        class="gdlr-core-toggle-box-item-icon gdlr-core-js gdlr-core-skin-icon ">
    </div>
    <?php 


    $total_whole_itinerary_cost=0;
    $first_sightseeing="first-sightseeing";
    for($days_count=0;$days_count<count($result_data['itinerary']['itinerary_details_day_wise'])-1;$days_count++)
    {
        ?>
        <div class="gdlr-core-toggle-box-item-content-wrapper">
                                                    <!--  <h4
                                                        class="gdlr-core-toggle-box-item-title gdlr-core-js  gdlr-core-skin-e-background gdlr-core-skin-e-content">
                                                        <span class="gdlr-core-head">Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></span><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_title']?></h4>
                                                    <div class="gdlr-core-toggle-box-item-content" > 
                                                    -->
                                                    <button type="button" class="collapsible" id="days_header<?php echo  ($days_count+1);?>">Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']." - ";
                                                    if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_itinerary_details']['sightseeing_id']))
                                                    {
                                                      $fetch_sightseeing=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_details'];

                                                      echo trim($fetch_sightseeing['sightseeing_tour_name'],",");

                                                  }
                                                  else if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']))
                                                  {
                                                   $transfer_name=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_name'];
                                                   echo 'Transfer : '.$transfer_name;
                                               } 
                                               else
                                               {
                                                echo 'Nothing planned';
                                            }
                                            ?>
                                        </button>


                                        <div class="content days_count" id="days_count<?php echo ($days_count+1); ?>">
                                           <div>
                                            <?php
                                            if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_itinerary_details']['sightseeing_id']))
                                            {
                                              $fetch_sightseeing=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_details'];
                                              
                                              echo '<p class="des discriptionnone">'.$fetch_sightseeing['sightseeing_attractions'].'</p>';

                                          }
                                          else if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']))
                                          {
                                            echo '<p class="des discriptionnone"></p>';
                                        }
                                        else
                                        {
                                            echo ' <p class="des">Spend time at leisure</p>';
                                        }
                                        ?>
                                    </div>
                                    <div class="t-div">
                                        <p class="country">

                                          <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['city_name']." ,".$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['country_name']; ?>
                                      </p>
                                      <input type="hidden" class="country_id_book" id="days_country__<?php echo ($days_count+1) ?>" value=" <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['country_id']?>">
                                      <input type="hidden" class="city_id_book" id="days_city__<?php echo ($days_count+1) ?>" value=" <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['city_id']?>">
                                      <input type="hidden" name="days_dates[]" id="days_dates__<?php echo ($days_count+1) ?>" value="<?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['date']?>">
                                  </div>
                                  <div class="day-count">
                                    <?php
                                    if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']))
                                    {

                                       $fetch_transfer=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_details'];
                                       $fetch_transfer_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_itinerary_details'];
                                       $transfer_name=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_name'];
                                       $transfer_vehicle_min=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_vehicle_min'];
                                       $transfer_vehicle_max=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_vehicle_max'];
                                        $transfer_driver_id=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_driver_id'];
                                                           	// 
                                       "<pre>";
																   // print_r($fetch_transfer);

                                       ?>
                                       <div class="t-tab">
                                        <div class="row">
                                            <div class="col-md-12 transfer_select yo" id="transfer_select__<?php echo ($days_count+1) ?>">
                                                <div class="hotel-list-div">
                                                    <div class="hotel-div">
                                                        <div class="hotel-img-div">
                                                          <?php 
                                                          $get_transfer_images=unserialize($fetch_transfer['transfer_vehicle_images']);
                                                          if(!empty($get_transfer_images[0][0]))
                                                          {
                                                              ?>
                                                              <img class="cstm-transfer-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/vehicle_images/'.$get_transfer_images[0][0]; ?>"
                                                              style="width:100%">
                                                              <?php
                                                          }
                                                          else
                                                          {
                                                            ?>
                                                            <img class="cstm-transfer-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="hotel-details">

                                                        <div class="heading-div">
                                                            <p class="hotel-name cstm-transfer-name">
                                                                <?php echo  $transfer_name; ?></p>

                                                            </div>


                                                            <p class="info cstm-transfer-car"><?php echo $fetch_transfer_itinerary['transfer_name']; ?></p>
                                                            <div
                                                            class="heading-div c-h-div">
                                                            <div class="rating ">
                                                                <span
                                                                class="span-i">DATE </span>
                                                                <p class="p-i"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                                                            </div>
                                                            <div>
                                                             <?php
                                                             $price=$fetch_transfer_itinerary['transfer_cost'];
                                                             $total_transfer_cost=round($price);

                                                             ?>

                                                             <input type="hidden" class="transfer_id<?php echo ($days_count+1) ?>" id="transfer_id__<?php echo ($days_count+1); ?>" name="transfer_id[<?php echo $days_count ?>]" value="<?php echo $fetch_transfer['transfer_id']; ?>">
                                                             <input type="hidden" class="transfer_name<?php echo ($days_count+1) ?>" name="transfer_name[<?php echo $days_count ?>]"  id="transfer_name__<?php echo ($days_count+1); ?>" value="<?php echo $transfer_name; ?>">

                                                             <input type="hidden" class="transfer_car_name<?php echo ($days_count+1) ?>" name="transfer_car_name[<?php echo $days_count ?>]"  id="transfer_car_name__<?php echo ($days_count+1); ?>" value="<?php echo $fetch_transfer_itinerary['transfer_name']; ?>">

                                                             <input type="hidden" class="transfer_type<?php echo ($days_count+1) ?> " name="transfer_type[<?php echo $days_count ?>]"  id="transfer_type__<?php echo ($days_count+1); ?>" value="<?php echo $fetch_transfer_itinerary['transfer_type']; ?>">
                                                             <input type="hidden" class="transfer_from_city<?php echo ($days_count+1) ?>" name="transfer_from_city[<?php echo $days_count ?>]"  id="transfer_from_city__<?php echo ($days_count+1); ?>" value="<?php echo $fetch_transfer_itinerary['transfer_from_city']; ?>">
                                                             <input type="hidden" class="transfer_to_city<?php echo ($days_count+1) ?>" name="transfer_to_city[<?php echo $days_count ?>]"  id="transfer_to_city__<?php echo ($days_count+1); ?>" value="<?php echo $fetch_transfer_itinerary['transfer_to_city']; ?>">
                                                             <input type="hidden" class="transfer_from_airport<?php echo ($days_count+1) ?>" name="transfer_from_airport[<?php echo $days_count ?>]"  id="transfer_from_airport__<?php echo ($days_count+1); ?>" value="<?php echo $fetch_transfer_itinerary['transfer_from_airport']; ?>">
                                                             <input type="hidden" class="transfer_to_airport<?php echo ($days_count+1) ?>" name="transfer_to_airport[<?php echo $days_count ?>]"  id="transfer_to_airport__<?php echo ($days_count+1); ?>" value="<?php echo $fetch_transfer_itinerary['transfer_to_airport']; ?>">
                                                             <input type="hidden" class="transfer_pickup<?php echo ($days_count+1) ?>" name="transfer_pickup[<?php echo $days_count ?>]"  id="transfer_pickup__<?php echo ($days_count+1); ?>" value="<?php if(!empty($fetch_transfer_itinerary['transfer_pickup']))echo $fetch_transfer_itinerary['transfer_pickup']; ?>">
                                                             <input type="hidden" class="transfer_dropoff<?php echo ($days_count+1) ?>" name="transfer_dropoff[<?php echo $days_count ?>]"  id="transfer_dropoff__<?php echo ($days_count+1); ?>" value="<?php if(!empty($fetch_transfer_itinerary['transfer_dropoff'])) echo $fetch_transfer_itinerary['transfer_dropoff']; ?>">

                                                             <input type="hidden" class="transfer_vehicle_type<?php echo ($days_count+1) ?> chnage_transfer_type" name="transfer_vehicle_type[<?php echo $days_count ?>]"  id="transfer_vehicle_type__<?php echo ($days_count+1); ?>" value="<?php if(!empty($fetch_transfer_itinerary['transfer_vehicle_type'])) echo $fetch_transfer_itinerary['transfer_vehicle_type']; else echo $fetch_transfer['transfer_vehicle_type']; ?>">
                                                             <input type="hidden" class="transfer_vehicle_min<?php echo ($days_count+1) ?>" name="transfer_vehicle_min[<?php echo $days_count ?>]"  id="transfer_vehicle_min__<?php echo ($days_count+1); ?>" value="<?php echo $transfer_vehicle_min; ?>">
                                                             <input type="hidden" class="transfer_vehicle_max<?php echo ($days_count+1) ?>" name="transfer_vehicle_max[<?php echo $days_count ?>]"  id="transfer_vehicle_max__<?php echo ($days_count+1); ?>" value="<?php echo $transfer_vehicle_max; ?>">
                                                             <input type="hidden" class="transfer_guide_id<?php echo ($days_count+1) ?>" name="transfer_guide_id[<?php echo $days_count ?>]" id="transfer_guide_id__<?php echo ($days_count+1); ?>" value="<?php if(!empty($fetch_transfer_itinerary['transfer_guide_id'])) echo $fetch_transfer_itinerary['transfer_guide_id']; ?>">
                                                             <input type="hidden" class="transfer_guide_name<?php echo ($days_count+1) ?>" name="transfer_guide_name[<?php echo $days_count ?>]" id="transfer_guide_name__<?php echo ($days_count+1); ?>" value="<?php if(!empty($fetch_transfer_itinerary['transfer_guide_name']))echo $fetch_transfer_itinerary['transfer_guide_name']; ?>">
                                                             <input type="hidden" class="transfer_guide_cost<?php echo ($days_count+1) ?>" name="transfer_guide_cost[<?php echo $days_count ?>]" id="transfer_guide_cost__<?php echo ($days_count+1); ?>" value="<?php if(!empty($fetch_transfer_itinerary['transfer_guide_cost'])) echo $fetch_transfer_itinerary['transfer_guide_cost']; ?>">
                                                             <?php
                                                             if(!empty($fetch_transfer_itinerary['transfer_guide_cost']))
                                                             {
                                                                $total_transfer_full_cost=$total_transfer_cost+$fetch_transfer_itinerary['transfer_guide_cost'];
                                                            }
                                                            else
                                                            {
                                                              $total_transfer_full_cost=$total_transfer_cost;
                                                          }
                                                          ?>
                                                          <input type="hidden" class="transfer_cost<?php echo ($days_count+1) ?>" name="transfer_cost[<?php echo $days_count ?>]" id="transfer_cost__<?php echo ($days_count+1); ?>" value="<?php echo $total_transfer_cost; ?>">
                                                          <input type="hidden" class="calc_cost transfer_total_cost<?php echo ($days_count+1) ?>" name="transfer_total_cost[<?php echo $days_count ?>]" id="transfer_total_cost__<?php echo ($days_count+1); ?>" value="<?php echo $total_transfer_full_cost; ?>">
                                                          <?php
                                                          $total_whole_itinerary_cost+=($total_transfer_full_cost);
                                                          ?>
                                                      </div>
                                                  </div>
                                                  <?php
                                                  if(!empty($fetch_transfer_itinerary['transfer_guide_id']))
                                                  {
                                                    ?>
                                                    <div>
                                                    <button type="button" class="btn btn-sm btn-primary view_guide seted_view_gide_transfer" id="view_<?php echo ($days_count+1) ?>_<?php echo $fetch_transfer_itinerary['transfer_guide_id']; ?>" value="<?php echo $fetch_transfer_itinerary['transfer_guide_id']; ?>" >View Guide</button>
                                                    <button type="button" class="btn btn-sm btn-primary view_driver" id="view_<?php echo ($days_count+1) ?>_<?php echo  $transfer_driver_id; ?>">View Car / Driver</button>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-primary select_transfer_guide_btn" id="select_transfer_guide_btn__<?php echo ($days_count+1); ?>" style="display:none">Select Guide</button>
                                                    <span class="selected_transfer_guide_label" id="selected_transfer_guide_label__<?php echo ($days_count+1); ?>" ><strong>Selected Guide :</strong></span>
                                                    <button type="button" class="btn btn-sm transfer_guide_remove"  id="transfer_guide_remove__<?php echo ($days_count+1); ?>">Remove</button>
                                                    <p class="selected_transfer_guide_name" id="selected_transfer_guide_name__<?php echo ($days_count+1); ?>"><?php
                                                    if(!empty($fetch_transfer_itinerary['transfer_guide_name']))
                                                      { echo $fetch_transfer_itinerary['transfer_guide_name']; }

                                                  ?>
                                                  <input type="hidden" class="selected_transfer_guide_id_transfer" id="selected_transfer_guide_id_transfer__<?php echo ($days_count+1); ?>"  value="<?php echo $fetch_transfer_itinerary['transfer_guide_id'] ?>" id="selelted_guide_already">
                                              </p>
                                              
                                              <?php
                                          }
                                          else
                                          {
                                            ?>
                                             <div>
                                            <button type="button" style="display:none" class="btn btn-sm btn-primary view_guide seted_view_gide_transfer" value="" id="view_<?php echo ($days_count+1) ?>">View Guide</button>
                                            <button type="button"  class="btn btn-sm btn-primary view_driver" id="view_<?php echo ($days_count+1) ?>_<?php echo  $transfer_driver_id; ?>">View Car / Driver</button>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary select_transfer_guide_btn" id="select_transfer_guide_btn__<?php echo ($days_count+1); ?>">Select Guide</button>
                                            
                                            <span class="selected_transfer_guide_label-" id="selected_transfer_guide_label__<?php echo ($days_count+1); ?>" style="display:none"><strong>Selected Guide :</strong></span>
                                            <button type="button"  class="btn btn-sm transfer_guide_remove"  id="transfer_guide_remove__<?php echo ($days_count+1); ?>" style="display:none">Remove</button>  
                                            <p class="selected_transfer_guide_name" id="selected_transfer_guide_name__<?php echo ($days_count+1); ?>"></p>
                                            <?php

                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="hotel-info-div">
                                   <div class="change">
                                     <span class="hr"></span>
                                     <span class="login-a add-modal-btn change_transfer change_remove_link" id="change_transfer__<?php echo ($days_count+1); ?>">Change</span>
                                     <span  class="login-a add-modal-btn remove_transfer change_remove_link" id="remove_transfer__<?php echo ($days_count+1); ?>">Remove</span>
                                 </div>

                             </div>

                         </div>



                     </div>

                 </div>
             </div>

             <?php
         }


         if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']))
         {
           $fetch_hotel=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_details'];
           $fetch_hotelrooms=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_room_details'];
           $fetch_hotelseasons=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_room_season_details'];
           $fetch_hotelseasonoccupancy=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_room_season_occupancy_details'];
           $fetch_hotel_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_itinerary_details'];
           ?>
           <div class="t-tab">
            <div class="row">
                <div class="col-md-12 hotels_select" id="hotels_select__<?php echo ($days_count+1) ?>" style="margin:0 0 0px">
                    <div class="hotel-list-div">

                        <div class="hotel-div">
                            <div class="hotel-img-div">
                              <?php 
                              $get_hotel_images=unserialize($fetch_hotel['hotel_images']);

                              if(!empty($get_hotel_images))
                              {
                                  ?>

                                  <img class="cstm-hotel-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/hotel_images/'.$get_hotel_images[0]; ?>"
                                  style="width:100%">
                                  <?php
                              }
                              else
                              {
                                ?>
                                <img class="cstm-hotel-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                <?php
                            }
                            ?>
                        </div>
                        <div class="hotel-details">
                            <div class="ens">
                                <span
                                class="hotel-s cstm-hotel-number-rating"><?php echo $fetch_hotel['hotel_rating']; ?>/5</span>
                                <div class="rating cstm-hotel-rating"
                                style="display:inline-block;">
                                <?php
                                for($stars=1;$stars<=5;$stars++)
                                {
                                    if($stars<=$fetch_hotel['hotel_rating'])
                                    {
                                        echo '<span
                                        class="fa fa-star checked"></span>';
                                    }
                                    else
                                    {
                                        echo '<span
                                        class="fa fa-star"></span>';
                                    }

                                }
                                ?>


                            </div>
                        </div>

                        <div class="heading-div">
                            <p class="hotel-name cstm-hotel-name">
                                <?php echo $fetch_hotel['hotel_name'];?></p>

                            </div>


                            <p class="info cstm-hotel-address"><?php echo $fetch_hotel['hotel_address'];?></p>
                            <div
                            class="heading-div c-h-div">
                            <div class="rating ">
                                <span
                                class="span-i">DATES</span>
                                <p class="title-2"><?php  echo date('D, d M Y',strtotime($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin']));
                                ?> - <?php echo date('D, d M Y',strtotime($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkout']));
                                ?></p>
                            </div>
                            <div>
                                                                                                <!-- <span
                                                                                                    class="span-i">INCLUDES</span>
                                                                                                <p class="title-2">
                                                                                                Breakfast</p> -->
                                                                                            </div>

                                                                                        </div>
                                                                                        <span class="span-i">ROOM TYPE</span>

                                                                                        <?php
                                                                                        $check_hotels_query=0;
                                                                                        if($fetch_hotel['hotel_approve_status']==1 && $fetch_hotel['hotel_status']==1 && $fetch_hotel['booking_validity_from']<=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'] && $fetch_hotel['booking_validity_to']>=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'])
                                                                                        {
                                                                                            $check_hotels_query=1;
                                                                                        }



                                                                                        $room_avail=0;

                                                                                        $hotel_room_id="";
                                                                                        $hotel_room_name="";
                                                                                        $hotel_occupancy_id="";
                                                                                        $hotel_occupancy_qty="";
                                                                                        $hotel_cost="";
                                                                                        $rooms_found=0;
                                                                                        $hotel_room_name="1-".$fetch_hotel_itinerary['room_name'];
                                                                                        $hotel_cost=$fetch_hotel_itinerary['hotel_cost'];

                                                                                        if(isset($fetch_hotel_itinerary['room_id']))
                                                                                        {
                                                                            // $check_hotel_room=App\HotelRooms::join('hotel_room_season_occupancy_price',' hotel_room_season_occupancy_price.hotel_room_id_fk','=','hotel_rooms.hotel_room_id')->where('hotel_rooms.hotel_id',$fetch_hotel['hotel_id'])->where('hotel_rooms.hotel_room_id',$itinerary_package_services[$days_count]['hotel']['room_id'])->where('hotel_room_season_occupancy_price.hotel_room_occupancy_id',$itinerary_package_services[$days_count]['hotel']['room_occupancy_id'])->first();
                                                                                            $check_hotel_room=0;

                                                                                            foreach($fetch_hotelrooms as $hotel_room)
                                                                                            {
                                                                                                if($hotel_room['hotel_room_id']==$fetch_hotel_itinerary['room_id'])
                                                                                                {
                                                                                                   $check_hotel_room++;
                                                                                               }
                                                                                           }

                                                                                           if($check_hotel_room!=0)
                                                                                           {
                                                                                // $check_hotel_occupancy=App\HotelRoomSeasonOccupancy::where('hotel_room_occupancy_id',$itinerary_package_services[$days_count]['hotel']['room_occupancy_id'])->where('hotel_room_id_fk',$itinerary_package_services[$days_count]['hotel']['room_id'])->first();

                                                                                            foreach($fetch_hotelseasonoccupancy as $check_hotel_occupancy)
                                                                                            {
                                                                                                if($check_hotel_occupancy['hotel_room_occupancy_id']==$fetch_hotel_itinerary['room_occupancy_id'] && $check_hotel_occupancy['hotel_room_id_fk']==$fetch_hotel_itinerary['room_id'])
                                                                                                {

                                                                                         // $get_hotel_seasons=App\HotelRoomSeasons::where('hotel_room_id_fk',$itinerary_package_services[$days_count]['hotel']['room_id'])->where('hotel_room_season_id',$check_hotel_occupancy->hotel_room_season_id_fk)->where('hotel_room_season_validity_from',"<=",$checkin_date)->where('hotel_room_season_validity_to',">=",$checkin_date)->first();

                                                                                                   $seasons_counter=0;
                                                                                                   foreach($fetch_hotelseasons as $get_hotel_seasons){

                                                                                                    if($get_hotel_seasons['hotel_room_id_fk']==$fetch_hotel_itinerary['room_id'] && $get_hotel_seasons['hotel_room_season_id']==$check_hotel_occupancy['hotel_room_season_id_fk'] && $get_hotel_seasons['hotel_room_season_validity_from']<=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'] && $get_hotel_seasons['hotel_room_season_validity_to']>=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'])
                                                                                                    {


                                                                                                       $hotel_room_id=$fetch_hotel_itinerary['room_id'];
                                                                                                       $hotel_room_name="1-".$fetch_hotel_itinerary['room_name']."-(".$check_hotel_occupancy['hotel_room_occupancy_qty']." Occupancy)";

                                                                                                       $hotel_occupancy_id=$fetch_hotel_itinerary['room_occupancy_id'];
                                                                                                       $hotel_occupancy_qty=$check_hotel_occupancy['hotel_room_occupancy_qty']; 
                                                                                                       $hotel_cost=$check_hotel_occupancy['hotel_room_occupancy_price'];

                                                                                                       $rooms_found++;
                                                                                                       $room_avail++;
                                                                                                       $seasons_counter++;


                                                                                                   }
                                                                                               }

                                                                                               if($seasons_counter==0)
                                                                                               {
                                                                                                   $rooms_found++;
                                                                                               }


                                                                                           }
                                                                                       }

                                                                                   }

                                                                               }

                                                                               if($rooms_found==0)
                                                                               {


                                                                                   $hotel_currency=$fetch_hotel['hotel_currency'];

                                                                                   $hotel_room_id="";
                                                                                   $hotel_room_name="";
                                                                                   $hotel_occupancy_id="";
                                                                                   $hotel_occupancy_qty="";
                                                                                   $hotel_cost="";
                                                                                   $room_min_price=0;
                                                                                   foreach($fetch_hotelrooms as $rooms_value)
                                                                                   {

                                                                                      if($hotel_currency==null)
                                                                                      {
                                                                                        $hotel_currency=$rooms_value['hotel_room_currency'];
                                                                                    }
                                                                                      // $get_hotel_seasons=App\HotelRoomSeasons::where('hotel_room_id_fk',$rooms_value->hotel_room_id)->where('hotel_room_season_validity_from',"<=",$checkin_date)->where('hotel_room_season_validity_to',">=",$checkin_date)->get();

                                                                                    foreach($fetch_hotelseasons as $hotel_seasons)
                                                                                    {
                                                                                        if($hotel_seasons['hotel_room_id_fk']==$rooms_value['hotel_room_id'] && $hotel_seasons['hotel_room_season_validity_from']<=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'] && $hotel_seasons['hotel_room_season_validity_to']>=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'])
                                                                                        {

                                                                                        // $get_occupancy=App\HotelRoomSeasonOccupancy::where('hotel_room_season_id_fk',$hotel_seasons->hotel_room_season_id)->get();

                                                                                            foreach($fetch_hotelseasonoccupancy as $occupancy)
                                                                                            {
                                                                                                if($occupancy['hotel_room_season_id_fk']==$hotel_seasons['hotel_room_season_id'])
                                                                                                {
                                                                                                  if($room_min_price==0)
                                                                                                  {
                                                                                                     $room_min_price=$occupancy['hotel_room_occupancy_price'];
                                                                                                     $hotel_room_name=ucwords($rooms_value['hotel_room_class'])." ".ucwords($rooms_value['hotel_room_type'])." Room";
                                                                                                     $hotel_room_id=$rooms_value['hotel_room_id'];
                                                                                                     $hotel_room_name="1-".$hotel_room_name."-(".$occupancy['hotel_room_occupancy_qty']." Occupancy)";
                                                                                                     $hotel_occupancy_id=$occupancy['hotel_room_occupancy_id'];
                                                                                                     $hotel_occupancy_qty=$occupancy['hotel_room_occupancy_qty'];
                                                                                                     $room_avail++;

                                                                                                 }
                                                                                                 else if($occupancy['hotel_room_occupancy_price']<$room_min_price)
                                                                                                 {
                                                                                                    $room_min_price=$occupancy['hotel_room_occupancy_price'];
                                                                                                    $hotel_room_name=ucwords($rooms_value['hotel_room_class'])." ".ucwords($rooms_value['hotel_room_type'])." Room";

                                                                                                    $hotel_room_name="1-".$hotel_room_name."-(".$occupancy['hotel_room_occupancy_qty']." Occupancy)";
                                                                                                    $hotel_room_id=$rooms_value['hotel_room_id'];
                                                                                                    $hotel_occupancy_id=$occupancy['hotel_room_occupancy_id'];
                                                                                                    $hotel_occupancy_qty=$occupancy['hotel_room_occupancy_qty'];
                                                                                                    $room_avail++;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }

                                                                            $new_currency="";
                                                                            if($room_min_price>0)
                                                                            {

                                                                                $price=$room_min_price;
                                                                                if($hotel_currency!="GEL")
                                                                                {
                                                                                  $new_currency= $hotel_currency;
                                                                                  $conversion_price=0;
                                                                                  $fetch_html = file_get_contents('https://www.exchange-rates.org/converter/'.$new_currency.'/GEL/1');
                                                                                  $scriptDocument = new \DOMDocument();
                                                                                libxml_use_internal_errors(TRUE); //disable libxml errors
                                                                                if(!empty($fetch_html)){
                                                                                   //load
                                                                                 $scriptDocument->loadHTML($fetch_html);
                                                                                 
                                                                                //init DOMXPath
                                                                                 $scriptDOMXPath = new \DOMXPath($scriptDocument);
                                                                                  //get all the h2's with an id
                                                                                 $scriptRow = $scriptDOMXPath->query('//span[@id="ctl00_M_lblToAmount"]');
                                                                                  //check
                                                                                 if($scriptRow->length > 0){
                                                                                    foreach($scriptRow as $row){
                                                                                     $conversion_price=round($row->nodeValue,2);
                                                                                 }
                                                                             }

                                                                         }
                                                                         $price=round($price*$conversion_price,2);

                                                                     }




                                                                     $total_cost=round($price);

                                                                     $hotel_cost= $total_cost;


                                                                 }
                                                             }

                                                             if($check_hotels_query==0)
                                                             {
                                                                $warning_count++;
                                                                echo "<p style='color:red' class='warning_text add-modal-btn hotel_warning__".($days_count+1)."' id='hotel_warning__".($days_count+1)."'>Please click change in order to select another hotel, as current one is not available for your dates</p>";
                                                            }
                                                            else if($room_avail==0)
                                                            {
                                                                $warning_count++;
                                                                echo "<p style='color:red'  class='warning_text add-modal-btn hotel_warning__".($days_count+1)."' id='hotel_warning__".($days_count+1)."'>Please click change in order to select another room/rooms, as current one is not available for your dates</p>";
                                                            }
                                                            ?>

                                                            <p class="title-2 cstm-hotel-room-type"><?php echo str_replace("-"," ",$hotel_room_name); ?></p>
                                                            <span class="login-a add-modal-btn change_hotel_room cstm-change-hotel change_remove_link" id="change_hotel__<?php echo $fetch_hotel['hotel_id'].'_'.($days_count+1); ?>">Change Room</span>
                                                            <input type="hidden" class="hotel_id<?php echo ($days_count+1) ?>" id="hotel_id__<?php echo ($days_count+1) ?>" name="hotel_id[<?php echo $days_count ?>]" value="<?php echo $fetch_hotel['hotel_id']  ?>">
                                                            <input type="hidden" class="hotel_name<?php echo ($days_count+1) ?>" id="hotel_name__<?php echo ($days_count+1) ?>" name="hotel_name[<?php echo $days_count ?>]" value="<?php echo $fetch_hotel['hotel_name']  ?>">
                                                            <input type="hidden" class="room_name<?php echo ($days_count+1) ?>" id="room_name__<?php echo ($days_count+1) ?>" name="room_name[<?php echo $days_count ?>]" value="<?php echo $hotel_room_name; ?>">
                                                            <input type="hidden" class="room_qty<?php echo ($days_count+1) ?>" id="room_qty__<?php echo ($days_count+1) ?>" name="room_qty[<?php echo $days_count ?>]" value="1">
                                                            <?php
                                                            if(trim($hotel_cost)!="" && $hotel_cost!=null)
                                                            {
                                                                $hotel_cost=($hotel_cost*$fetch_hotel_itinerary['hotel_no_of_days']); 
                                                            }
                                                            else
                                                            {
                                                             $hotel_cost=0; 
                                                         }

                                                         ?>
                                                         <input type="hidden" class="calc_cost hotel_cost<?php echo ($days_count+1) ?>"  id="hotel_cost__<?php echo ($days_count+1) ?>" name="hotel_cost[<?php echo $days_count ?>]" value="<?php echo round($hotel_cost); ?>">
                                                         <input type="hidden" class="hotel_no_of_days<?php echo ($days_count+1) ?>" id="hotel_no_of_days__<?php echo ($days_count+1) ?>" name="hotel_no_of_days[<?php echo $days_count ?>]" value="<?php echo $fetch_hotel_itinerary['hotel_no_of_days'] ?>">
                                                         <input type="hidden" class="hotel_checkin<?php echo ($days_count+1) ?>" id="hotel_checkin__<?php echo ($days_count+1) ?>" name="hotel_checkin[<?php echo $days_count ?>]" value="<?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin']; ?>">
                                                         <input type="hidden" class="hotel_checkout<?php echo ($days_count+1) ?>" id="hotel_checkout__<?php echo ($days_count+1) ?>" name="hotel_checkout[<?php echo $days_count ?>]" value="<?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkout']; ?>">

                                                         <?php
                                                                                // $total_whole_itinerary_cost+=($fetch_hotel_itinerary['hotel_cost']*$fetch_hotel_itinerary['hotel_no_of_days']);
                                                         $total_whole_itinerary_cost+=$hotel_cost;
                                                         ?>
                                                         <div id="hotel_room_detail__<?php echo ($days_count+1) ?>">
                                                           <input type="hidden" class="hotel_room_name<?php echo ($days_count+1) ?>" id="hotel_room_name__<?php echo ($days_count+1) ?>" name="hotel_room_name[<?php echo $days_count ?>][]" value="<?php echo $hotel_room_name; ?>">

                                                           <input type="hidden" class="hotel_room_qty hotel_room_qty<?php echo ($days_count+1) ?>" id="hotel_room_qty__<?php echo ($days_count+1) ?>__1" name="hotel_room_qty[<?php echo $days_count ?>][]" value="1">

                                                           <input type="hidden" class="hotel_room_id<?php echo ($days_count+1) ?>" id="hotel_room_id__<?php echo ($days_count+1) ?>" name="hotel_room_id[<?php echo $days_count ?>][]" value="<?php echo $hotel_room_id; ?>">

                                                           <input type="hidden" class=" hotel_occupancy_id hotel_occupancy_id<?php echo ($days_count+1) ?>" id="hotel_occupancy_id__<?php echo ($days_count+1) ?>" name="hotel_occupancy_id[<?php echo $days_count ?>][]" value="<?php echo $hotel_occupancy_id; ?>">

                                                           <input type="hidden" class="hotel_occupancy_qty hotel_occupancy_qty<?php echo ($days_count+1) ?>" id="hotel_occupancy_qty__<?php echo ($days_count+1) ?>__1" name="hotel_occupancy_qty[<?php echo $days_count ?>][]" value="<?php echo $hotel_occupancy_qty; ?>">

                                                           <input type="hidden" class="hotel_room_cost<?php echo ($days_count+1) ?>" id="hotel_room_cost__<?php echo ($days_count+1) ?>" name="hotel_room_cost[<?php echo $days_count ?>][]" value="<?php echo $hotel_cost; ?>">
                                                       </div>

                                                   </div>
                                               </div>
                                               <div class="hotel-info-div">
                                                   <div class="change">
                                                     <span class="hr"></span>
                                                     <?php
                                                     if($check_hotels_query==0)
                                                     {
                                                        ?>
                                                        <span  class="login-a add-modal-btn change_hotel change_remove_link" id="change_hotel__<?php echo ($days_count+1); ?>" style="outline: 5px solid red;padding:0px 4px;">Change</span>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <span  class="login-a add-modal-btn change_hotel change_remove_link" id="change_hotel__<?php echo ($days_count+1); ?>">Change</span>
                                                        <?php
                                                    }
                                                    ?>

                                                    <span class="login-a add-modal-btn remove_hotel change_remove_link" id="remove_hotel__<?php echo ($days_count+1); ?>">Remove</span>
                                                </div>

                                            </div>

                                        </div>



                                    </div>

                                </div>
                            </div>

                            <?php
                        }

                        if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_itinerary_details']['sightseeing_id']))
                        {
                            $fetch_sightseeing=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_details'];

                            $fetch_sightseeing_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_itinerary_details'];

                            $from_city=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['from_city'];
                            $between_city=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['between_city'];
                            $to_city=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['to_city'];

                            $sightseeing_vehicle_min=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_vehicle_min'];

                            $sightseeing_vehicle_max=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_vehicle_max'];



                            ?>

                            <div class="t-tab">
                              <div class="row">
                               <div class="col-md-12 sightseeing_select" id="sightseeing_select__<?php echo ($days_count+1) ?>" style="margin:0 0 0px">

                                  <div class="hotel-list-div">
                                    <div class="hotel-div">
                                        <div class="hotel-img-div">
                                           <?php 
                                           $get_sightseeing_images=unserialize($fetch_sightseeing['sightseeing_images']);

                                           if(!empty($get_sightseeing_images))
                                           {
                                              ?>

                                              <img class="cstm-sightseeing-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/sightseeing_images/'.$get_sightseeing_images[0] ?>"
                                              style="width:100%">
                                              <?php
                                          }
                                          else
                                          {
                                            ?>
                                            <img class="cstm-sightseeing-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="hotel-details">
                                       <div class="heading-div">
                                        <p class="title-1 cstm-sightseeing-name"><?php echo $fetch_sightseeing['sightseeing_tour_name']."( ".$fetch_sightseeing['sightseeing_distance_covered']." KMS)"; ?>
                                    </p>
                                </div>
                                <div
                                class="heading-div c-h-div">
                                <div class="rating ">
                                    <p class="title-2 cstm-sightseeing-address"><b><?php echo $from_city.$between_city.$to_city; ?></b></p>

                                    <span class="span-i">Date</span>
                                    <p class="p-i"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                                    <?php 
                                    $price=$fetch_sightseeing['sightseeing_adult_cost'];
                                    $price+=round($fetch_sightseeing['sightseeing_food_cost']);
                                    $price+=round($fetch_sightseeing['sightseeing_hotel_cost']);

                                    $total_sightseeing_cost=round($price+$fetch_sightseeing['sightseeing_additional_cost']);
                                    $total_adult_cost=round($price);
                                    ?>
                                    <input type="hidden" class="sightseeing_id sightseeing_id<?php echo ($days_count+1) ?>" id="sightseeing_id__<?php echo ($days_count+1) ?>" name="sightseeing_id[<?php echo $days_count ?>]" value="<?php echo $fetch_sightseeing['sightseeing_id']  ?>">
                                    <input type="hidden" class="sightseeing_name<?php echo ($days_count+1) ?>" name="sightseeing_name[<?php echo $days_count ?>]"  id="sightseeing_name__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing['sightseeing_tour_name'] ?>">

                                    <input type="hidden" class="sightseeing_tour_type<?php echo ($days_count+1) ?>" name="sightseeing_tour_type[<?php echo $days_count ?>]"  id="sightseeing_tour_type__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_tour_type'] ?>">
                                    <input type="hidden" class="sightseeing_vehicle_type sightseeing_vehicle_type<?php echo ($days_count+1) ?>" name="sightseeing_vehicle_type[<?php echo $days_count ?>]"  id="sightseeing_vehicle_type__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_vehicle_type']; ?>">

                                    <input type="hidden" class="sightseeing_vehicle_min sightseeing_vehicle_min<?php echo ($days_count+1) ?>" name="sightseeing_vehicle_min[<?php echo $days_count ?>]"  id="sightseeing_vehicle_min__<?php echo ($days_count+1) ?>" value="<?php echo $sightseeing_vehicle_min; ?>">

                                    <input type="hidden" class="sightseeing_vehicle_max sightseeing_vehicle_max<?php echo ($days_count+1) ?>" name="sightseeing_vehicle_max[<?php echo $days_count ?>]"  id="sightseeing_vehicle_max__<?php echo ($days_count+1) ?>" value="<?php echo $sightseeing_vehicle_max; ?>">


                                    <input type="hidden" class="sightseeing_guide_id sightseeing_guide_id<?php echo ($days_count+1) ?>" name="sightseeing_guide_id[<?php echo $days_count ?>]"  id="sightseeing_guide_id__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_guide_id']; ?>">
                                    <input type="hidden" class="sightseeing_guide_name sightseeing_guide_name<?php echo ($days_count+1) ?>" name="sightseeing_guide_name[<?php echo $days_count ?>]"  id="sightseeing_guide_name__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_guide_name']; ?>">

                                    <input type="hidden" class="sightseeing_guide_cost sightseeing_guide_cost<?php echo ($days_count+1) ?>" name="sightseeing_guide_cost[<?php echo $days_count ?>]"  id="sightseeing_guide_cost__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_guide_cost']; ?>">

                                    <input type="hidden" class="sightseeing_driver_id sightseeing_driver_id<?php echo ($days_count+1) ?>" name="sightseeing_driver_id[<?php echo $days_count ?>]"  id="sightseeing_driver_id__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_driver_id']; ?>">

                                    <input type="hidden" class="sightseeing_driver_name sightseeing_driver_name<?php echo ($days_count+1) ?>" name="sightseeing_driver_name[<?php echo $days_count ?>]"  id="sightseeing_driver_name__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_driver_name']; ?>">

                                    <input type="hidden" class="sightseeing_driver_cost sightseeing_driver_cost<?php echo ($days_count+1) ?>" name="sightseeing_driver_cost[<?php echo $days_count ?>]"  id="sightseeing_driver_cost__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing_itinerary['sightseeing_driver_cost']; ?>">

                                    <input type="hidden" class="sightseeing_adult_cost<?php echo ($days_count+1) ?>" name="sightseeing_adult_cost[<?php echo $days_count ?>]"  id="sightseeing_adult_cost__<?php echo ($days_count+1) ?>" value="<?php echo $total_adult_cost; ?>">
                                    <input type="hidden" class="sightseeing_additional_cost<?php echo ($days_count+1) ?>" name="sightseeing_additional_cost[<?php echo $days_count ?>]"  id="sightseeing_additional_cost__<?php echo ($days_count+1) ?>" value="<?php echo $fetch_sightseeing['sightseeing_additional_cost']; ?>">
                                    <input type="hidden" class="sightseeing_cities sightseeing_cities<?php echo ($days_count+1) ?>" name="sightseeing_cities[<?php echo $days_count ?>]"  id="sightseeing_cities__<?php echo ($days_count+1) ?>" value="<?php if($fetch_sightseeing['sightseeing_city_between']!="")
                                    echo $fetch_sightseeing['sightseeing_city_between'].','.$fetch_sightseeing['sightseeing_city_to'];
                                    else if($fetch_sightseeing['sightseeing_city_to']!="")
                                        echo $fetch_sightseeing['sightseeing_city_to'];
                                    else
                                        echo $fetch_sightseeing['sightseeing_city_from']; 
                                    ?>">
                                    <?php
                                    $total_sightseeing_cost+=$fetch_sightseeing_itinerary['sightseeing_guide_cost'];     
                                    $total_sightseeing_cost+=$fetch_sightseeing_itinerary['sightseeing_driver_cost'];
                                    ?>
                                    <input type="hidden" class="calc_cost sightseeing_cost<?php echo ($days_count+1) ?>" name="sightseeing_cost[<?php echo $days_count ?>]" id="sightseeing_cost__<?php echo ($days_count+1) ?>" value="<?php echo $total_sightseeing_cost; ?>">
                                    <?php
                                    $total_whole_itinerary_cost+=($total_sightseeing_cost);
                                    ?>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary view_guide seted_view_gide" id="view_<?php echo ($days_count+1) ?>_<?php echo $fetch_sightseeing_itinerary['sightseeing_guide_id']; ?>">View Guide</button>
                            <button type="button" class="btn btn-sm btn-primary view_driver" id="view_<?php echo ($days_count+1) ?>_<?php echo $fetch_sightseeing_itinerary['sightseeing_driver_id']; ?>">View Car / Driver</button>

                        </div>
                    </div>


                    <div class="hotel-info-div">
                        <div class="change">
                         <!--  <a href="#" class="remove">Remove</a> -->
                         <span class="hr"></span>
                         <span class="login-a add-modal-btn change_sightseeing change_remove_link <?php echo $first_sightseeing; ?>" id="change_sightseeing__<?php echo ($days_count+1); ?>">Change</span>
                     </div>
                     <div class="change">
                        <span class="hr"></span>
                        <span class="login-a add-modal-btn remove_sightseeing change_remove_link" id="remove_sightseeing__<?php echo ($days_count+1); ?>">Remove</span>

                    </div>
                    <div class="change">
                      <span class="hr"></span>
                      <a href="<?php echo site_url();?>/sightseeing-details/?id=<?php echo $fetch_sightseeing['sightseeing_id']; ?>&itinerary=true" target="_blank" class="login-a add-modal-btn shows_sightseeing" id="shows_sightseeing__<?php echo ($days_count+1); ?>">Details</a>
                  </div>
              </div>
          </div>
      </div>

  </div>
</div>
<div class="text-center"><button type="button" class="btn btn-md btn-primary add_sightseeing add_more add-modal-btn" id="add_sightseeing__<?php echo ($days_count+1); ?>" style="display:none">+ Add Daily Tour</button></div>
<?php

$first_sightseeing="";
}
else
{
   ?>
   <div class="t-tab" style="display: none">
      <div class="row">
       <div class="col-md-12 sightseeing_select" id="sightseeing_select__<?php echo ($days_count+1) ?>" style="margin:0 0 0px">

          <div class="hotel-list-div">
            <div class="hotel-div">
                <div class="hotel-img-div">
                   <img class="cstm-sightseeing-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">
               </div>
               <div class="hotel-details">
                   <div class="heading-div">
                    <p class="title-1 cstm-sightseeing-name"></p>
                </div>
                <div
                class="heading-div c-h-div">
                <div class="rating ">
                    <p class="title-2 cstm-sightseeing-address"></p>

                    <span class="span-i">Date</span>
                    <p class="p-i"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>

                    <input type="hidden" class="sightseeing_id<?php echo ($days_count+1) ?>" id="sightseeing_id__<?php echo ($days_count+1) ?>" name="sightseeing_id[<?php echo $days_count ?>]" value="">
                    <input type="hidden" class="sightseeing_name<?php echo ($days_count+1) ?>" name="sightseeing_name[<?php echo $days_count ?>]"  id="sightseeing_name__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_tour_type<?php echo ($days_count+1) ?>" name="sightseeing_tour_type[<?php echo $days_count ?>]"  id="sightseeing_tour_type__<?php echo ($days_count+1) ?>" value="">
                    <input type="hidden" class="sightseeing_vehicle_type<?php echo ($days_count+1) ?>" name="sightseeing_vehicle_type[<?php echo $days_count ?>]"  id="sightseeing_vehicle_type__<?php echo ($days_count+1) ?>" value="">
                    <input type="hidden" class="sightseeing_vehicle_min sightseeing_vehicle_min<?php echo ($days_count+1) ?>" name="sightseeing_vehicle_min[<?php echo $days_count ?>]"  id="sightseeing_vehicle_min__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_vehicle_max sightseeing_vehicle_max<?php echo ($days_count+1) ?>" name="sightseeing_vehicle_max[<?php echo $days_count ?>]"  id="sightseeing_vehicle_max__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_guide_id sightseeing_guide_id<?php echo ($days_count+1) ?>" name="sightseeing_guide_id[<?php echo $days_count ?>]"  id="sightseeing_guide_id__<?php echo ($days_count+1) ?>" value="">
                    <input type="hidden" class="sightseeing_guide_name sightseeing_guide_name<?php echo ($days_count+1) ?>" name="sightseeing_guide_name[<?php echo $days_count ?>]"  id="sightseeing_guide_name__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_guide_cost sightseeing_guide_cost<?php echo ($days_count+1) ?>" name="sightseeing_guide_cost[<?php echo $days_count ?>]"  id="sightseeing_guide_cost__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_driver_id sightseeing_driver_id<?php echo ($days_count+1) ?>" name="sightseeing_driver_id[<?php echo $days_count ?>]"  id="sightseeing_driver_id__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_driver_name sightseeing_driver_name<?php echo ($days_count+1) ?>" name="sightseeing_driver_name[<?php echo $days_count ?>]"  id="sightseeing_driver_name__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_driver_cost sightseeing_driver_cost<?php echo ($days_count+1) ?>" name="sightseeing_driver_cost[<?php echo $days_count ?>]"  id="sightseeing_driver_cost__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="sightseeing_adult_cost<?php echo ($days_count+1) ?>" name="sightseeing_adult_cost[<?php echo $days_count ?>]"  id="sightseeing_adult_cost__<?php echo ($days_count+1) ?>" value="">
                    <input type="hidden" class="sightseeing_additional_cost<?php echo ($days_count+1) ?>" name="sightseeing_additional_cost[<?php echo $days_count ?>]"  id="sightseeing_additional_cost__<?php echo ($days_count+1) ?>" value="">
                    <input type="hidden" class="sightseeing_cities<?php echo ($days_count+1) ?>" name="sightseeing_cities[<?php echo $days_count ?>]"  id="sightseeing_cities__<?php echo ($days_count+1) ?>" value="">

                    <input type="hidden" class="calc_cost sightseeing_cost<?php echo ($days_count+1) ?>" name="sightseeing_cost[<?php echo $days_count ?>]" id="sightseeing_cost__<?php echo ($days_count+1) ?>" value="">

                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary view_guide seted_view_gide" id="view_<?php echo ($days_count+1) ?>_1">View Guide</button>
            <button type="button" class="btn btn-sm btn-primary view_driver" id="view_<?php echo ($days_count+1) ?>_2">View Car / Driver</button>

        </div>
    </div>


    <div class="hotel-info-div">
        <div class="change">
         <!--  <a href="#" class="remove">Remove</a> -->
         <span class="hr"></span>
         <span class="login-a add-modal-btn change_sightseeing change_remove_link" id="change_sightseeing__<?php echo ($days_count+1); ?>">Change</span>
     </div>
     <div class="change">
        <span class="hr"></span>
        <span class="login-a add-modal-btn remove_sightseeing change_remove_link" id="remove_sightseeing__<?php echo ($days_count+1); ?>">Remove</span>

    </div>
    <div class="change">
      <span class="hr"></span>
      <a href="" target="_blank" class="login-a add-modal-btn shows_sightseeing" id="shows_sightseeing__<?php echo ($days_count+1); ?>">Details</a>
  </div>
</div>
</div>
</div>

</div>
</div>
<div class="text-center"><button type="button" class="btn btn-md btn-primary add_sightseeing add_more add-modal-btn" id="add_sightseeing__<?php echo ($days_count+1); ?>">+ Add Daily Tour</button></div>
<?php
}
?>


<?php
if(is_array($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity']))
{
 $activity_count=count($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity']); 
}
else
{
    $activity_count=0;
}

$activity_show_count=0;
for($activity_counter=0;$activity_counter < $activity_count;$activity_counter++)
{
    $fetch_activity=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity'][$activity_counter]['activity_details'];

    $fetch_activity_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity'][$activity_counter]['activity_itinerary_details'];


    if($activity_show_count==0)
    {
        echo ' <div class="t-tab">
        <label>Activities</label>';
    }

    ?>
    <div class="row">
        <div class="col-md-12 activity_select activity_select__<?php echo ($days_count+1) ?>" id="activity_select__<?php echo ($days_count+1) ?>__<?php echo ($activity_counter+1)?>">
            <div class="hotel-list-div">

                <div class="hotel-div">
                    <div class="hotel-img-div">

                     <?php 
                     $get_activity_images=unserialize($fetch_activity['activity_images']);

                     if(!empty($get_activity_images))
                     {
                      ?>

                      <img class="cstm-activity-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/activities_images/'.$get_activity_images[0] ?>"
                      style="width:100%">
                      <?php
                  }
                  else
                  {
                    ?>
                    <img class="cstm-activity-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                    <?php
                }
                ?>
            </div>
            <div class="hotel-details">
                <div class="heading-div">
                    <p class="hotel-name cstm-activity-name">
                        <?php echo $fetch_activity['activity_name']; ?>
                    </p>

                </div>
                <p class="info cstm-activity-address"> <?php echo $fetch_activity['activity_location']; ?></p>
                <div
                class="heading-div c-h-div">
                <div class="rating">
                    <span
                    class="span-i">DATES</span>
                    <p class="title-2"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                    <?php 
                    $total_activity_cost=round($fetch_activity['adult_price']);
                    $total_whole_itinerary_cost+=($total_activity_cost);
                    ?>
                    <input type="hidden" class="activity_id<?php echo ($days_count+1) ?>" id="activity_id__<?php echo ($days_count+1) ?>__<?php echo ($activity_counter+1) ?>" name="activity_id[<?php echo $days_count ?>][]" value="<?php echo $fetch_activity['activity_id'] ?>">
                    <input type="hidden" class="activity_name<?php echo ($days_count+1) ?>" name="activity_name[<?php echo $days_count ?>][]"  id="activity_name__<?php echo ($days_count+1) ?>__<?php echo ($activity_counter+1) ?>" value="<?php echo $fetch_activity['activity_name'] ?>">
                    <input type="hidden" class="calc_cost activity_cost<?php echo ($days_count+1) ?>" id="activity_cost__<?php echo ($days_count+1) ?>__<?php echo ($activity_counter+1); ?>"  
                    name="activity_cost[<?php echo $days_count ?>][]"
                    value="<?php echo $total_activity_cost ?>">

                </div>
            </div>
            <?php
            $activity_not_available=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity'][$activity_counter]['activity_not_available'];

            if($activity_not_available==1 || $activity_not_available=="1")
            {
                $warning_count++;
                echo "<p style='color:red' class='warning_text add-modal-btn activities_warning__".($days_count+1)."__".($activity_counter+1)."' id='activities_warning__".($days_count+1)."__".($activity_counter+1)."'>Please click change in order to select another activity, as current one is not available for your dates</p>";
            }
            ?>
            <div class="row activities_pax_div activities_pax_div___<?php echo ($days_count+1); ?>" id="activity_pax_div__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>" <?php if(!empty($activity_pax_count_array[$days_count][$activity_counter]['adult_price_details'])) { echo 'style="margin-top: 10px;margin-left: 0px;"'; } else {  echo 'style="margin-top: 10px;margin-left: 0px;display:none"'; } ?>>
               <div class="col-md-6">
                  <?php


                  if(!empty($activity_pax_count_array[$days_count][$activity_counter]['adult_price_details']))
                  {
                    $min_adult=$activity_pax_count_array[$days_count][$activity_counter]['adult_price_details'][0]['adult_min_pax'];
                    $max_adult=$activity_pax_count_array[$days_count][$activity_counter]['adult_price_details'][0]['adult_max_pax'];
                    foreach($activity_pax_count_array[$days_count][$activity_counter]['adult_price_details'] as $adult_price_i)
                    {
                        if($adult_price_i['adult_min_pax']<$min_adult)
                        {
                            $min_adult=$adult_price_i['adult_min_pax'];
                        }
                        if($adult_price_i['adult_max_pax']>$max_adult)
                        {
                            $max_adult=$adult_price_i['adult_max_pax'];
                        }

                    }

                }
                else
                {
                   $min_adult=0;
                   $max_adult=0; 
               }

               ?>
               <select name="activities_select_adults[<?php echo $days_count; ?>][]" id="activities_select_adults__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>" class="form-control select_activities_adults"  <?php if(!empty($activity_pax_count_array[$days_count][$activity_counter]['adult_price_details'])) { echo 'required="required"';  } ?> style="height: 40px;padding: 10px 35px !important;">
                <option value="">Adults</option>
                <option value="0">0</option>
                <?php
                for($i=$min_adult;$i<=$max_adult;$i++)
                {
                   if($i==1)
                   {
                      echo '<option value="'.$i.'" selected>'.$i.'</option>';
                      continue;
                  }
                  echo '<option value="'.$i.'">'.$i.'</option>';
              }

              ?>
          </select>
      </div>
      <div class="col-md-6">
       <?php

       if(!empty($activity_pax_count_array[$days_count][$activity_counter]['child_price_details']))
       {
        $min_child=$activity_pax_count_array[$days_count][$activity_counter]['child_price_details'][0]['child_min_pax'];
        $max_child=$activity_pax_count_array[$days_count][$activity_counter]['child_price_details'][0]['child_max_pax'];
        foreach($activity_pax_count_array[$days_count][$activity_counter]['child_price_details'] as $child_price_i)
        {  
            if($child_price_i['child_min_pax']<$min_child)
            {
                $min_child=$child_price_i['child_min_pax'];
            }
            if($child_price_i['child_max_pax']>$max_child)
            {
                $max_child=$child_price_i['child_max_pax'];
            }

        }
    }
    else
    {
       $min_child=0;
       $max_child=0;

   }
   ?>
   <select name="activities_select_child[<?php echo $days_count; ?>][]" id="activities_select_child__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>" class="form-control select_activities_child" <?php if(!empty($activity_pax_count_array[$days_count][$activity_counter]['child_price_details'])) echo 'required="required"'; ?> style="height: 40px;padding: 10px 35px !important;">

    <option value="">Child</option>
    <?php
    if($min_child>0)
    {
      echo '<option value="0" selected>0</option>';
  }


  for($i=$min_child;$i<=$max_child;$i++)
  {
    if($i==0)
    {
      echo '<option value="'.$i.'" selected>'.$i.'</option>'; 
      continue;
  }
  echo '<option value="'.$i.'">'.$i.'</option>';  
}

?>
</select>
</div>
<div class="col-md-12">
    <br>    
    <div class="row activities_child_age_div" id="activities_child_age_div__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>">

    </div>
</div>


</div>



</div>
</div>
<div class="hotel-info-div">

    <span class="login-a add-modal-btn change_activity change_remove_link" id="change_activity__<?php echo ($days_count+1); ?>_<?php echo ($activity_counter+1); ?>">Change</span>
    <span class="login-a add-modal-btn remove_activity change_remove_link" id="remove_activity__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>">Remove</span>
    <a href="<?php echo site_url();?>/activity-details/?id=<?php echo $fetch_activity['activity_id']; ?>&itinerary=true" target="_blank" class="login-a add-modal-btn show_activity" id="show_activity__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>">Details</a>


</div>

</div>



</div>

</div>
<br>
<?php
$activity_show_count++;
if($activity_show_count==$activity_count)
{
    echo '</div>';

}

}
if($activity_show_count==0)
{
    ?>
    <div class="t-tab" style="display:none">
      <label>Activities</label>
      <div class="row">
        <div class="col-md-12 activity_select__<?php echo ($days_count+1) ?>" id="activity_select__<?php echo ($days_count+1) ?>__1">
            <div class="hotel-list-div">

                <div class="hotel-div">
                    <div class="hotel-img-div">
                        <img class="cstm-activity-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">
                    </div>
                    <div class="hotel-details">
                        <div class="heading-div">
                            <p class="hotel-name cstm-activity-name"></p>
                        </div>
                        <p class="info cstm-activity-address"></p>
                        <div
                        class="heading-div c-h-div">
                        <div class="rating">
                            <span
                            class="span-i">DATES</span>
                            <p class="title-2"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                            <input type="hidden" class="activity_id<?php echo ($days_count+1) ?>" id="activity_id__<?php echo ($days_count+1) ?>__1" name="activity_id[<?php echo $days_count ?>][]" value="">
                            <input type="hidden" class="activity_name<?php echo ($days_count+1) ?>" name="activity_name[<?php echo $days_count ?>][]"  id="activity_name__<?php echo ($days_count+1) ?>__1" value="">
                            <input type="hidden" class="calc_cost activity_cost<?php echo ($days_count+1) ?>" id="activity_cost__<?php echo ($days_count+1) ?>__1"  
                            name="activity_cost[<?php echo $days_count ?>][]"
                            value="0">

                        </div>
                    </div>

                    <div class="row activities_pax_div activities_pax_div___<?php echo ($days_count+1); ?>" id="activity_pax_div__<?php echo ($days_count+1); ?>__1" style="margin-top: 10px;margin-left: 0px;display:none">
                       <div class="col-md-6 addactivitiesbox">
                        <?php
                        $min_adult=0;
                        $max_adult=0; 
                        ?>
                        <label>Adults</label>
                        <select name="activities_select_adults[<?php echo $days_count; ?>][]" id="activities_select_adults__<?php echo ($days_count+1); ?>__1" class="form-control select_activities_adults" style="height: 40px;padding: 10px 35px !important;">
                            <option value="">Adults</option>
                            <option value="0">0</option>
                            <?php
                            for($i=$min_adult;$i<=$max_adult;$i++)
                            {
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }

                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 addactivitiesbox">
                       <?php
                       $min_child=0;
                       $max_child=0;
                       ?>
                       <label>Child</label>
                       <select name="activities_select_child[<?php echo $days_count; ?>][]" id="activities_select_child__<?php echo ($days_count+1); ?>__1" class="form-control select_activities_child" style="height: 40px;padding: 10px 35px !important;">

                        <option value="">Child</option>
                        <?php
                        if($min_child>0)
                        {
                          echo '<option value="0">0</option>';   
                      } 
                      for($i=$min_child;$i<=$max_child;$i++)
                      {
                         echo '<option value="'.$i.'">'.$i.'</option>';  
                     }

                     ?>
                 </select>
             </div>
             <div class="col-md-12 addactivitiesbox2">
                <br>    
                <div class="row activities_child_age_div" id="activities_child_age_div__<?php echo ($days_count+1); ?>__1">

                </div>
            </div>


        </div>



    </div>
</div>
<div class="hotel-info-div">

    <span class="login-a add-modal-btn change_activity change_remove_link" id="change_activity__<?php echo ($days_count+1); ?>_1">Change</span>
    <span class="login-a add-modal-btn remove_activity change_remove_link" id="remove_activity__<?php echo ($days_count+1); ?>__1">Remove</span>
    <a href="" target="_blank" class="login-a add-modal-btn show_activity" id="show_activity__<?php echo ($days_count+1); ?>__1">Details</a>
</div>

</div>



</div>

</div>
</div>


<?php
}
?>
<div class="text-center"><button type="button" class="btn btn-md btn-primary add_activity add-modal-btn" id="add_activity__<?php echo ($days_count+1); ?>">+ Add Activity</button></div>

<div class="t-tab" style="display:none">
    <label>Restaurants</label>
    <div class="row">
        <div class="col-md-12 restaurant_select__<?php echo ($days_count+1); ?>" id="restaurant_select__<?php echo ($days_count+1); ?>__1" style="margin:0 0 25px !important;display:none">
            <div class="hotel-list-div">

                <div class="hotel-div">
                    <div class="hotel-img-div">
                      <img class="cstm-restaurant-image" src=""
                      style="width:100%">

                  </div>
                  <div class="hotel-details">
                    <div class="heading-div">
                        <p class="hotel-name cstm-restaurant-name"></p>
                    </div>


                    <p class="info"></p>
                    <p class="address cstm-restaurant-address" style="    margin-bottom: 5px !important;"><i class="fa fa-map-marker"></i></p>
                    <div class="heading-div c-h-div">
                        <div class="rating">
                            <span class="span-i">SELECTED FOOD / DRINK :</span>
                            <p class="title-2 cstm-restaurant-food-type"></p>
                            <div class="row">
                             <div class="col-md-7">
                                <span class="span-i">DATE</span>
                                <p class="title-2"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                            </div>
                            <div class="col-md-5">
                                <span class="span-i">FOOD / DRINK </span>
                                <p class="title-2 cstm-restaurant-food-purpose"></p>
                            </div>
                            <div class="col-md-4">
                                <span class="span-i">Timings</span>
                                <p class="title-2 cstm-restaurant-food-time"></p>
                            </div>
                            <div class="col-md-4">
                                <span class="span-i">Total Price</span>
                                <p class="title-2 cstm-restaurant-total-price"></p>
                            </div>
                            <div class="col-md-4">
                                <span class="span-i">Pax Count</span>
                                <p class="title-2 cstm-restaurant-pax-count"></p>
                            </div>
                        </div>
                        <input type="hidden" class="restaurant_id" id="restaurant_id__<?php echo ($days_count+1); ?>__1" name="restaurant_id[<?php echo $days_count; ?>][]" value="">
                        <input type="hidden" class="restaurant_name" name="restaurant_name[<?php echo $days_count; ?>][]"  id="restaurant_name__<?php echo ($days_count+1); ?>__1" value="">
                        <input type="hidden" class="calc_cost restaurant_cost" id="restaurant_cost__<?php echo ($days_count+1); ?>__1"  
                        name="restaurant_cost[<?php echo $days_count; ?>][]"
                        value="0">
                        <input type="hidden" class="restaurant_food_for" name="restaurant_food_for[<?php echo $days_count; ?>][]"  id="restaurant_food_for__<?php echo ($days_count+1); ?>__1" value="">
                        <input type="hidden" class="restaurant_food_time" name="restaurant_food_time[<?php echo $days_count; ?>][]"  id="restaurant_food_time__<?php echo ($days_count+1); ?>__1" value="">
                        <input type="hidden" class="restaurant_pax_count" name="restaurant_pax_count[<?php echo $days_count; ?>][]"  id="restaurant_pax_count__<?php echo ($days_count+1); ?>__1" value="">

                    </div>
                </div>
            </div>
            <div class="restaurant_food_detail" id="restaurant_food_detail__<?php echo ($days_count+1); ?>__1"></div>
        </div>
        <div class="hotel-info-div">

           <a href="#" class="login-a add-modal-btn change_restaurant" id="change_restaurant__<?php echo ($days_count+1); ?>_1">Change</a>

           <a href="javascript:void(0)" class="login-a add-modal-btn remove_restaurant" id="remove_restaurant__<?php echo ($days_count+1); ?>__1">Remove</a>

           <a href="" class="login-a add-modal-btn show_restaurant" id="show_restaurant__<?php echo ($days_count+1); ?>__1" target="_blanks">Details</a>

       </div>

   </div>


</div>
</div>
</div>

<div class="text-center"><button type="button" class="btn btn-md btn-primary add_restaurant add-modal-btn" id="add_restaurant__<?php echo ($days_count+1); ?>">+ Add Restaurant</button></div>
<!-- </div> -->


</div>
</div>
</div>

<?php
}
?>
</div>

</div>
</div>

<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 25px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>
</div>
</div>
</div>

<div class="gdlr-core-pbf-wrapper click-hotels navtab" style="padding: 20px 0px 20px 0px;"
data-skin="Blue Icon" id="hotels">
<div class="gdlr-core-pbf-background-wrap"></div>
<div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="gdlr-core-pbf-element">
            <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr"
            style="padding-bottom:35px;">
            <div class="gdlr-core-title-item-title-wrap">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size: 24px ;font-weight: 600 ;letter-spacing: 0px ;text-transform:none;">
                Hotels<span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6>
        </div>
    </div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-toggle-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-toggle-box-style-background-title gdlr-core-left-align"
    style="padding-bottom:15px;">
    <div class="gdlr-core-toggle-box-item-tab clearfix">
        <div class="gdlr-core-toggle-box-item-icon gdlr-core-js gdlr-core-skin-icon ">
        </div>
        <div class="tourmaster-tour-item-holder gdlr-core-js-2 clearfix" data-layout="fitrows">
            <?php
            for($days_count=0;$days_count<count($result_data['itinerary']['itinerary_details_day_wise'])-1;$days_count++)
            {
                if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']))
                {
                    ?>
                    <div class="gdlr-core-toggle-box-item-content-wrapper">
                                                   <!--  <h4
                                                        class="gdlr-core-toggle-box-item-title gdlr-core-js  gdlr-core-skin-e-background gdlr-core-skin-e-content">
                                                        <span class="gdlr-core-head">Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></span><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_title']?></h4>
                                                        <div class="gdlr-core-toggle-box-item-content"> -->
                                                          <button type="button" class="collapsible">Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']; ?> - <?php echo ($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_no_of_days']+$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']); ?> </button>
                                                          <div class="content" id="days_count<?php echo ($days_count+1); ?>}">
                                                            <p class="des"><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_desc']?></p>
                                                            <div class="day-count">

                                                                <?php

                                                                $fetch_hotel=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_details'];
                                                                $fetch_hotel_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_itinerary_details'];
                                                                ?>
                                                                <div class="t-tab">
                                                                    <div class="row">
                                                                        <div class="col-md-12 hotels_indiv_select" id="hotels_indiv_select__<?php echo ($days_count+1) ?>" style="margin:0 0 25px">
                                                                            <div class="hotel-list-div">

                                                                                <div class="hotel-div">
                                                                                    <div class="hotel-img-div">
                                                                                      <?php 
                                                                                      $get_hotel_images=unserialize($fetch_hotel['hotel_images']);

                                                                                      if(!empty($get_hotel_images))
                                                                                      {
                                                                                          ?>

                                                                                          <img class="cstm-hotel-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/hotel_images/'.$get_hotel_images[0]; ?>"
                                                                                          style="width:100%">
                                                                                          <?php
                                                                                      }
                                                                                      else
                                                                                      {
                                                                                        ?>
                                                                                        <img class="cstm-hotel-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                                <div class="hotel-details">
                                                                                    <div class="ens">
                                                                                        <span
                                                                                        class="hotel-s cstm-hotel-indiv-number-rating"><?php echo $fetch_hotel['hotel_rating']; ?>/5</span>
                                                                                        <div class="rating cstm-hotel-indiv-rating"
                                                                                        style="display:inline-block;">
                                                                                        <?php
                                                                                        for($stars=1;$stars<=5;$stars++)
                                                                                        {
                                                                                            if($stars<=$fetch_hotel['hotel_rating'])
                                                                                            {
                                                                                                echo '<span
                                                                                                class="fa fa-star checked"></span>';
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                echo '<span
                                                                                                class="fa fa-star"></span>';
                                                                                            }

                                                                                        }
                                                                                        ?>


                                                                                    </div>
                                                                                </div>

                                                                                <div class="heading-div">
                                                                                    <p class="hotel-name cstm-hotel-indiv-name">
                                                                                        <?php echo $fetch_hotel['hotel_name'];?></p>

                                                                                    </div>


                                                                                    <p class="info cstm-hotel-indiv-address"><?php echo $fetch_hotel['hotel_address'];?></p>
                                                                                    <div
                                                                                    class="heading-div c-h-div">
                                                                                    <div class="rating">
                                                                                        <span
                                                                                        class="span-i">DATES</span>
                                                                                        <p class="title-2"><?php echo date('D, d M Y',strtotime($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin']));
                                                                                        ?> - <?php echo date('D, d M Y',strtotime($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkout']));
                                                                                        ?></p>
                                                                                    </div>
                                                                                    <div>
                                                                                                <!-- <span
                                                                                                    class="span-i">INCLUDES</span>
                                                                                                <p class="title-2">
                                                                                                Breakfast</p> -->
                                                                                            </div>

                                                                                        </div>
                                                                                        <span class="span-i">ROOM
                                                                                        TYPE</span>
                                                                                        <?php
                                                                                        $check_hotels_query=0;
                                                                                        if($fetch_hotel['hotel_approve_status']==1 && $fetch_hotel['hotel_status']==1 && $fetch_hotel['booking_validity_from']<=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'] && $fetch_hotel['booking_validity_to']>=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'])
                                                                                        {
                                                                                            $check_hotels_query=1;
                                                                                        }



                                                                                        $room_avail=0;

                                                                                        $hotel_room_id="";
                                                                                        $hotel_room_name="";
                                                                                        $hotel_occupancy_id="";
                                                                                        $hotel_occupancy_qty="";
                                                                                        $hotel_cost="";
                                                                                        $rooms_found=0;
                                                                                        $hotel_room_name="1-".$fetch_hotel_itinerary['room_name'];
                                                                                        $hotel_cost=$fetch_hotel_itinerary['hotel_cost'];

                                                                                        if(isset($fetch_hotel_itinerary['room_id']))
                                                                                        {
                                                                            // $check_hotel_room=App\HotelRooms::join('hotel_room_season_occupancy_price',' hotel_room_season_occupancy_price.hotel_room_id_fk','=','hotel_rooms.hotel_room_id')->where('hotel_rooms.hotel_id',$fetch_hotel['hotel_id'])->where('hotel_rooms.hotel_room_id',$itinerary_package_services[$days_count]['hotel']['room_id'])->where('hotel_room_season_occupancy_price.hotel_room_occupancy_id',$itinerary_package_services[$days_count]['hotel']['room_occupancy_id'])->first();
                                                                                            $check_hotel_room=0;

                                                                                            foreach($fetch_hotelrooms as $hotel_room)
                                                                                            {
                                                                                                if($hotel_room['hotel_room_id']==$fetch_hotel_itinerary['room_id'])
                                                                                                {
                                                                                                   $check_hotel_room++;
                                                                                               }
                                                                                           }

                                                                                           if($check_hotel_room!=0)
                                                                                           {
                                                                                // $check_hotel_occupancy=App\HotelRoomSeasonOccupancy::where('hotel_room_occupancy_id',$itinerary_package_services[$days_count]['hotel']['room_occupancy_id'])->where('hotel_room_id_fk',$itinerary_package_services[$days_count]['hotel']['room_id'])->first();

                                                                                            foreach($fetch_hotelseasonoccupancy as $check_hotel_occupancy)
                                                                                            {
                                                                                                if($check_hotel_occupancy['hotel_room_occupancy_id']==$fetch_hotel_itinerary['room_occupancy_id'] && $check_hotel_occupancy['hotel_room_id_fk']==$fetch_hotel_itinerary['room_id'])
                                                                                                {

                                                                                         // $get_hotel_seasons=App\HotelRoomSeasons::where('hotel_room_id_fk',$itinerary_package_services[$days_count]['hotel']['room_id'])->where('hotel_room_season_id',$check_hotel_occupancy->hotel_room_season_id_fk)->where('hotel_room_season_validity_from',"<=",$checkin_date)->where('hotel_room_season_validity_to',">=",$checkin_date)->first();

                                                                                                   $seasons_counter=0;
                                                                                                   foreach($fetch_hotelseasons as $get_hotel_seasons){

                                                                                                    if($get_hotel_seasons['hotel_room_id_fk']==$fetch_hotel_itinerary['room_id'] && $get_hotel_seasons['hotel_room_season_id']==$check_hotel_occupancy['hotel_room_season_id_fk'] && $get_hotel_seasons['hotel_room_season_validity_from']<=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'] && $get_hotel_seasons['hotel_room_season_validity_to']>=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'])
                                                                                                    {


                                                                                                       $hotel_room_id=$fetch_hotel_itinerary['room_id'];
                                                                                                       $hotel_room_name="1-".$fetch_hotel_itinerary['room_name']."-(".$check_hotel_occupancy['hotel_room_occupancy_qty']." Occupancy)";

                                                                                                       $hotel_occupancy_id=$fetch_hotel_itinerary['room_occupancy_id'];
                                                                                                       $hotel_occupancy_qty=$check_hotel_occupancy['hotel_room_occupancy_qty']; 
                                                                                                       $hotel_cost=$check_hotel_occupancy['hotel_room_occupancy_price'];

                                                                                                       $rooms_found++;
                                                                                                       $room_avail++;
                                                                                                       $seasons_counter++;


                                                                                                   }
                                                                                               }

                                                                                               if($seasons_counter==0)
                                                                                               {
                                                                                                   $rooms_found++;
                                                                                               }


                                                                                           }
                                                                                       }

                                                                                   }

                                                                               }

                                                                               if($rooms_found==0)
                                                                               {


                                                                                   $hotel_currency=$fetch_hotel['hotel_currency'];

                                                                                   $hotel_room_id="";
                                                                                   $hotel_room_name="";
                                                                                   $hotel_occupancy_id="";
                                                                                   $hotel_occupancy_qty="";
                                                                                   $hotel_cost="";
                                                                                   $room_min_price=0;
                                                                                   foreach($fetch_hotelrooms as $rooms_value)
                                                                                   {

                                                                                      if($hotel_currency==null)
                                                                                      {
                                                                                        $hotel_currency=$rooms_value['hotel_room_currency'];
                                                                                    }
                                                                                      // $get_hotel_seasons=App\HotelRoomSeasons::where('hotel_room_id_fk',$rooms_value->hotel_room_id)->where('hotel_room_season_validity_from',"<=",$checkin_date)->where('hotel_room_season_validity_to',">=",$checkin_date)->get();

                                                                                    foreach($fetch_hotelseasons as $hotel_seasons)
                                                                                    {
                                                                                        if($hotel_seasons['hotel_room_id_fk']==$rooms_value['hotel_room_id'] && $hotel_seasons['hotel_room_season_validity_from']<=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'] && $hotel_seasons['hotel_room_season_validity_to']>=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['hotel']['hotel_checkin'])
                                                                                        {

                                                                                        // $get_occupancy=App\HotelRoomSeasonOccupancy::where('hotel_room_season_id_fk',$hotel_seasons->hotel_room_season_id)->get();

                                                                                            foreach($fetch_hotelseasonoccupancy as $occupancy)
                                                                                            {
                                                                                                if($occupancy['hotel_room_season_id_fk']==$hotel_seasons['hotel_room_season_id'])
                                                                                                {
                                                                                                  if($room_min_price==0)
                                                                                                  {
                                                                                                     $room_min_price=$occupancy['hotel_room_occupancy_price'];
                                                                                                     $hotel_room_name=ucwords($rooms_value['hotel_room_class'])." ".ucwords($rooms_value['hotel_room_type'])." Room";
                                                                                                     $hotel_room_id=$rooms_value['hotel_room_id'];
                                                                                                     $hotel_room_name="1-".$hotel_room_name."-(".$occupancy['hotel_room_occupancy_qty']." Occupancy)";
                                                                                                     $hotel_occupancy_id=$occupancy['hotel_room_occupancy_id'];
                                                                                                     $hotel_occupancy_qty=$occupancy['hotel_room_occupancy_qty'];
                                                                                                     $room_avail++;

                                                                                                 }
                                                                                                 else if($occupancy['hotel_room_occupancy_price']<$room_min_price)
                                                                                                 {
                                                                                                    $room_min_price=$occupancy['hotel_room_occupancy_price'];
                                                                                                    $hotel_room_name=ucwords($rooms_value['hotel_room_class'])." ".ucwords($rooms_value['hotel_room_type'])." Room";

                                                                                                    $hotel_room_name="1-".$hotel_room_name."-(".$occupancy['hotel_room_occupancy_qty']." Occupancy)";
                                                                                                    $hotel_room_id=$rooms_value['hotel_room_id'];
                                                                                                    $hotel_occupancy_id=$occupancy['hotel_room_occupancy_id'];
                                                                                                    $hotel_occupancy_qty=$occupancy['hotel_room_occupancy_qty'];
                                                                                                    $room_avail++;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }

                                                                            $new_currency="";
                                                                            if($room_min_price>0)
                                                                            {

                                                                                $price=$room_min_price;
                                                                                if($hotel_currency!="GEL")
                                                                                {
                                                                                  $new_currency= $hotel_currency;
                                                                                  $conversion_price=0;
                                                                                  $fetch_html = file_get_contents('https://www.exchange-rates.org/converter/'.$new_currency.'/GEL/1');
                                                                                  $scriptDocument = new \DOMDocument();
                                                                                libxml_use_internal_errors(TRUE); //disable libxml errors
                                                                                if(!empty($fetch_html)){
                                                                                   //load
                                                                                 $scriptDocument->loadHTML($fetch_html);
                                                                                 
                                                                                //init DOMXPath
                                                                                 $scriptDOMXPath = new \DOMXPath($scriptDocument);
                                                                                  //get all the h2's with an id
                                                                                 $scriptRow = $scriptDOMXPath->query('//span[@id="ctl00_M_lblToAmount"]');
                                                                                  //check
                                                                                 if($scriptRow->length > 0){
                                                                                    foreach($scriptRow as $row){
                                                                                     $conversion_price=round($row->nodeValue,2);
                                                                                 }
                                                                             }

                                                                         }
                                                                         $price=round($price*$conversion_price,2);

                                                                     }




                                                                     $total_cost=round($price);

                                                                     $hotel_cost= $total_cost;


                                                                 }
                                                             }



                                                             if($check_hotels_query==0)
                                                             {

                                                                echo "<p style='color:red' class='warning_text add-modal-btn hotel_warning__".($days_count+1)."' id='hotel_warning__".($days_count+1)."'>Please click change in order to select another hotel, as current one is not available for your dates</p>";
                                                            }
                                                            else if($room_avail==0)
                                                            {

                                                                echo "<p style='color:red'  class='warning_text add-modal-btn hotel_warning__".($days_count+1)."' id='hotel_warning__".($days_count+1)."'>Please click change in order to select another room/rooms, as current one is not available for your dates</p>";
                                                            }
                                                            ?>

                                                            <p class="title-2 cstm-hotel-indiv-room-type"><?php echo str_replace("-"," ",$hotel_room_name); ?></p>
                                                            <span  class="login-a add-modal-btn change_hotel_room cstm-change-hotel change_remove_link" id="change_hotel__<?php echo $fetch_hotel['hotel_id'].'_'.($days_count+1); ?>">Change Room</span>
                                                        </div>
                                                    </div>
                                                    <div class="hotel-info-div">
                                                       <div class="change">
                                                         <span class="hr"></span>
                                                         <span  class="login-a add-modal-btn change_hotel change_remove_link" id="change_hotel__<?php echo ($days_count+1); ?>">Change</span>
                                                         <span class="login-a add-modal-btn remove_hotel change_remove_link" id="remove_hotel__<?php echo ($days_count+1); ?>">Remove</span>
                                                     </div>

                                                 </div>

                                             </div>



                                         </div>

                                     </div>
                                 </div>




                             </div>
                         </div>
                     </div>

                     <?php

                 }
             }
             ?>
         </div>
     </div>
 </div>
</div>

<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 25px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>
</div>
</div>
</div>

<div class="gdlr-core-pbf-wrapper click-sightseeing navtab" style="padding: 20px 0px 20px 0px;"
data-skin="Blue Icon" id="sightseeing">
<div class="gdlr-core-pbf-background-wrap"></div>
<div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="gdlr-core-pbf-element">
            <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr"
            style="padding-bottom:35px;">
            <div class="gdlr-core-title-item-title-wrap">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size: 24px ;font-weight: 600 ;letter-spacing: 0px ;text-transform:none;">
                Daily Tour<span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6>
        </div>
    </div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-toggle-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-toggle-box-style-background-title gdlr-core-left-align"
    style="padding-bottom:15px;">
    <div class="gdlr-core-toggle-box-item-tab clearfix">
        <div class="gdlr-core-toggle-box-item-icon gdlr-core-js gdlr-core-skin-icon ">
        </div>
        <div class="tourmaster-tour-item-holder gdlr-core-js-2 clearfix" data-layout="fitrows">
            <?php
            for($days_count=0;$days_count<count($result_data['itinerary']['itinerary_details_day_wise'])-1;$days_count++)
            {
               if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_itinerary_details']['sightseeing_id']))
               {
                   $fetch_sightseeing=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_details'];

                   $fetch_sightseeing_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['sightseeing_itinerary_details'];

                   $from_city=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['from_city'];
                   $between_city=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['between_city'];
                   $to_city=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['sightseeing']['to_city'];



                   ?>
                   <div class="gdlr-core-toggle-box-item-content-wrapper">
                                                   <!--  <h4
                                                        class="gdlr-core-toggle-box-item-title gdlr-core-js  gdlr-core-skin-e-background gdlr-core-skin-e-content">
                                                        <span class="gdlr-core-head">Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></span><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_title']?></h4>
                                                        <div class="gdlr-core-toggle-box-item-content"> -->
                                                           <button type="button" class="collapsible" >Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></button>
                                                           <div class="content ">
                                                            <p class="des"><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_desc']?></p>

                                                            <div class="day-count">

                                                             <div class="t-tab">
                                                              <div class="row">
                                                               <div class="col-md-12 sightseeing_indiv_select" id="sightseeing_indiv_select__<?php echo ($days_count+1) ?>" style="margin:0 0 0px">

                                                                  <div class="hotel-list-div">
                                                                    <div class="hotel-div">
                                                                        <div class="hotel-img-div">
                                                                           <?php 
                                                                           $get_sightseeing_images=unserialize($fetch_sightseeing['sightseeing_images']);

                                                                           if(!empty($get_sightseeing_images))
                                                                           {
                                                                              ?>

                                                                              <img class="cstm-sightseeing-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/sightseeing_images/'.$get_sightseeing_images[0] ?>"
                                                                              style="width:100%">
                                                                              <?php
                                                                          }
                                                                          else
                                                                          {
                                                                            ?>
                                                                            <img class="cstm-sightseeing-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <div class="hotel-details">
                                                                       <div class="heading-div">
                                                                        <p class="title-1 cstm-sightseeing-indiv-name"><?php echo $fetch_sightseeing['sightseeing_tour_name']."( ".$fetch_sightseeing['sightseeing_distance_covered']." KMS)"; ?>
                                                                    </p>
                                                                </div>
                                                                <div
                                                                class="heading-div c-h-div">
                                                                <div class="rating ">
                                                                    <p class="title-2 cstm-sightseeing-indiv-address"><b><?php echo $from_city.$between_city.$to_city; ?></b></p>

                                                                    <span class="span-i">Date</span>
                                                                    <p class="p-i"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>

                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-primary view_guide" id="viewindiv_<?php echo ($days_count+1) ?>_<?php echo $fetch_sightseeing_itinerary['sightseeing_guide_id']; ?>">View Guide</button>
                                                            <button type="button" class="btn btn-sm btn-primary view_driver" id="viewindiv_<?php echo ($days_count+1) ?>_<?php echo $fetch_sightseeing_itinerary['sightseeing_driver_id']; ?>">View Car / Driver</button>

                                                        </div>
                                                    </div>


                                                    <div class="hotel-info-div">
                                                        <div class="change">
                                                         <!--  <a href="#" class="remove">Remove</a> -->
                                                         <span class="hr"></span>
                                                         <span class="login-a add-modal-btn change_sightseeing change_remove_link" id="change_indiv_sightseeing__<?php echo ($days_count+1); ?>">Change</span>
                                                     </div>
                                                     <div class="change">
                                                        <span class="hr"></span>
                                                        <span class="login-a add-modal-btn remove_sightseeing change_remove_link" id="remove_indiv_sightseeing__<?php echo ($days_count+1); ?>">Remove</span>

                                                    </div>
                                                    <div class="change">
                                                      <span class="hr"></span>
                                                      <a href="<?php echo site_url();?>/sightseeing-details/?id=<?php echo $fetch_sightseeing['sightseeing_id']; ?>&itinerary=true" target="_blank" class="login-a add-modal-btn shows_sightseeing" id="shows_indiv_sightseeing__<?php echo ($days_count+1); ?>">Details</a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <?php
              }
              else
              {
                ?>
                <div class="gdlr-core-toggle-box-item-content-wrapper">

                   <button type="button" class="collapsible" >Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></button>
                   <div class="content ">
                    <p class="des"></p>

                    <div class="day-count">

                     <div class="t-tab" style="display: none;">
                       <div class="row">
                        <div class="col-md-12 sightseeing_indiv_select" id="sightseeing_indiv_select__<?php echo ($days_count+1) ?>" style="margin:0 0 0px">

                          <div class="hotel-list-div">
                            <div class="hotel-div">
                                <div class="hotel-img-div">

                                    <img class="cstm-sightseeing-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                </div>
                                <div class="hotel-details">
                                    <div class="heading-div">
                                        <p class="title-1 cstm-sightseeing-indiv-name">
                                        </p>
                                    </div>
                                    <div
                                    class="heading-div c-h-div">
                                    <div class="rating ">
                                        <p class="title-2 cstm-sightseeing-indiv-address"><b></b></p>

                                        <span class="span-i">Date</span>
                                        <p class="p-i"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>

                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary view_guide" id="viewindiv_<?php echo ($days_count+1) ?>_1">View Guide</button>
                                <button type="button" class="btn btn-sm btn-primary view_driver" id="viewindiv_<?php echo ($days_count+1) ?>_1">View Car / Driver</button>

                            </div>
                        </div>


                        <div class="hotel-info-div">
                            <div class="change">
                             <!--  <a href="#" class="remove">Remove</a> -->
                             <span class="hr"></span>
                             <span class="login-a add-modal-btn change_sightseeing change_remove_link" id="change_indiv_sightseeing__<?php echo ($days_count+1); ?>">Change</span>
                         </div>
                         <div class="change">
                            <span class="hr"></span>
                            <span class="login-a add-modal-btn remove_sightseeing change_remove_link" id="remove_indiv_sightseeing__<?php echo ($days_count+1); ?>">Remove</span>

                        </div>
                        <div class="change">
                           <span class="hr"></span>
                           <a href="" target="_blank" class="login-a add-modal-btn shows_sightseeing" id="shows_indiv_sightseeing__<?php echo ($days_count+1); ?>">Details</a>
                       </div>
                   </div>
               </div>
           </div>

       </div>
   </div>
</div>
</div>
</div>

<?php
}
}
?>
</div>
</div>
</div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 25px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>
</div>
</div>
</div>

<div class="gdlr-core-pbf-wrapper click-activities navtab" style="padding: 20px 0px 20px 0px;"
data-skin="Blue Icon" id="activities">
<div class="gdlr-core-pbf-background-wrap"></div>
<div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="gdlr-core-pbf-element">
            <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr"
            style="padding-bottom:35px;">
            <div class="gdlr-core-title-item-title-wrap">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size: 24px ;font-weight: 600 ;letter-spacing: 0px ;text-transform:none;">
                Activities<span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6>
        </div>
    </div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-toggle-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-toggle-box-style-background-title gdlr-core-left-align"
    style="padding-bottom:15px;">
    <div class="gdlr-core-toggle-box-item-tab clearfix">
        <div class="gdlr-core-toggle-box-item-icon gdlr-core-js gdlr-core-skin-icon ">
        </div>
        <div class="tourmaster-tour-item-holder gdlr-core-js-2 clearfix" data-layout="fitrows">
            <?php

            for($days_count=0;$days_count<count($result_data['itinerary']['itinerary_details_day_wise'])-1;$days_count++)
            {
                ?>
                <div class="gdlr-core-toggle-box-item-content-wrapper">
                                                    <!-- <h4
                                                        class="gdlr-core-toggle-box-item-title gdlr-core-js  gdlr-core-skin-e-background gdlr-core-skin-e-content">
                                                        <span class="gdlr-core-head">Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></span><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_title']?></h4>
                                                        <div class="gdlr-core-toggle-box-item-content"> -->
                                                           <button type="button" class="collapsible" >Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></button>
                                                           <div class="content ">
                                                            <p class="des"><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_desc']?></p>
                                                            <div class="day-count">

                                                                <?php
                                                                if(is_array($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity']))
                                                                {
                                                                   $activity_count=count($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity']);
                                                               }
                                                               else
                                                               {
                                                                $activity_count=0;
                                                            }
                                                            $activity_show_count=0;
                                                            for($activity_counter=0;$activity_counter < $activity_count;$activity_counter++)
                                                            {
                                                                $activity_show_count++;
                                                                $fetch_activity=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity'][$activity_counter]['activity_details'];

                                                                $fetch_activity_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['activity'][$activity_counter]['activity_itinerary_details'];
                                                                if($activity_show_count==0)
                                                                {
                                                                    echo '<div class="t-tab">
                                                                    <label>Activities</label>';
                                                                }
                                                                ?>
                                                                <div class="row">
                                                                    <div class="col-md-12 activity_indiv_select__<?php echo ($days_count+1) ?>" id="activity_indiv_select__<?php echo ($days_count+1) ?>__<?php echo ($activity_counter+1)?>" style="margin:0 0 25px">
                                                                        <div class="hotel-list-div">

                                                                            <div class="hotel-div">
                                                                                <div class="hotel-img-div">

                                                                                 <?php 
                                                                                 $get_activity_images=unserialize($fetch_activity['activity_images']);

                                                                                 if(!empty($get_activity_images))
                                                                                 {
                                                                                  ?>

                                                                                  <img class="cstm-activity-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/activities_images/'.$get_activity_images[0] ?>"
                                                                                  style="width:100%">
                                                                                  <?php
                                                                              }
                                                                              else
                                                                              {
                                                                                ?>
                                                                                <img class="cstm-activity-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <div class="hotel-details">
                                                                            <div class="heading-div">
                                                                                <p class="hotel-name cstm-activity-indiv-name">
                                                                                    <?php echo $fetch_activity['activity_name']; ?>
                                                                                </p>

                                                                            </div>
                                                                            <p class="info cstm-activity-indiv-address"> <?php echo $fetch_activity['activity_location']; ?></p>
                                                                            <div
                                                                            class="heading-div c-h-div">
                                                                            <div class="rating">
                                                                                <span
                                                                                class="span-i">DATES</span>
                                                                                <p class="title-2"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>


                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                                <div class="hotel-info-div">

                                                                    <span  class="login-a add-modal-btn change_activity change_remove_link" id="change_activity__<?php echo ($days_count+1); ?>_<?php echo ($activity_counter+1); ?>">Change</span>
                                                                    <span class="login-a add-modal-btn remove_activity change_remove_link" id="remove_activity__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>">Remove</span>
                                                                    <a href="<?php echo site_url();?>/activity-details/?id=<?php echo $fetch_activity['activity_id']; ?>&itinerary=true" target="_blank" class="login-a add-modal-btn show_indiv_activity" id="show_indiv_activity__<?php echo ($days_count+1); ?>__<?php echo ($activity_counter+1); ?>">Details</a>



                                                                </div>

                                                            </div>



                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>





                                            <?php
                                            if($activity_show_count==0)
                                            {
                                                ?>
                                                <div class="t-tab" style="display:none">
                                                  <label>Activities</label>
                                                  <div class="row">
                                                    <div class="col-md-12 activity_indiv_select__<?php echo ($days_count+1) ?>" id="activity_indiv_select__<?php echo ($days_count+1) ?>__<?php echo ($activity_counter+1)?>" style="margin:0 0 25px">
                                                        <div class="hotel-list-div">

                                                            <div class="hotel-div">
                                                                <div class="hotel-img-div">
                                                                    <img class="cstm-activity-indiv-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">
                                                                </div>
                                                                <div class="hotel-details">
                                                                    <div class="heading-div">
                                                                        <p class="hotel-name cstm-activity-indiv-name">
                                                                        </p>

                                                                    </div>
                                                                    <p class="info cstm-activity-indiv-address"></p>
                                                                    <div
                                                                    class="heading-div c-h-div">
                                                                    <div class="rating">
                                                                        <span
                                                                        class="span-i">DATES</span>
                                                                        <p class="title-2"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>


                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                        <div class="hotel-info-div">

                                                            <span  class="login-a add-modal-btn change_activity change_remove_link" id="change_activity__<?php echo ($days_count+1); ?>_1">Change</span>
                                                            <span class="login-a add-modal-btn remove_activity change_remove_link" id="remove_activity__<?php echo ($days_count+1); ?>__1">Remove</span>
                                                            <a href="" target="_blank" class="login-a add-modal-btn show_indiv_activity" id="show_indiv_activity__<?php echo ($days_count+1); ?>__1">Details</a>



                                                        </div>

                                                    </div>



                                                </div>

                                            </div>
                                        </div>


                                        <?php
                                    }
                                    ?>


                                    <div class="text-center"><button type="button" class="btn btn-md btn-primary add_activity add-modal-btn" id="add_indiv_activity__<?php echo ($days_count+1); ?>" style="margin-top:6px;">+ Add Activity</button></div>
                                </div>
                            </div>

                            <?php

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gdlr-core-pbf-element">
        <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
        style="margin-bottom: 25px ;">
        <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
    </div>
</div>
</div>
</div>
</div>
<div class="gdlr-core-pbf-wrapper click-transfer navtab" style="padding: 20px 0px 20px 0px;"
data-skin="Blue Icon" id="transfer">
<div class="gdlr-core-pbf-background-wrap"></div>
<div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="gdlr-core-pbf-element">
            <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr"
            style="padding-bottom:35px;">
            <div class="gdlr-core-title-item-title-wrap">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size: 24px ;font-weight: 600 ;letter-spacing: 0px ;text-transform:none;">
                Transfers<span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6>
        </div>
    </div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-toggle-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-toggle-box-style-background-title gdlr-core-left-align"
    style="padding-bottom:15px;">
    <div class="gdlr-core-toggle-box-item-tab clearfix">
        <div class="gdlr-core-toggle-box-item-icon gdlr-core-js gdlr-core-skin-icon ">
        </div>
        <div class="tourmaster-tour-item-holder gdlr-core-js-2 clearfix" data-layout="fitrows">
            <?php
            for($days_count=0;$days_count<count($result_data['itinerary']['itinerary_details_day_wise'])-1;$days_count++)
            {
              if(!empty($result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']))
              {
               $fetch_transfer=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_details'];
               $fetch_transfer_itinerary=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_itinerary_details'];
               $transfer_name=$result_data['itinerary']['itinerary_details_day_wise'][$days_count]['transfer']['transfer_name'];
               ?>
               <div class="gdlr-core-toggle-box-item-content-wrapper">
                   <button type="button" class="collapsible" >Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></button>
                   <div class="content ">
                    <p class="des"><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_desc']?></p>

                    <div class="day-count">
                        <div class="t-tab">
                            <div class="row">
                                <div class="col-md-12 transfer_indiv_select" id="transfer_indiv_select__<?php echo ($days_count+1) ?>">
                                    <div class="hotel-list-div">
                                        <div class="hotel-div">
                                            <div class="hotel-img-div">
                                              <?php 
                                              $get_transfer_images=unserialize($fetch_transfer['transfer_vehicle_images']);
                                              if(!empty($get_transfer_images[0][0]))
                                              {
                                                  ?>
                                                  <img class="cstm-indiv-transfer-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/uploads/vehicle_images/'.$get_transfer_images[0][0]; ?>"
                                                  style="width:100%">
                                                  <?php
                                              }
                                              else
                                              {
                                                ?>
                                                <img class="cstm-indiv-transfer-image" src="<?php echo $GLOBALS["api_base_url"].'/assets/images/no-photo.png'; ?>" style="width:100%">

                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="hotel-details">

                                            <div class="heading-div">
                                                <p class="hotel-name cstm-indiv-transfer-name">
                                                    <?php echo  $transfer_name; ?></p>

                                                </div>


                                                <p class="info cstm-indiv-transfer-car"><?php echo $fetch_transfer_itinerary['transfer_name']; ?></p>
                                                <div
                                                class="heading-div c-h-div">
                                                <div class="rating ">
                                                    <span
                                                    class="span-i">DATE </span>
                                                    <p class="p-i"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                                                </div>
                                                <div>
                                                 <?php
                                                 $price=$fetch_transfer_itinerary['transfer_cost'];
                                                 $total_transfer_cost=round($price);

                                                 ?>

                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="hotel-info-div">
                                   <div class="change">
                                     <span class="hr"></span>
                                     <span  class="login-a add-modal-btn change_transfer change_remove_link" id="change_transfer__<?php echo ($days_count+1); ?>">Change</span>
                                     <span class="login-a add-modal-btn remove_transfer change_remove_link" id="remove_transfer__<?php echo ($days_count+1); ?>">Remove</span>
                                 </div>

                             </div>

                         </div>



                     </div>

                 </div>
             </div>
         </div>
     </div>
 </div>
 <?php
}

}
?>
</div>
</div>
</div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 25px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>
</div>
</div>
</div>

<div class="gdlr-core-pbf-wrapper click-restaurant navtab" style="padding: 20px 0px 20px 0px;"
data-skin="Blue Icon" id="transfer">
<div class="gdlr-core-pbf-background-wrap"></div>
<div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="gdlr-core-pbf-element">
            <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr"
            style="padding-bottom:35px;">
            <div class="gdlr-core-title-item-title-wrap">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size: 24px ;font-weight: 600 ;letter-spacing: 0px ;text-transform:none;">
                Restaurants<span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6>
        </div>
    </div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-toggle-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-toggle-box-style-background-title gdlr-core-left-align"
    style="padding-bottom:15px;">
    <div class="gdlr-core-toggle-box-item-tab clearfix">
        <div class="gdlr-core-toggle-box-item-icon gdlr-core-js gdlr-core-skin-icon ">
        </div>
        <div class="tourmaster-tour-item-holder gdlr-core-js-2 clearfix" data-layout="fitrows">
            <?php
            for($days_count=0;$days_count<count($result_data['itinerary']['itinerary_details_day_wise'])-1;$days_count++)
            {

                ?>
                <div class="gdlr-core-toggle-box-item-content-wrapper">
                   <button type="button" class="collapsible" >Day <?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_number']?></button>
                   <div class="content ">
                    <p class="des"><?php echo $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['day_desc']?></p>

                    <div class="day-count">
                        <div class="t-tab" style="display: none">
                            <div class="row">
                                <div class="col-md-12 restaurant_indiv_select__<?php echo ($days_count+1); ?>" id="restaurant_indiv_select__<?php echo ($days_count+1); ?>__1" style="margin:0 0 25px !important;display:none">
                                    <div class="hotel-list-div">

                                        <div class="hotel-div">
                                            <div class="hotel-img-div">
                                              <img class="cstm-restaurant-indiv-image" src=""
                                              style="width:100%">

                                          </div>
                                          <div class="hotel-details">
                                            <div class="heading-div">
                                                <p class="hotel-name cstm-restaurant-indiv-name"></p>
                                            </div>


                                            <p class="info"></p>
                                            <p class="address cstm-restaurant-indiv-address" style="    margin-bottom: 5px !important;"><i class="fa fa-map-marker"></i></p>
                                            <div class="heading-div c-h-div">
                                                <div class="rating">
                                                    <span class="span-i">SELECTED FOOD / DRINK :</span>
                                                    <p class="title-2 cstm-restaurant-indiv-food-type"></p>
                                                    <div class="row">
                                                     <div class="col-md-7">
                                                        <span class="span-i">DATE</span>
                                                        <p class="title-2"><?php echo date('D, d M Y',strtotime("+".($days_count)." days",strtotime($itinerary_date_from))); ?></p>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <span class="span-i">FOOD / DRINK </span>
                                                        <p class="title-2 cstm-restaurant-indiv-food-purpose"></p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="restaurant_food_detail" id="restaurant_food_detail__<?php echo ($days_count+1); ?>__1"></div>
                                </div>
                                <div class="hotel-info-div">

                                   <a href="#" class="login-a add-modal-btn change_restaurant" id="change_indiv_restaurant__<?php echo ($days_count+1); ?>_1">Change</a>

                                   <a href="javascript:void(0)" class="login-a add-modal-btn remove_restaurant" id="remove_indiv_restaurant__<?php echo ($days_count+1); ?>__1">Remove</a>

                                   <a href="" class="login-a add-modal-btn show_restaurant" id="show_indiv_restaurant__<?php echo ($days_count+1); ?>__1" target="_blanks">Details</a>

                               </div>

                           </div>


                       </div>


                   </div>
               </div>
           </div>
           <div class="text-center"><button type="button" class="btn btn-md btn-primary add_restaurant add-modal-btn" id="add_indiv_restaurant__<?php echo ($days_count+1); ?>">+ Add Restaurant</button></div>
       </div>
   </div>

   <?php

}
?>
</div>
</div>
</div>
</div>
<div class="gdlr-core-pbf-element">
    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-center-align"
    style="margin-bottom: 25px ;">
    <div class="gdlr-core-divider-line gdlr-core-skin-divider"></div>
</div>
</div>
</div>
</div>
</div>
<div class="gdlr-core-pbf-wrapper" style="padding:0px">
  <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
        <div class="col-md-12" style="margin-top:30px">
            <div class="minHeight100vh">
                <div class="appendTop20">
                    <div class=" relative  latoBold font16 lineHeight18 appendBottom20 active">
                        <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                        style="font-size:24px; font-weight:600; letter-spacing:0px; text-transform:none;">
                        Exclusions<span
                        class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
                    </h6></div>
                    <div class="accordContent paddingB20 lineHeight18">
                     <span class="gettourexcl"> <?php echo $result_data['itinerary']['itinerary_exclusions'] ?></span>
                 </div>
                 <div class=" relative  latoBold font16 lineHeight18 appendBottom20 active">
                    <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                    style="font-size:24px; font-weight:600; letter-spacing:0px; text-transform:none;">
                    Terms And Conditions<span
                    class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
                </h6></div>
                <div class="accordContent paddingB20 lineHeight18">
                 <span class="gettourtermsC">  <?php echo $result_data['itinerary']['itinerary_terms_and_conditions'] ?></span>
             </div>
             <div class=" relative  latoBold font16 lineHeight18 appendBottom20 active">
                <h6 class="gdlr-core-title-item-title gdlr-core-skin-title"
                style="font-size:24px; font-weight:600; letter-spacing:0px; text-transform:none;">
                Cancellation  Policy <span
                class="gdlr-core-title-item-title-divider gdlr-core-skin-divider"></span>
            </h6></div>
            <div class="accordContent paddingB20 lineHeight18">
               <span class="gettourtermsCancel">   <?php echo $result_data['itinerary']['itinerary_cancellation'] ?></span>
           </div>


       </div>
   </div>
</div>
</div>
</div>

</div>
<div class="tourmaster-tour-booking-bar-widget  traveltour-sidebar-area booking-bar-hide booking-bar-hide">
    <div id="text-12" class="widget widget_text traveltour-widget">
        <div class="textwidget"><span class="gdlr-core-space-shortcode"
            style="margin-top: -10px ;"></span>
            <div class="gdlr-core-widget-box-shortcode "
            style="color: #c9e2ff ;background-image: url(https://demo.goodlayers.com/traveltour/hiking/wp-content/uploads/2019/05/sidebar-bg.jpg) ;">
            <h3 class="gdlr-core-widget-box-shortcode-title" style="color: #ffffff ;">
            Get a Question?</h3>
            <div class="gdlr-core-widget-box-shortcode-content">
                <p>Do not hesitage to give us a call. We are an expert team and we are
                happy to talk to you.</p>
                <p><i class="fa fa-phone"
                    style="font-size: 20px ;color: #ffa11a ;margin-right: 10px ;"></i>
                    <span style="font-size: 20px; color: #ffffff; font-weight: 600;">
                    +995 322 35 35 99 </span></p>
                    <p><i class="fa fa-envelope-o"
                        style="font-size: 17px ;color: #ffa11a ;margin-right: 10px ;"></i>
                        <span
                        style="font-size: 14px; color: #fff; font-weight: 600;">office@traveldoor.ge</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


</div>
</form>
</div>
</div>
</div>
</div>



<span class="tourmaster-user-top-bar-loader" data-tmlb="selectLoaderModal" style="display:none"><i class="icon_lock_alt"></i></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectLoaderModal" style="background: none;
text-align: center;">

<div class="tourmaster-lightbox-content">
    <i class="tourmaster-lightbox-close icon_close modalCloseIcon loaderClose" style="display:none"></i>
    <h4 style="color:white">Searching Best Results For You....</h4>
    <div class="country-loader" style="width: 100px;
    height: 100px;
    display:inline-block;">
    <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
    viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
    <circle fill="#ffa019" stroke="none" cx="6" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 15 ; 0 -15; 0 15" 
        repeatCount="indefinite" 
        begin="0.1"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="30" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 10 ; 0 -10; 0 10" 
        repeatCount="indefinite" 
        begin="0.2"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="54" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 5 ; 0 -5; 0 5" 
        repeatCount="indefinite" 
        begin="0.3"/>
    </circle>
</svg>
</div>
</div>
</div>


<span class="tourmaster-user-top-bar-cost-loader" data-tmlb="selectCostLoaderModal" style="display:none"><i class="icon_lock_alt"></i></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectCostLoaderModal" style="background: none;
text-align: center;">

<div class="tourmaster-lightbox-content">
    <i class="tourmaster-lightbox-close icon_close modalCloseIcon loaderClose" style="display:none"></i>
    <h4 style="color:white">Calculating Total Cost For Your Package...</h4>
    <div class="country-loader-new" style="width: 100px;
    height: 100px;
    display:inline-block;">

    <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
    viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
    <circle fill="#ffa019" stroke="none" cx="6" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 15 ; 0 -15; 0 15" 
        repeatCount="indefinite" 
        begin="0.1"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="30" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 10 ; 0 -10; 0 10" 
        repeatCount="indefinite" 
        begin="0.2"/>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="54" cy="50" r="6">
        <animateTransform 
        attributeName="transform" 
        dur="1s" 
        type="translate" 
        values="0 5 ; 0 -5; 0 5" 
        repeatCount="indefinite" 
        begin="0.3"/>
    </circle>
</svg>
</div>
</div>
</div>

<span class="tourmaster-user-top-bar-hotels" data-tmlb="selectHotelModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Select Hotel</span></span>
<div class="tourmaster-lightbox-content-wrap hotel-main-modal" data-tmlb-id="selectHotelModal" id="selectHotelModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Select Hotel</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content modal-section">
      <div id="first_step_hotel" class="first_step_hotel">
        <input type="hidden" id="hotel_day_index" class="hotel_day_index">
        <input type="hidden" id="hotel_offset" name="hotel_offset">
        <div class="row popupmargin hotel-filter-row">
            <div class="col-md-2 filter-item">
                <label for="hotel_name_filter"><b>Hotel Name</b></label><br>
                <input name="hotel_name_filter" id="hotel_name_filter" class="form-control form-control-modal hotel_name_filter" placeholder="Type Hotel Name / Address">
            </div>
            <div class="col-md-2 filter-item">
                <label for="hotel_type_filter"><b>Hotel Type</b></label><br>
                <select name="hotel_type_filter" id="hotel_type_filter" class="form-control form-control-modal">
                    <option value="">Any Hotel Type</option>
                    <?php
                    foreach($hotel_type as $type)
                    {
                        echo '<option value="'.$type['hotel_type_id'].'">'.$type['hotel_type_name'].'</option>';
                    }
                    ?>

                </select>
            </div>
            <div class="col-md-2 filter-item">
                <label for="hotel_star_filter"><b>Hotel Stars</b></label><br>
                <select name="hotel_star_filter" id="hotel_star_filter" class="form-control form-control-modal">
                    <option value="">Any Hotel Star</option>
                    <?php
                    for($i=5;$i>=1;--$i)
                    {
                        echo "<option  value='".$i."'>".$i." Stars</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2 filter-item">
                <label for="hotel_sort_filter"><b>Sort By</b></label><br>
                <select name="hotel_sort_filter" id="hotel_sort_filter" class="form-control form-control-modal sort-width-set">
                    <option value="" hidden>Sort By</option>
                    <option value="price_low_high">Price: Low to High</option>
                    <option value="price_high_low">Price: High to Low</option>
                </select>
            </div>
            <div class="col-md-2 filter-item" style="">
               <label for="hotel_sort_filter"> </label><br>
               <button type="button" id="filter_hotel">Filter Hotels</button>
           </div>
       </div>
       <br>
       <div id="hotels_div" class="hotels_div">
       </div>
       <button type="button" class="btn btn-primary show_more_itinerary_hotel morehotelbtn" id="show_more_itinerary_hotel" style=" margin: 5% 0%;width:100%;display: none !important;">Show More Hotels</button>
       <button type="button" class="btn btn-primary no_more_itinerary_hotel" id="no_more_itinerary_hotel"  style=" margin: 5% 0%; width:100%;display: none !important;background-color: gray !important;" disabled="disabled">No More Hotels</button>
   </div>
   <div id="second_step_hotel" class="second_step_hotel" style="display:none">
       <button id="go_back_hotel_btn" class="btn btn-default go_back_hotel_btn"><i class="fa fa-angle-left go-icon"></i> Go back to Hotel List</button>
       <div id="hotels_details_div" class="hotels_details_div yo">

       </div>
       <div class="tourmaster-hotels-bottom">
        <button class="btn btn-primary include_hotel" id="include_hotel">INCLUDE</button>
    </div>
</div>
<div class="tourmaster-hotels-bottom">

</div>
</div>
</div>

<span class="tourmaster-user-top-bar-sightseeing" data-tmlb="selectSightseeingModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Select Daily Tour</span></span>
<div class="tourmaster-lightbox-content-wrap sight-main-modal" data-tmlb-id="selectSightseeingModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Select Daily Tour</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content">
       <div id="first_step_sightseeing" class="first_step_sightseeing">
        <input type="hidden" id="sightseeing_day_index" class="sightseeing_day_index">
        <input type="hidden" id="sightseeing_offset" name="sightseeing_offset">
        <div class="row" id="sightseeing_filter">
            <div class="col-md-6">
                <label for="sightseeing_name_filter"><b>Daily Tour Name</b></label>
                <input name="sightseeing_name_filter" id="sightseeing_name_filter" class="form-control form-control-modal" placeholder="Daily Tour Name">
            </div>
            <div class="col-md-6">
                <label for="sightseeing_sort_filter"><b>Sort By</b></label>
                <select name="sightseeing_sort_filter" id="sightseeing_sort_filter" class="form-control form-control-modal">
                    <option value="" hidden>Sort By</option>
                    <option value="price_low_high">Price: Low to High</option>
                    <option value="price_high_low">Price: High to Low</option>
                </select>
            </div>
        </div>
        <br>
        <div id="sightseeing_div" class="sightseeing_div">
        </div>
        <button type="button" class="btn btn-primary show_more_itinerary_sightseeing morehotelbtn" id="show_more_itinerary_sightseeing" style=" margin: 5% 0%;display: none !important;">Show More Daily Tour</button>
        <button type="button" class="btn btn-primary no_more_itinerary_sightseeing" id="no_more_itinerary_sightseeing"  style=" margin: 5% 0%;display: none !important;background-color: gray !important;" disabled="disabled">No More Daily Tour</button>
    </div>
    <div id="second_step_sightseeing" class="second_step_sightseeing" style="display:none">
       <button id="go_back_sightseeing_btn" class="btn btn-default go_back_sightseeing_btn"><i class="fa fa-angle-left go-icon"></i> Go Back To Daily Tour List</button>
       <p class="price pricefixedbtn1"><strong>Total Price</strong> <span id="sightseeing_total_price_text" class="sightseeing_total_price_text pricefixedbtn">GEL 0</span></p>
       <div id="sightseeing_details_div" class="sightseeing_details_div">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="sightseeing_details_id" id="sightseeing_details_id" class="sightseeing_details_id">
                                <select class="form-control type-select" id="sightseeing_details_tour_type" style="display:none">
                                    <option value="private" selected="selected">PRIVATE TOUR</option>
                                    <option value="group">GROUP TOUR</option>                                                   
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="row main-info-row-div">
                                    <div class="col-md-9 col-sm-12">
                                        <p class="para-h sightseeing_details_name" id="sightseeing_details_name" >Tbilisi - Mtskheta -Tbilisi</p>
                                        <div class="row s-info-row-main-div">
                                            <div class="col-md-4 no-padding">
                                                <p class="address sightseeing_details_address" id="sightseeing_details_address"> <i class="fa fa-map-marker"></i> (
                                                    Tbilisi-Mtskheta )
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="address sightseeing_details_distance" id="sightseeing_details_distance"> <i class="fa fa-road" ></i> 51 KMS</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="address sightseeing_details_duration" id="sightseeing_details_duration"><i class="fa fa-clock-o" ></i> 8 HOURS</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <input type="hidden" name="sightseeing_adult_cost" id="sightseeing_adult_cost" class="sightseeing_adult_cost" value="">
                                        <input type="hidden" name="sightseeing_group_cost" id="sightseeing_group_cost" class="sightseeing_group_cost" value="">
                                        <input type="hidden" name="sightseeing_total_cost" id="sightseeing_total_cost" class="sightseeing_total_cost" value="">
                                        <input type="hidden" name="selected_vehicle_type_id" id="selected_vehicle_type_id" class="selected_vehicle_type_id" value="0">
                                        <input type="hidden" name="selected_vehicle_min" id="selected_vehicle_min" class="selected_vehicle_min" value="0">
                                        <input type="hidden" name="selected_vehicle_max" id="selected_vehicle_max" class="selected_vehicle_max" value="0">
                                        <input type="hidden" name="selected_vehicle_type_name"  id="selected_vehicle_type_name" class="selected_vehicle_type_name" value="">
                                        <input type="hidden" name="selected_sightseeing_guide_id"  id="selected_sightseeing_guide_id" value="0" class="selected_guide_id">
                                        <input type="hidden" name="selected_sightseeing_guide_name"  id="selected_sightseeing_guide_name" value="" class="selected_guide_name">
                                        <input type="hidden" name="selected_sightseeing_guide_cost" id="selected_sightseeing_guide_cost" value="0" class="selected_guide_cost">
                                        <input type="hidden" name="selected_sightseeing_driver_id" id="selected_sightseeing_driver_id" value="0" class="selected_driver_id">
                                        <input type="hidden" name="selected_sightseeing_driver_name"  id="selected_sightseeing_driver_name" value="" class="selected_driver_name">
                                        <input type="hidden" name="selected_sightseeing_driver_cost" id="selected_sightseeing_driver_cost" value="0" class="selected_driver_cost">
                                        <input type="hidden" name="sightseeing_additional_cost" id="sightseeing_additional_cost" value="0" class="sightseeing_additional_cost">
                                        <input type="hidden" name="sightseeing_cities" id="sightseeing_cities" value="" class="sightseeing_cities">
                                        <input type="hidden"  id="selected_sightseeing_detail_link" value="" class="selected_sightseeing_detail_link">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="box">
                                            <div class="box-body" style="padding:0">
                                                <div class="grid-images sightseeing_detail_images" id="sightseeing_detail_images">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="section" id="">
                                            <p class="star-p"> <i class="fa fa-star star"></i>
                                                Daily Tour Description
                                            </p>
                                            <div id="sightseeing_detail_desc" class="sightseeing_detail_desc">

                                            </div>


                                        </div>
                                        <div class="section">
                                            <p class="star-p"> <i class="fa fa-star star"></i>
                                                Daily Tour Attractions
                                            </p>
                                            <div id="sightseeing_detail_attract" class="sightseeing_detail_attract">

                                            </div>
                                        </div>
                                        <hr style="border-color: #ffc0c0;width: 100%;">
                                    </div>
                                </div>
                                <p class="star-p"> <i class="fa fa-star star"></i>
                                 Select Vehicle
                             </p>
                             <div class="row" style="margin-top:20px" id="vehicle_type_div" class="vehicle_type_div">
                               <?php
                               foreach($get_vehicles as $vehicles)
                               {
                                 echo '<div class="col-md-4 text-center vehicle-type parent-tile" style="cursor: pointer">
                                 <div class="box box-body bg-dark pull-up"';

                                 if($vehicles['vehicle_type_image']!="")
                                 {
                                     echo 'style="background: url(\''.$GLOBALS["api_base_url"].'/assets/uploads/vehicle_type_images'.'/'.$vehicles['vehicle_type_image'].'\');background-size: cover;background-repeat: no-repeat;">';
                                 }
                                 else 
                                 {
                                  echo 'style="background: url(\''.$GLOBALS["api_base_url"].'/assets/images/vehicle-type.jpg\');background-size: cover;background-repeat: no-repeat;">';
                              }

                              echo '<div class="overlay"></div>
                              </div>
                              <p class="font-size-26 text-center color-tiles-text"> <a href="#" class="details text-light">
                              '.$vehicles['vehicle_type_name'].'
                              <input type="hidden" id="vehcile_type_name_'.$vehicles['vehicle_type_id'].'" name="vehicle_type_name_'.$vehicles['vehicle_type_id'].'" value="'.$vehicles['vehicle_type_name'].'"></a></p>
                              <button type="button" style="width: 100%;" class="btn vehicle-select-btn" id="vehicle_selected__'.$vehicles['vehicle_type_id'].'">Select</button>
                              <input type="radio" class="with-gap radio-col-primary vehcile_type_show" name="vehicle_type1" id="vehicle_type_'.$vehicles['vehicle_type_id'].'" value="'.$vehicles['vehicle_type_id'].'" data-minvalue="'.$vehicles['vehicle_type_min'].'" data-maxvalue="'.$vehicles['vehicle_type_max'].'" style="display:none">
                              <label for="vehicle_type_'.$vehicles['vehicle_type_id'].'" class="tile-label"></label>
                              </div>

                              ';            

                          }
                          ?>  

                      </div>
                      <hr>
                      <div>
                        <p class="star-p driver_div_head" id="driver_div_head" > <i
                            class="fa fa-star star"></i> Select Driver for Daily Tour
                        </p>
                        <div class="col-md-12 driver_div" id="driver_div">
                        </div>
                    </div>
                    <br>
                    <div style="clear: both;">
                        <p class="star-p guide_div_head" id="guide_div_head" > <i
                            class="fa fa-star star"></i> Select Guide for Daily Tour
                        </p>
                        <div class="col-md-12 guide_div" id="guide_div">
                        </div>
                    </div>
                    <div class="text-center table-sightseeing-loader"
                    style="display: none">
                    <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 100 100" enable-background="new 0 0 0 0"
                    xml:space="preserve">
                    <circle fill="#ffa019" stroke="none" cx="6" cy="50" r="6"
                    transform="translate(0 -3.75126)">
                    <animateTransform attributeName="transform" dur="1s"
                    type="translate" values="0 15 ; 0 -15; 0 15"
                    repeatCount="indefinite" begin="0.1">
                </animateTransform>
            </circle>
            <circle fill="#ffa019" stroke="none" cx="30" cy="50" r="6"
            transform="translate(0 1.49916)">
            <animateTransform attributeName="transform" dur="1s"
            type="translate" values="0 10 ; 0 -10; 0 10"
            repeatCount="indefinite" begin="0.2">
        </animateTransform>
    </circle>
    <circle fill="#ffa019" stroke="none" cx="54" cy="50" r="6"
    transform="translate(0 2.74958)">
    <animateTransform attributeName="transform" dur="1s"
    type="translate" values="0 5 ; 0 -5; 0 5"
    repeatCount="indefinite" begin="0.3">
</animateTransform>
</circle>
</svg>
</div>

</div>

</div>
</div>
</div>
</div>
</div>
</div>
<div class="tourmaster-sightseeing-bottom">
    <button class="btn btn-primary include_sightseeing" id="include_sightseeing">INLCUDE</button>
</div>
</div>
</div>
</div>
<span class="tourmaster-user-top-bar-guide" data-tmlb="selectGuideModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Guide Details</span></span>
<div class="tourmaster-lightbox-content-wrap selectGuideModal-2" data-tmlb-id="selectGuideModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Guide Details</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content">
        <br>
        <div id="guide_details_div" class="guide_details_div">
        </div>
        <div class="tourmaster-guide-bottom">

        </div>
    </div>
</div>

<span class="tourmaster-user-top-bar-driver" data-tmlb="selectDriverModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Driver Details</span></span>
<div class="tourmaster-lightbox-content-wrap selectDriverModal-2" data-tmlb-id="selectDriverModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Driver Details</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content" >
        <br>
        <div id="driver_details_div" class="driver_details_div">
        </div>
        <div class="tourmaster-driver-bottom">

        </div>
    </div>
</div>

<span class="tourmaster-user-top-bar-vehicle-images" data-tmlb="selectVehicleImagesModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Vehicle Images</span></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectVehicleImagesModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Vehicle Images</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content" >
        <br>
        <div id="vehicle_view_images" class="vehicle_view_images">
        </div>
        <div class="tourmaster-driver-bottom">

        </div>
    </div>
</div>

<span class="tourmaster-user-top-bar-room-info" data-tmlb="selectRoomInfoModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Room Info</span></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectRoomInfoModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title room_info_title">Room Info</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content" >
        <br>
        <div id="room_info_div" class="room_info_div">
        </div>
        <div class="tourmaster-room-info-bottom">

        </div>
    </div>
</div>




<span class="tourmaster-user-top-bar-activity" data-tmlb="selectActivityModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Select Activity</span></span>
<div class="tourmaster-lightbox-content-wrap activity-modal-2" data-tmlb-id="selectActivityModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Select Activity</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content">
        <input type="hidden" id="activities_day_index">
        <input type="hidden" id="activities_day_index_self">
        <div class="row">
            <div class="col-md-6">
                <label for="activity_name_filter"><b>Activity Name</b></label><br>
                <input name="activity_name_filter" id="activity_name_filter" class="form-control form-control-modal" placeholder="Type Activity Name">
            </div>
            <div class="col-md-6">
                <label for="activities_sort_filter"><b>Sort By</b></label><br>
                <select name="activities_sort_filter" id="activities_sort_filter" class="form-control form-control-modal">
                    <option value="" hidden>Sort By</option>
                    <option value="price_low_high">Price: Low to High</option>
                    <option value="price_high_low">Price: High to Low</option>
                </select>
            </div>
        </div>
        <br>
        <div id="activities_div" class="activities_div">
        </div>
        <div class="tourmaster-activity-bottom">

        </div>
    </div>
</div>

<span class="tourmaster-user-top-bar-transfers" data-tmlb="selectTransferModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Select Transfer</span></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectTransferModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Select Transfer</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content">
        <input type="hidden" id="transfer_day_index">
        <div class="row popupmargin">
            <div class="col-md-4">
                <label for="transfer_name_filter"><b>Transfer Car Name</b></label><br>
                <input name="transfer_name_filter" id="transfer_name_filter" class="form-control form-control-modal" placeholder="Type Transfer Car Name">
            </div>
            <div class="col-md-4">
                <label for="transfer_vehicle_type_filter"><b>Transfer Vehicle Type</b></label><br>
                <select name="transfer_vehicle_type_filter" id="transfer_vehicle_type_filter" class="form-control form-control-modal">
                    <option value="">Select Vehicle Type</option>
                    <?php
                    foreach($get_vehicles as $vehicles)
                    {
                        echo '<option value="'.$vehicles['vehicle_type_id'].'">'.$vehicles['vehicle_type_name'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="transfer_sort_filter"><b>Sort By</b></label><br>
                <select name="transfer_sort_filter" id="transfer_sort_filter" class="form-control form-control-modal">
                    <option value="" hidden>Sort By</option>
                    <option value="price_low_high">Price: Low to High</option>
                    <option value="price_high_low">Price: High to Low</option>
                </select>
            </div>
        </div>
        <br>
        <div id="transfer_div" class="transfer_div">
        </div>
        <hr>
<!-- <div class="tourmaster-transfers-bottom">
<button class="btn btn-primary include_transfer" id="include_transfer">APPLY</button>
</div> -->
</div>
</div>


<span class="tourmaster-user-top-bar-error" data-tmlb="selectErrorModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Error</span></span>
<div class="tourmaster-lightbox-content-wrap" data-tmlb-id="selectErrorModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title error_text_title">Error</h3>
        <i class="tourmaster-lightbox-close icon_close"></i>
    </div>
    <div class="tourmaster-lightbox-content">
      <h5 class="error_text"></h5>
      <div class="tourmaster-transfers-bottom pop-up-close" style="text-align: center;">
        <button class="btn btn-primary">OK</button>
    </div>
</div>
</div>

<style type="text/css">
    .guide-card-div {
        margin-bottom: 30px;
        box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.1);
    }
    .guide-card-div h3.gdlr-core-personnel-list-title {
        font-size: 22px;
        margin-bottom: 0 !important;
    }
    .guide-card-div .gdlr-core-personnel-list-content-wrap {
        padding: 10px;
    }
    .guide-card-div .gdlr-core-language {
        margin-top: 5px;
    }
    .guide-card-div .gdlr-core-personnel-list-content p {
        margin-bottom: 5px;
    }
</style>
<span class="tourmaster-user-top-bar-transfer-guide" data-tmlb="selectTransferGuideModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Select Transfer</span></span>
<div class="tourmaster-lightbox-content-wrap guide-main-modal" data-tmlb-id="selectTransferGuideModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Select Transfer Guide</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content">
        <input type="hidden" id="transfer_guide_day_index">
        <input type="hidden" name="selected_transfer_guide_id"  id="selected_transfer_guide_id" value="" class="selected_guide_id">
        <input type="hidden" name="selected_transfer_guide_name"  id="selected_transfer_guide_name" value="" class="selected_guide_name">
        <input type="hidden" name="selected_transfer_guide_cost" id="selected_transfer_guide_cost" value="0" class="selected_guide_cost">

        <div id="transfer_guide_div">
        </div>
        <div class="tourmaster-transfers-bottom">

          <button class="btn btn-primary include_transfer include-apply-button" id="include_transfer_guide">APPLY</button>
      </div>
  </div>
</div>


<span class="tourmaster-user-top-bar-restaurant" data-tmlb="selectRestaurantModal" style="display:none"><i class="icon_lock_alt"></i><span class="tourmaster-text">Select Restaurant</span></span>
<div class="tourmaster-lightbox-content-wrap selectRestaurantmodal-2" data-tmlb-id="selectRestaurantModal" id="selectRestaurantModal">
    <div class="tourmaster-lightbox-head">
        <h3 class="tourmaster-lightbox-title">Select Restaurant</h3>
        <i class="tourmaster-lightbox-close icon_close modalCloseIcon modalCloseIcon"></i>
    </div>
    <div class="tourmaster-lightbox-content">
     <input type="hidden" id="restaurant_day_index" class="restaurant_day_index">
     <input type="hidden" id="restaurant_day_index_self" class="restaurant_day_index_self">
     <input type="hidden" id="restaurant_offset" class="restaurant_offset">
     <div class="row restaurant_filter popupmargin" id="restaurant_filter">
        <div class="col-md-5">
            <label for="restaurant_name_filter"><b>Restaurant Name</b></label><br>
            <input name="restaurant_name_filter" id="restaurant_name_filter" class="form-control form-control-modal restaurant_name_filter" placeholder="Restaurant Name">
        </div>
        <div class="col-md-5">
            <label for="restaurant_type_filter"><b>Restaurant Type</b></label><br>
            <select name="restaurant_type_filter" id="restaurant_type_filter" class="form-control form-control-modal">
                <option value="">Any Restaurant Type</option>
                <?php
                foreach($restaurant_type as $type)
                {
                    echo '<option value="'.$type['restaurant_type_id'].'">'.$type['restaurant_type_name'].'</option>';
                }
                ?>

            </select>
        </div>
    </div>
    <br>
    <div id="restaurant_div" class="restaurant_div">
    </div>
    <div id="restaurant_div_buttons" class="restaurant_div_buttons">
     <button type="button" class="btn btn-primary more_restaurant show_more_restaurant" id="show_more_restaurant" style=" margin: 5% 0%;width:100%;display: none !important;">Show More Restaurants</button>
     <button type="button" class="btn btn-primary more_restaurant no_more_restaurant" id="no_more_restaurant"  style=" margin: 5% 0%; width:100%;display: none !important;" disabled="disabled">No More Restaurants</button>
 </div>

 <div id="restaurant_details_div" class="restaurant_details_div">
   <button id="go_back_restaurant_btn" class="btn btn-default go-back-restaurant-pop2"><i class="fa fa-angle-left go-icon"></i> Go back to Restaurant List</button>
   <p class="price pricefixedbtn2">Total Price <span id="restaurant_total_price_text" class="restaurant_total_price_text pricefixedbtn">GEL 0</span></p>
   <div class="row go-back-restaurant-pop">
    <div class="col-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="restaurant_details_id" id="restaurant_details_id" class="restaurant_details_id">
                    <div class="col-md-12" style="padding: 10px">
                        <div class="row" style="margin-bottom: -40px;">
                            <div class="col-md-6 col-sm-12">
                                <p class="para-h restaurant_details_name" id="restaurant_details_name">Restaurant Name</p>
                                <small id="restaurant_details_type"></small>
                                <div class="row" style="margin-left: 5px;">
                                    <div class="col-md-6">
                                        <p class="address restaurant_details_address" id="restaurant_details_address"><i class="fa fa-map-marker"></i></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="address restaurant_details_timing" id="restaurant_details_timing">
                                            <i class="fa fa-clock-o" ></i></p>
                                        </div>      
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                   <input type="hidden"  id="selected_restaurant_address" class="selected_restaurant_address" value="">
                                   <input type="hidden"  id="selected_restaurant_detail_link" class="selected_restaurant_detail_link" value="">
                                   <input type="hidden"  id="selected_restaurant_total_price" class="selected_restaurant_total_price" value="0">
                                   <input type="hidden"  id="selected_restaurant_food_items" class="selected_restaurant_food_items" value="">

                               </div>


                           </div>
                           <div class="row" style="justify-content: center">
                            <div class="col-sm-12 restaurant_food_select_info_head" id="restaurant_food_select_info_head">
                                <h4><strong>Selected Food /  Drink Items :</strong></h4>
                                <div id="restaurant_food_select_info" class="restaurant_food_select_info">

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12" style="padding: 8px;">
                                <div class="section" id="">
                                    <p class="star-p"> <i class="fa fa-star star"></i>
                                        Restaurant Images
                                    </p>
                                    <div class="grid-images restaurant_detail_images" id="restaurant_detail_images">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="section" id="">
                                    <p class="star-p"> <i class="fa fa-star star"></i>
                                        Restaurant Description
                                    </p>
                                    <div id="restaurant_detail_desc" class="restaurant_detail_desc">

                                    </div>


                                </div>
                                <hr style="border-color: #ffc0c0;width: 100%;">
                            </div>
                        </div>

                        <hr>
                        <br>
                        <p class="star-p" id="restaurant_menu_div_head"> <i
                            class="fa fa-star star"></i> Restaurant Menu
                        </p>
                        <div class="col-md-12 restaurant_menu_div" id="restaurant_menu_div">
                        </div>
                        <br>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="tourmaster-restaurant-bottom go-back-restaurant-pop3">

    <div class="row" style="justify-content: flex-end;">
       <div class="col-md-3">
           <label for="food_time" class="control-label"><b>Select Time :</b></label>
           <input type="time" name="food_time" class="form-control form-control-modal food_time">
       </div>
       <div class="col-md-3">
           <label for="food_for" class="control-label"><b>Food / Drink For :</b></label>
           <select name="food_for" id="food_for" class="form-control form-control-modal food_for">
              <option value="">Select Food For</option>
              <option value="lunch">Lunch</option>
              <option value="dinner">Dinner</option>
          </select>
      </div>
      <div class="col-md-3">
           <label for="no_of_pax" class="control-label"><b>No. of Pax :</b></label>
           <select name="no_of_pax" id="no_of_pax" class="form-control form-control-modal no_of_pax">
              <option value="">Select No. of Pax</option>
              <?php
              for($i=0;$i<=50;$i++)
              {
                echo '<option value="'.$i.'">'.$i.'</option>';
              }
              ?>
          </select>
      </div>

      <div class="col-md-3">
          <button class="btn btn-primary include_restaurant" id="include_restaurant">INLCUDE</button>
      </div>
  </div>
</div>
</div>

</div>
</div>

<?php $_SESSION['itinerary_name'] = $result_data['itinerary']['itinerary_name']; ?>
<?php $_SESSION['itinerary_tour_description'] = $result_data['itinerary']['itinerary_tour_description'] ?>
<?php $_SESSION['country_id'] = $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['country_id'] ?>
<?php $_SESSION['city_id'] = $result_data['itinerary']['itinerary_details_day_wise'][$days_count]['city_id'] ?>
<?php $days_count =  $result_data['itinerary']['itinerary_tour_days']+1; ?>
<?php  $_SESSION['itinerary_tour_days']= $days_count;
$_SESSION['itinerary_tour_id'] = $_GET['id'];
$_SESSION['total_cost'] =  $total_cost;

?>


<input type="hidden" value="<?php json_encode($get_hotel_images);?>" id="img_data_h">
<input type="hidden" id="driver_vch_type" value="">	
<input type="hidden" id="driver_vehicle" value="">	
<input type="hidden" id="driver_supplier_id" value="">	
<script>    
    // var enabledates = "<?php 
    // echo $result_data['enabled_dates'] 
    ?>";
    // var enableDays = enabledates.split(', ')

    jQuery(document).on("click",".view_driver,.view_driver_btn",function(e)
    {
       // var driver_vch_type = jQuery('#driver_vch_type').val();
       // var driver_vehicle = jQuery('#driver_vehicle').val();
       // var driver_supplier_id = jQuery('#driver_supplier_id').val();
    //    if(driver_vch_type == "")
    //    {
    //     var driver_vch_type = 1;
    //     var driver_vehicle = 8;
    //     var driver_supplier_id = 40;

    // }
			// alert(driver_supplier_id);
           if(e.target.className=="view_driver_btn")
           {
             var driverid=jQuery(this).attr("id").split("_")[1];
             var vehicle_type="";
           }
           else
           {
             var driverid=jQuery(this).attr("id").split("_")[2];
             var day_count=jQuery(this).attr("id").split("_")[1];
             var vehicle_type=jQuery(".transfer_vehicle_type"+day_count).val();
           }
            
           var data = {
            'action': 'fetchItineraryDriversDetails',
            'driver_id':driverid,
            'vehicle_type':vehicle_type
            };
            jQuery("span.tourmaster-user-top-bar-loader").trigger("click");
            jQuery.post(the_ajax_script.ajaxurl, data, function(response) {
             jQuery(".loaderClose").trigger("click");
             jQuery(".driver_details_div").html(response);
             jQuery("span.tourmaster-user-top-bar-driver").trigger("click");

         })

        });

     jQuery(document).on("click",".view_guide,.view_guide_btn",function(e)
    {
           if(e.target.className=="view_guide_btn")
           {
             var id=jQuery(this).attr("id").split("_")[1];
           }
           else
           {
             var id=jQuery(this).attr("id").split("_")[2];
           }
            
            var data = {
            'action': 'fetchItineraryGuideDetails',
            'guide_id':id};
            jQuery("span.tourmaster-user-top-bar-loader").trigger("click");
            jQuery.post(the_ajax_script.ajaxurl, data, function(response) {
             jQuery(".loaderClose").trigger("click");
             jQuery(".guide_details_div").html(response);
             jQuery("span.tourmaster-user-top-bar-guide").trigger("click");

         });

        });
    jQuery(document).ready(function()
    {
  //     jQuery(".seted_view_gide").click(function(){
  //         var id = jQuery('#selected_transfer_guide_id').val();
		// // if(id == "")
		// // {
		// 	// var id = jQuery('#selelted_guide_already').val();
		// 	// if(id = "")
		// 	// {
		// 		// var id = 1;
		// 	// }
		// // }
		// if(id == "")
		// {
		// 	alert('Please Select Guide')
		// }
		
  //       else{            
		// var data = {
  //           'action': 'fetchItineraryGuideDetails',
  //           'guide_id':id};
  //           jQuery("span.tourmaster-user-top-bar-loader").trigger("click");
  //           jQuery.post(the_ajax_script.ajaxurl, data, function(response) {
  //            jQuery(".loaderClose").trigger("click");
  //            jQuery(".guide_details_div").html(response);
  //            jQuery("span.tourmaster-user-top-bar-guide").trigger("click");

  //        });
  //       }
  //       });

  //       jQuery(".seted_view_gide_transfer").click(function(){
  //         var id = jQuery(this).val();
		// // if(id == "")
		// // {
		// 	// var id = jQuery('#selelted_guide_already').val();
		// 	// if(id = "")
		// 	// {
		// 		// var id = 1;
		// 	// }
		// // }
		// if(id == "")
		// {
		// 	alert('Please Select Guide')
		// }
		// else{
		// var data = {
  //           'action': 'fetchItineraryGuideDetails',
  //           'guide_id':id};
  //           jQuery("span.tourmaster-user-top-bar-loader").trigger("click");
  //           jQuery.post(the_ajax_script.ajaxurl, data, function(response) {
  //            jQuery(".loaderClose").trigger("click");
  //            jQuery(".guide_details_div").html(response);
  //            jQuery("span.tourmaster-user-top-bar-guide").trigger("click");

  //        });
  //       }
  //       });


      jQuery(".forpdf").click(function(){
         var formdata=jQuery("#traveldoor-activity-booking-fields").serialize();

         var change_date_from = jQuery('#selected_transfer_guide_id').val();

         var select_adults = jQuery('.select_adults').val();
         var select_child = jQuery('.select_child').val();
         var country_id_book = jQuery('.country_id_book').val();
         var city_id_book = jQuery('.city_id_book').val();
         var gettour_info = jQuery('.tourpack_info').html();
         var packtour_desc = jQuery('.packtour_desc').html();
         var total_cost = jQuery('#total_cost_text').html();
         var itinerary_id = "<?php echo $_GET['id'];?>";
         var itinerary_exclusions = jQuery('.gettourexcl').html();
         var itinerary_terms_and_conditions = jQuery('.gettourtermsC').html();
         var itinerary_cancellation = jQuery('.gettourtermsCancel').html();
         var itinerary_tour_days = "<?php echo ($result_data['itinerary']['itinerary_tour_days']+1) ?>";
         var add_this_for_itinerary_validity_operation_to = "<?php echo ($result_data['itinerary']['itinerary_tour_days']+1) ?>";




         jQuery.ajax({
             type: 'POST',
             url: "<?php echo get_site_url(); ?>/wp-content/plugins/activities/itinerary/send.php?total="+total_cost,
			//contentType: "application/json",
            type:"POST",
            data: formdata,
			//dataType: 'json',
			success: function(data)
			{

				// window.open(data, '_tab','download=download');
				// window.open(
				  // data,
				  // '_blank' // <- This is what makes it open in a new window.
				// );

    //             console.log(data)
				// window.location=(data);
             var link = document.createElement('a');
             link.setAttribute("id", "downloadPackage");
             link.setAttribute("href",data);
             link.setAttribute("download","download");
              document.body.appendChild(link);
             setTimeout(function()
             {
              link.click();
              link.remove()  
          },2000);


				//console.log(data);
				
            },
            failure: function(errMsg) {
                alert("there was an error!");
            },
        });


     });
  });
    // jQuery(document).ready(function()
    // {
        // jQuery(".tourmaster-datepicker").datepicker({
        //    dateFormat: 'yy-mm-dd',
        //    minDate: '0',
        //    beforeShowDay: function(date){
        //     var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        //     return [ enableDays.indexOf(string) != -1 ]
        // }
    // });
    // });

    jQuery(document).ready(function()
    {

       var coll = document.getElementsByClassName("collapsible");
       var i;

       for (i = 0; i < coll.length; i++) {
          coll[i].addEventListener("click", function() {
            this.classList.toggle("active1");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
              content.style.maxHeight = null;
          } else {
    	   // content.style.maxHeight = parseInt(content.scrollHeight+50) + "px";
         content.style.maxHeight = "none";
     }
 });
      }

      jQuery("#calculate_cost").trigger("click");

      jQuery("#warning_count").val("<?php echo $warning_count; ?>")



      jQuery('#traveldoor-activity-booking-fields').submit(function(e)
      {
        var warning=jQuery("#warning_count").val();
        if(warning<=0)
        {
         var total_guests=parseInt(parseInt(jQuery(".select_adults").val())+parseInt(jQuery(".select_child").val()));
           // console.log(total_guests)

           var occupancy_error_count=0;
           let hotel_occupancy_counter=0;
           var hotel_id_counter=0;
           jQuery(".hotels_select").each(function()
           {
            hotel_id_counter=jQuery(this).attr("id").split("__")[1]
            hotel_occupancy_counter=0;
            jQuery(this).find(".hotel_occupancy_qty").each(function()
            {
                var this_occupany_id_index=jQuery(this).attr("id").split("__")[2];
                var room_selected_qty=parseInt(jQuery("#hotel_room_qty__"+hotel_id_counter+"__"+this_occupany_id_index).val())
                var total_pax_count=parseInt(parseInt(jQuery(this).val())*room_selected_qty)      
                hotel_occupancy_counter+=total_pax_count
            })


            if(total_guests!=hotel_occupancy_counter)
            {  
                occupancy_error_count++;

                jQuery(".collapsible:not(.active1)").trigger("click");
                if(!jQuery("#change_hotel__"+hotel_id_counter).attr('style'))
                {
                    jQuery("#hotels_select__"+hotel_id_counter).find('.change_hotel_room').css({'outline': '5px solid red','padding':'0px 8px'})
                    jQuery("#hotels_select__"+hotel_id_counter).find('.change_hotel_room_message').remove()
                    jQuery("#hotels_select__"+hotel_id_counter).find('.change_hotel_room').before("<p style='color:red' class='change_hotel_room_message' id='change_hotel_room_message__"+hotel_id_counter+"'>Please Change Room</p>")
                }

                jQuery('html, body').animate({ scrollTop: jQuery("#hotels_select__"+hotel_id_counter).offset().top-200 }, 'slow');
                // jQuery("span.tourmaster-user-top-bar-error").trigger("click");
                // jQuery(".error_text_title").text("Error");
                // jQuery(".error_text").text("Please Change the Room Selection in hotel on Day "+hotel_id_counter);
                // alert("Please Change the Room Selection in hotel on Day "+hotel_id_counter)
                jQuery("#myErrorModalBtn").trigger("click");
                jQuery(".errors_text").text("Please Change the Room Selection in hotel on Day "+hotel_id_counter);
                jQuery(".errors_text_title").text("Notice");
                return false;
            }
        });
           var transfer_error_count=0;
           // let transfer_occupancy_counter=0;
           var transfer_id_counter=0;

           
           jQuery(".transfer_select").each(function()
           {
               transfer_id_counter=jQuery(this).attr("id").split("__")[1]
            // transfer_occupancy_counter=0;

            var vehicle_min=jQuery("#transfer_vehicle_min__"+transfer_id_counter).val();
            var vehicle_max=jQuery("#transfer_vehicle_max__"+transfer_id_counter).val();
                // alert(vehicle_min);
                if(vehicle_min<total_guests && vehicle_max<total_guests)
                {  
                    transfer_error_count++;
                    jQuery("#change_transfer__"+transfer_id_counter).css({'outline': '5px solid red','padding':'0px 8px'})

                    jQuery(".collapsible:not(.active1)").trigger("click");
                    jQuery('html, body').animate({ scrollTop: jQuery("#transfer_select__"+transfer_id_counter).offset().top-200 }, 'slow');
                    // jQuery("span.tourmaster-user-top-bar-error").trigger("click");
                    // jQuery(".error_text_title").text("Error");
                    // jQuery(".error_text").text("Please Change the Transfer Vehicle on Day "+transfer_id_counter);

                    // alert("Please Change the Transfer Vehicle on Day "+transfer_id_counter);

                    jQuery("#myErrorModalBtn").trigger("click");
                    jQuery(".errors_text").text("Please Change the Transfer Vehicle on Day "+transfer_id_counter);
                    jQuery(".errors_text_title").text("Notice");
                    return false;
                }
            });



           var sightseeing_error_count=0;
           // let transfer_occupancy_counter=0;
           var sightseeing_id_counter=0;

           
           jQuery(".sightseeing_select").each(function()
           {
               sightseeing_id_counter=jQuery(this).attr("id").split("__")[1]
            // transfer_occupancy_counter=0;
            if(jQuery("#sightseeing_id__"+sightseeing_id_counter).val()!="")
            {
                var vehicle_min=jQuery("#sightseeing_vehicle_min__"+sightseeing_id_counter).val();
                var vehicle_max=jQuery("#sightseeing_vehicle_max__"+sightseeing_id_counter).val();

                var guide_cost=jQuery("#sightseeing_guide_cost__"+sightseeing_id_counter).val();
                var driver_cost=jQuery("#sightseeing_driver_cost__"+sightseeing_id_counter).val();
                // alert(vehicle_min);
                if(vehicle_min<total_guests && vehicle_max<total_guests)
                {  
                    sightseeing_error_count++;
                    jQuery("#change_sightseeing__"+sightseeing_id_counter).css({'outline': '5px solid red','padding':'0px 8px'})

                    jQuery(".collapsible:not(.active1)").trigger("click");
                    jQuery('html, body').animate({ scrollTop: jQuery("#sightseeing_select__"+sightseeing_id_counter).offset().top-200 }, 'slow');
                    jQuery("#myErrorModalBtn").trigger("click");
                    jQuery(".errors_text").text("Please Change the Daily Tour Vehicle on Day "+sightseeing_id_counter);
                    jQuery(".errors_text_title").text("Notice");
                } 
                

                if((guide_cost==0 || guide_cost=="") && (driver_cost==0 || driver_cost=="") )
                {
                    sightseeing_error_count++;
                    jQuery("#change_sightseeing__"+sightseeing_id_counter).css({'outline': '5px solid red','padding':'0px 8px'})

                    jQuery(".collapsible:not(.active1)").trigger("click");
                    jQuery('html, body').animate({ scrollTop: jQuery("#sightseeing_select__"+sightseeing_id_counter).offset().top-200 }, 'slow');
                    // alert("Please Change the Daily Tour Guide and Driver on Day "+sightseeing_id_counter);

                    jQuery("#myErrorModalBtn").trigger("click");
                    jQuery(".errors_text").text("Please Change the Daily Tour Guide and Driver on Day "+sightseeing_id_counter);
                    jQuery(".errors_text_title").text("Notice");

                } 
                else if(guide_cost==0 || guide_cost=="")
                {
                    sightseeing_error_count++;
                    jQuery("#change_sightseeing__"+sightseeing_id_counter).css({'outline': '5px solid red','padding':'0px 8px'})

                    jQuery(".collapsible:not(.active1)").trigger("click");
                    jQuery('html, body').animate({ scrollTop: jQuery("#sightseeing_select__"+sightseeing_id_counter).offset().top-200 }, 'slow');
                    // alert("Please Change the Daily Tour Guide on Day "+sightseeing_id_counter);

                    jQuery("#myErrorModalBtn").trigger("click");
                    jQuery(".errors_text").text("Please Change the Daily Tour Guide on Day "+sightseeing_id_counter);
                    jQuery(".errors_text_title").text("Notice");
                    
                } 
                else if(driver_cost==0 || driver_cost=="")
                {
                    sightseeing_error_count++;
                    jQuery("#change_sightseeing__"+sightseeing_id_counter).css({'outline': '5px solid red','padding':'0px 8px'})

                    jQuery(".collapsible:not(.active1)").trigger("click");
                    jQuery('html, body').animate({ scrollTop: jQuery("#sightseeing_select__"+sightseeing_id_counter).offset().top-200 }, 'slow');
                    // alert("Please Change the Daily Tour Driver on Day "+sightseeing_id_counter);

                    jQuery("#myErrorModalBtn").trigger("click");
                    jQuery(".errors_text").text("Please Change the Daily Tour Driver on Day "+sightseeing_id_counter);
                    jQuery(".errors_text_title").text("Notice");

                } 



                if(vehicle_min<total_guests && vehicle_max<total_guests)
                {
                    return false;
                }



                if((guide_cost==0 || guide_cost=="") && (driver_cost==0 || driver_cost=="") )
                {
                    return false;
                } 
                else if(guide_cost==0 || guide_cost=="")
                {
                    return false;
                } 
                else if(driver_cost==0 || driver_cost=="")
                {
                    return false;
                } 
            }


        });

         // alert(occupancy_error_count);
         // alert(transfer_error_count);
         if(occupancy_error_count>0 || transfer_error_count>0 || sightseeing_error_count>0)
         {
             e.preventDefault();
         }  
     }
     else
     {
        e.preventDefault();
        var warning_section_id="";
        jQuery(".warning_text").each(function()
        {
            warning_section_id=jQuery(this).attr("id")
            return false;
        })

        jQuery(".collapsible:not(.active1)").trigger("click");
            // jQuery(".collapsible .active1").trigger("click");
             // jQuery("span.tourmaster-user-top-bar-error").trigger("click");
             //  jQuery(".error_text_title").text("Error");
             //  jQuery(".error_text").text("Please Change those services that are not available on selected dates");
             console.log(warning_section_id)
             jQuery('html, body').animate({ scrollTop: jQuery('#'+warning_section_id).offset().top-200 }, 'slow');
              // alert("Please Change those services that are not available on selected dates");
              // alert(warning_section_id);

              jQuery("#myErrorModalBtn").trigger("click");
              jQuery(".errors_text").text("Please Change those services that are not available on selected dates");
              jQuery(".errors_text_title").text("Notice");
              

          }
      });


})
</script>


<script>

 // now you can use jQuery code here with $ shortcut formatting
    // this will execute after the document is fully loaded
    // anything that interacts with your html should go here

    // jQuery(document).ready(function()
    // {
    //    setTimeout(function()
    //    {
    //       jQuery("span.tourmaster-user-top-bar-login").trigger("click");
    //   },2000);
    // })


    jQuery(document).on("click",".tourmaster-button",function()
    {
        jQuery(".collapsible:not(.active1)").trigger("click");
    })
</script>
<?php
}
else
{
  ?>
  <div class="single-tour">
    <div class="traveltour-page-wrapper" id="traveltour-page-wrapper">
        <div class="tourmaster-single-header"
        style="background-image: url(<?php echo get_site_url(); ?>/wp-content/uploads/2018/07/single-top-bg.jpg);">
        <div class="tourmaster-single-header-background-overlay"></div>
        <div class="tourmaster-single-header-top-overlay"></div>
        <div class="tourmaster-single-header-container tourmaster-container">
            <div class="tourmaster-single-header-container-inner">
                <div class="tourmaster-single-header-title-wrap tourmaster-item-pdlr"></div>
            </div>
        </div>
    </div>
    <div class="tourmaster-template-wrapper">
       <div class="tourmaster-single-tour-content-wrap">
        <div class="gdlr-core-page-builder-body">
            <div class="gdlr-core-pbf-wrapper">
                <div class="gdlr-core-pbf-background-wrap"></div>
                <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
                    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">

                        <div class="gdlr-core-pbf-element">
                            <div
                            class="tourmaster-tour-title-item tourmaster-item-pdlr tourmaster-item-pdb clearfix">
                            <?php
                            if(isset($_GET['source']))
                            {

                                $start_date =  $_SESSION['itinerary_date_from'];
                                $total_days = $result_data['itinerary']['itinerary_tour_days'];

                                ?>

                                <div class="row main-i-row">
                                    <div class="col-md-6" style="padding: 20px;">
                                        <label for="package_date" class="pkg-label"><b class="package-start-date">Package Start Date</b></label><br>

                                    </div>
                                    <div class="col-md-9" style="padding: 20px;">
                                    <input type="text" id="change_date_from" class="tourmaster-datepicker form-control-modal" value="<?php echo $_SESSION['itinerary_date_from']; ?>" />
                                        <!-- <input type="date" id="change_date_from" min="<?php echo date('Y-m-d'); ?>" class="form-control-modal" value="<?php echo $_SESSION['itinerary_date_from']; ?>"> -->
                                        <a style="pointer-events: none;cursor: default;"  id="change_date_link" href="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>"> <button type="button" class="btn btn-primary pkg-btn" id="change_date_button"  style="background-color:#DCDCDC;
                                        pointer-events: none;cursor: not-allowed">
                                        CHANGE DATE</button></a>

                                    </div>
                                     <div class="col-md-3 Edate">
                                            <label for="endDate" class="pkg-label"><b class="package-start-date">Package End Date</b></label><br>
                                        </div>
                                        <div class="col-md-7 Edate">
                                        <input type="text" id="EndDate" class="tourmaster-datepicker" value="<?php echo date('Y-m-d', strtotime($start_date. ' + '.$total_days.' days'));?> " class="form-control-modal EndDate" disabled>
                                        </div>
                                </div>

                                <script>
                                    jQuery(document).ready(function() {

                                        jQuery(document).on("change","#change_date_from",function()
                                        {   
                                            alert()
                                            var date=jQuery(this).val();
                                            var url="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>";
                                            jQuery('#change_date_link').attr("href",url+"&date_from="+date);
                                            jQuery("#change_date_button").removeAttr("style");
                                        })
                                    })
                                </script>
                                <?php
                            }
                            else
                            {   

                                $start_date =  $_SESSION['itinerary_date_from'];
                                $total_days = $result_data['itinerary']['itinerary_tour_days'];
                                ?>

                                <div class="row main-i-row">
                                    <div class="col-md-3" style="padding: 20px;">
                                        <label for="package_date" class="pkg-label"><b class="package-start-date">Package Start Date</b></label><br>

                                    </div>
                                    <div class="col-md-9" style="padding: 20px;">
                                        <input type="text" id="change_date_from" min="<?php echo date('Y-m-d'); ?>" class="form-control-modal tourmaster-datepicker" value="<?php echo $_SESSION['itinerary_date_from']; ?>">
                                        <a style="pointer-events: none;cursor: default;"  id="change_date_link" href="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>"> <button type="button" class="btn btn-primary pkg-btn" id="change_date_button"  style="background-color:#DCDCDC;
                                        pointer-events: none;cursor: not-allowed">
                                        CHANGE DATE</button></a>

                                    </div>
                                    <div class="col-md-3 Edate">
                                        <label for="endDate" class="pkg-label"><b class="package-start-date">Package End Date</b></label><br>
                                    </div>
                                    <div class="col-md-7 Edate">
                                    <input type="text" id="EndDate" class="tourmaster-datepicker" value="<?php echo date('Y-m-d', strtotime($start_date. ' + '.$total_days.' days'));?> " class="form-control-modal EndDate" disabled>   
                                    </div>
                                </div>

                                <script>
                                    jQuery(document).ready(function() {

                                        jQuery(document).on("change","#change_date_from",function()
                                        {   
                                            alert()
                                            var date=jQuery(this).val();
                                            var url="<?php echo site_url(); ?>/itinerary-details/?id=<?php echo $itinerary_id; ?>";
                                            jQuery('#change_date_link').attr("href",url+"&date_from="+date);
                                            jQuery("#change_date_button").removeAttr("style");
                                        })
                                    })
                                </script>

                                <?php
                            }

                            ?>
                            <br>
                            <h3 class="text-center">Packages not available for selected date</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<?php
}
?>
<script>
jQuery(document).ready(function(){

    // jQuery(".tourmaster-datepicker").datepicker({
    //        dateFormat: 'yy-mm-dd',
    //        minDate: 0,
    //    });


 jQuery("#change_date_from").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '0',
         onSelect: function(date){
        var total_days = jQuery('#total_days').val();
        var selectedDate = new Date(date);
        var msecsInADay = (total_days-1) * 86400000;
        var endDate = new Date(selectedDate.getTime() + msecsInADay);

       //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
        jQuery("#EndDate").datepicker( "option", "minDate", endDate );
        jQuery("#EndDate").datepicker("setDate", endDate);
        jQuery("#change_date_button").removeAttr("style");
        jQuery("#change_date_link").removeAttr("style");    
    	}
        });
  

    jQuery("#EndDate").datepicker({
         dateFormat: 'yy-mm-dd',
        minDate: '0'
    })



});

        </script>

<?php
}

add_shortcode('show_itinerary_detail', 'itinerary_details');

function itinerary_booking()
{
    if(!is_user_logged_in())
    {
        ?>
        <script>
          jQuery(document).ready(function()
          {
             setTimeout(function()
             {
              jQuery("span.tourmaster-user-top-bar-login").trigger("click");
          },2000);

             jQuery(document).on("submit","#traveldoor-activity-confirm-booking",function(e)
             {
                e.preventDefault();
                jQuery("span.tourmaster-user-top-bar-login").trigger("click");
            })
         });
     </script>
     <?php
 }

 // print_r($_POST);
 $itinerary_details=$_SESSION['itinerary_details'];

 if(!empty($_POST))
 {

  $_SESSION['itinerary_booking_fields']=$_POST;
  $booking_dates_array=$_POST['days_dates'];
  $booking_details_array=array();
  $booking_markup_per=$_POST['markup_per'];
  $hotel_id=$_POST['hotel_id'];
  $hotel_name=$_POST['hotel_name'];
  $room_name=$_POST['room_name'];
  $rooms_qty=$_POST['room_qty'];
  $hotel_cost=$_POST['hotel_cost'];
  $hotel_no_of_days=$_POST['hotel_no_of_days'];
  $hotel_checkin=$_POST['hotel_checkin'];
  $hotel_checkout=$_POST['hotel_checkout'];
  $hotel_room_name=$_POST['hotel_room_name'];
  $hotel_room_qty=$_POST['hotel_room_qty'];
  $hotel_room_cost=$_POST['hotel_room_cost'];
  $hotel_room_id=$_POST['hotel_room_id'];
  $hotel_occupancy_id=$_POST['hotel_occupancy_id'];
  $hotel_occupancy_qty=$_POST['hotel_occupancy_qty'];

  $sightseeing_id=$_POST['sightseeing_id'];
  $sightseeing_name=$_POST['sightseeing_name'];
  $sightseeing_tour_type=$_POST['sightseeing_tour_type'];
  $sightseeing_vehicle_type=$_POST['sightseeing_vehicle_type'];
  $sightseeing_guide_id=$_POST['sightseeing_guide_id'];
  $sightseeing_guide_name=$_POST['sightseeing_guide_name']; 
  $sightseeing_guide_cost=$_POST['sightseeing_guide_cost'];
  $sightseeing_driver_id=$_POST['sightseeing_driver_id'];  
  $sightseeing_driver_name=$_POST['sightseeing_driver_name']; 
  $sightseeing_driver_cost=$_POST['sightseeing_driver_cost'];
  $sightseeing_adult_cost=$_POST['sightseeing_adult_cost'];
  $sightseeing_additional_cost=$_POST['sightseeing_additional_cost'];
  $sightseeing_cost=$_POST['sightseeing_cost'];

  $activity_id=$_POST['activity_id'];
  $activity_name=$_POST['activity_name'];
  $activity_cost=$_POST['activity_cost'];

  $transfer_id=$_POST['transfer_id'];
  $transfer_name=$_POST['transfer_name'];
  $transfer_car_name=$_POST['transfer_car_name'];
  $transfer_type=$_POST['transfer_type'];
  $transfer_from_city=$_POST['transfer_from_city'];
  $transfer_to_city=$_POST['transfer_to_city'];
  $transfer_from_airport=$_POST['transfer_from_airport'];
  $transfer_to_airport=$_POST['transfer_to_airport'];
  $transfer_pickup=$_POST['transfer_pickup'];
  $transfer_dropoff=$_POST['transfer_dropoff'];
  $transfer_vehicle_type=$_POST['transfer_vehicle_type'];
  $transfer_guide_id=$_POST['transfer_guide_id'];
  $transfer_guide_name=$_POST['transfer_guide_name'];
  $transfer_guide_cost=$_POST['transfer_guide_cost'];
  $transfer_cost=$_POST['transfer_cost'];
  $transfer_total_cost=$_POST['transfer_total_cost'];

  $restaurant_id=$_POST['restaurant_id'];
  $restaurant_name=$_POST['restaurant_name'];
  $restaurant_cost=$_POST['restaurant_cost'];
  $restaurant_pax_count=$_POST['restaurant_pax_count'];
  $restaurant_food_for=$_POST['restaurant_food_for'];
  $restaurant_food_time=$_POST['restaurant_food_time'];
  $restaurant_food_name=$_POST['restaurant_food_name'];
  $restaurant_food_qty=$_POST['restaurant_food_qty'];
  $restaurant_food_price=$_POST['restaurant_food_price'];
  $restaurant_food_id=$_POST['restaurant_food_id'];
  $restaurant_food_category_id=$_POST['restaurant_food_category_id'];
  $restaurant_food_unit=$_POST['restaurant_food_unit'];


  $rooms_count=$_POST['rooms_count'];
  $select_adults=$_POST['select_adults'];
  $select_child=$_POST['select_child'];
  $child_age=$_POST['child_age'];

  $no_of_adults=array_sum($select_adults);
  $no_of_child=array_sum($select_child);

  if(isset($_POST['child_age']))
  {
   $child_age_array=serialize($child_age);
}
else
{
    $child_age=array();
    $child_age_array=serialize($child_age);

}


$activities_select_adults=$_POST['activities_select_adults'];
$activities_select_child=$_POST['activities_select_child'];
$activities_select_child_age=$_POST['activities_select_child_age'];   
}
else
{
 $booking_dates_array= $_SESSION['itinerary_booking_fields']['days_dates'];

 $booking_details_array=array();
 $booking_markup_per= $_SESSION['itinerary_booking_fields']['markup_per'];
 $hotel_id=$_SESSION['itinerary_booking_fields']['hotel_id'];
 $hotel_name=$_SESSION['itinerary_booking_fields']['hotel_name'];
 $room_name=$_SESSION['itinerary_booking_fields']['room_name'];
 $rooms_qty=$_SESSION['itinerary_booking_fields']['room_qty'];
 $hotel_cost=$_SESSION['itinerary_booking_fields']['hotel_cost'];
 $hotel_no_of_days=$_SESSION['itinerary_booking_fields']['hotel_no_of_days'];
 $hotel_checkin=$_SESSION['itinerary_booking_fields']['hotel_checkin'];
 $hotel_checkout=$_SESSION['itinerary_booking_fields']['hotel_checkout'];
 $hotel_room_name=$_SESSION['itinerary_booking_fields']['hotel_room_name'];
 $hotel_room_qty=$_SESSION['itinerary_booking_fields']['hotel_room_qty'];
 $hotel_room_cost=$_SESSION['itinerary_booking_fields']['hotel_room_cost'];
 $hotel_room_id=$_SESSION['itinerary_booking_fields']['hotel_room_id'];
 $hotel_occupancy_id=$_SESSION['itinerary_booking_fields']['hotel_occupancy_id'];
 $hotel_occupancy_qty=$_SESSION['itinerary_booking_fields']['hotel_occupancy_qty'];

 $sightseeing_id=$_SESSION['itinerary_booking_fields']['sightseeing_id'];
 $sightseeing_name=$_SESSION['itinerary_booking_fields']['sightseeing_name'];
 $sightseeing_tour_type=$_SESSION['itinerary_booking_fields']['sightseeing_tour_type'];
 $sightseeing_vehicle_type=$_SESSION['itinerary_booking_fields']['sightseeing_vehicle_type'];
 $sightseeing_guide_id=$_SESSION['itinerary_booking_fields']['sightseeing_guide_id'];
 $sightseeing_guide_name=$_SESSION['itinerary_booking_fields']['sightseeing_guide_name']; 
 $sightseeing_guide_cost=$_SESSION['itinerary_booking_fields']['sightseeing_guide_cost'];
 $sightseeing_driver_id=$_SESSION['itinerary_booking_fields']['sightseeing_driver_id'];  
 $sightseeing_driver_name=$_SESSION['itinerary_booking_fields']['sightseeing_driver_name']; 
 $sightseeing_driver_cost=$_SESSION['itinerary_booking_fields']['sightseeing_driver_cost'];
 $sightseeing_adult_cost=$_SESSION['itinerary_booking_fields']['sightseeing_adult_cost'];
 $sightseeing_additional_cost=$_SESSION['itinerary_booking_fields']['sightseeing_additional_cost'];
 $sightseeing_cost=$_SESSION['itinerary_booking_fields']['sightseeing_cost'];

 $activity_id=$_SESSION['itinerary_booking_fields']['activity_id'];
 $activity_name=$_SESSION['itinerary_booking_fields']['activity_name'];
 $activity_cost=$_SESSION['itinerary_booking_fields']['activity_cost'];

 $transfer_id=$_SESSION['itinerary_booking_fields']['transfer_id'];
 $transfer_name=$_SESSION['itinerary_booking_fields']['transfer_name'];
 $transfer_car_name=$_SESSION['itinerary_booking_fields']['transfer_car_name'];
 $transfer_type=$_SESSION['itinerary_booking_fields']['transfer_type'];
 $transfer_from_city=$_SESSION['itinerary_booking_fields']['transfer_from_city'];
 $transfer_to_city=$_SESSION['itinerary_booking_fields']['transfer_to_city'];
 $transfer_from_airport=$_SESSION['itinerary_booking_fields']['transfer_from_airport'];
 $transfer_to_airport=$_SESSION['itinerary_booking_fields']['transfer_to_airport'];
 $transfer_pickup=$_SESSION['itinerary_booking_fields']['transfer_pickup'];
 $transfer_dropoff=$_SESSION['itinerary_booking_fields']['transfer_dropoff'];
 $transfer_vehicle_type=$_SESSION['itinerary_booking_fields']['transfer_vehicle_type'];
 $transfer_guide_id=$_SESSION['itinerary_booking_fields']['transfer_guide_id'];
 $transfer_guide_name=$_SESSION['itinerary_booking_fields']['transfer_guide_name'];
 $transfer_guide_cost=$_SESSION['itinerary_booking_fields']['transfer_guide_cost'];
 $transfer_cost=$_SESSION['itinerary_booking_fields']['transfer_cost'];
 $transfer_total_cost=$_SESSION['itinerary_booking_fields']['transfer_total_cost'];

 $restaurant_id=$_SESSION['itinerary_booking_fields']['restaurant_id'];
 $restaurant_name=$_SESSION['itinerary_booking_fields']['restaurant_name'];
 $restaurant_food_for=$_SESSION['itinerary_booking_fields']['restaurant_food_for'];
 $restaurant_food_time=$_SESSION['itinerary_booking_fields']['restaurant_food_time'];
 $restaurant_pax_count=$_SESSION['itinerary_booking_fields']['restaurant_pax_count'];
 $restaurant_cost=$_SESSION['itinerary_booking_fields']['restaurant_cost'];
 $restaurant_food_name=$_SESSION['itinerary_booking_fields']['restaurant_food_name'];
 $restaurant_food_qty=$_SESSION['itinerary_booking_fields']['restaurant_food_qty'];
 $restaurant_food_price=$_SESSION['itinerary_booking_fields']['restaurant_food_price'];
 $restaurant_food_id=$_SESSION['itinerary_booking_fields']['restaurant_food_id'];
 $restaurant_food_category_id=$_SESSION['itinerary_booking_fields']['restaurant_food_category_id'];
 $restaurant_food_unit=$_SESSION['itinerary_booking_fields']['restaurant_food_unit'];


 $rooms_count=$_SESSION['itinerary_booking_fields']['rooms_count'];
 $select_adults=$_SESSION['itinerary_booking_fields']['select_adults'];
 $select_child=$_SESSION['itinerary_booking_fields']['select_child'];
 $child_age=$_SESSION['itinerary_booking_fields']['child_age'];

 $no_of_adults=array_sum($select_adults);
 $no_of_child=array_sum($select_child);

 if(isset($_SESSION['itinerary_booking_fields']['child_age']))
 {
   $child_age_array=serialize($child_age);
}
else
{
    $child_age=array();
    $child_age_array=serialize($child_age);

}


$activities_select_adults=$_SESSION['itinerary_booking_fields']['activities_select_adults'];
$activities_select_child=$_SESSION['itinerary_booking_fields']['activities_select_child'];
$activities_select_child_age=$_SESSION['itinerary_booking_fields']['activities_select_child_age'];
}


  // $activities_select_child_age=array_map('array_values', $activities_select_child_age);

$no_of_pax=$no_of_adults+$no_of_child;
$calculate_total_cost=0;
$total_rooms_count=0;


for($dates_count=0;$dates_count<=count($booking_dates_array)-1;$dates_count++)
{
  $booking_details_array[$dates_count]['dates']=$booking_dates_array[$dates_count];
  if(!empty($hotel_id[$dates_count]))
  {
    $booking_details_array[$dates_count]['hotel']['hotel_id']=$hotel_id[$dates_count];
    $booking_details_array[$dates_count]['hotel']['hotel_name']=$hotel_name[$dates_count];
    $booking_details_array[$dates_count]['hotel']['room_name']=$room_name[$dates_count];
    $booking_details_array[$dates_count]['hotel']['hotel_cost']=$hotel_cost[$dates_count];
    $booking_details_array[$dates_count]['hotel']['hotel_checkin']=$hotel_checkin[$dates_count];
    $booking_details_array[$dates_count]['hotel']['hotel_checkout']=$hotel_checkout[$dates_count];
    $booking_details_array[$dates_count]['hotel']['no_of_days']=$hotel_no_of_days[$dates_count];
    $booking_details_array[$dates_count]['hotel']['room_quantity']=$rooms_qty[$dates_count];

        // $calculate_total_cost+=$hotel_cost[$dates_count]*count($rooms_count);
    $total_rooms_count+=$rooms_qty[$dates_count];
    if(empty($hotel_room_qty[$dates_count]))
    {
      // $booking_details_array[$dates_count]['hotel']['hotel_cost']=$hotel_cost[$dates_count]*count($rooms_count);
      // $calculate_total_cost+=$hotel_cost[$dates_count]*count($rooms_count);

      $hotel_room_name_array=$hotel_room_name[$dates_count];
      $hotel_room_cost_array=$hotel_room_cost[$dates_count];
      $hotel_total_cost=0;
      for($i=0;$i<count($hotel_room_name_array);$i++)
      {

       $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_name']=$hotel_room_name_array[$i];
       $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_qty']=$rooms_qty[$dates_count];
       $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_cost']=$hotel_room_cost_array[$i];


       $hotel_total_cost+=$hotel_room_cost_array[$i]*$rooms_qty[$dates_count];

   }
   $booking_details_array[$dates_count]['hotel']['hotel_cost']=$hotel_total_cost;
   $calculate_total_cost+=$hotel_total_cost;


}
else
{
    $hotel_room_name_array=$hotel_room_name[$dates_count];
    $hotel_room_qty_array=$hotel_room_qty[$dates_count];
    $hotel_room_cost_array=$hotel_room_cost[$dates_count];
    $hotel_room_id_array=$hotel_room_id[$dates_count];
    $hotel_occupancy_id_array=$hotel_occupancy_id[$dates_count];
    $hotel_occupancy_qty_array=$hotel_occupancy_qty[$dates_count];
    $hotel_total_cost=0;
    for($i=0;$i<count($hotel_room_name_array);$i++)
    {

     $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_name']=$hotel_room_name_array[$i];
     $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_qty']=$hotel_room_qty_array[$i];
     $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_cost']=$hotel_room_cost_array[$i];
     $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_id']=$hotel_room_id_array[$i];
     $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_occupancy_id']=$hotel_occupancy_id_array[$i];
     $booking_details_array[$dates_count]['hotel']['hotel_room_detail'][$i]['room_occupancy_qty']=$hotel_occupancy_qty_array[$i];

     $hotel_total_cost+=$hotel_room_cost_array[$i]*$hotel_room_qty_array[$i];

 }
 $booking_details_array[$dates_count]['hotel']['hotel_cost']=$hotel_total_cost;
 $calculate_total_cost+=$hotel_total_cost;



}

}

      //   $booking_details_array[$dates_count]['sightseeing']['sightseeing_id']=$sightseeing_id[$dates_count];
      //   $booking_details_array[$dates_count]['sightseeing']['sightseeing_name']=$sightseeing_name[$dates_count];
      //   $booking_details_array[$dates_count]['sightseeing']['sightseeing_cost']=$sightseeing_cost[$dates_count];

      // $calculate_total_cost+=$sightseeing_cost[$dates_count]*$no_of_adults;

if(!empty($sightseeing_id[$dates_count]))
{
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_id"]=$sightseeing_id[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_name"]=$sightseeing_name[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_tour_type"]=$sightseeing_tour_type[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_vehicle_type"]=$sightseeing_vehicle_type[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_guide_id"]=$sightseeing_guide_id[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_guide_name"]=$sightseeing_guide_name[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_guide_cost"]=$sightseeing_guide_cost[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_driver_id"]=$sightseeing_driver_id[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_driver_name"]=$sightseeing_driver_name[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_driver_cost"]=$sightseeing_driver_cost[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_adult_cost"]=$sightseeing_adult_cost[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_additional_cost"]=$sightseeing_additional_cost[$dates_count];
  $booking_details_array[$dates_count]["sightseeing"]["sightseeing_cost"]=$sightseeing_cost[$dates_count];

  if($sightseeing_tour_type[$dates_count]=="private")
  {
    $calculate_total_cost+=$sightseeing_guide_cost[$dates_count];
    $calculate_total_cost+=$sightseeing_driver_cost[$dates_count];
    $calculate_total_cost+=$sightseeing_additional_cost[$dates_count];

    $calculate_total_cost+=$sightseeing_adult_cost[$dates_count]*$no_of_adults;
}
else
{
  $calculate_total_cost+=$sightseeing_additional_cost[$dates_count];
  $calculate_total_cost+=$sightseeing_adult_cost[$dates_count]*$no_of_adults;
}
}

if(!empty($transfer_id[$dates_count]))
{
  $booking_details_array[$dates_count]['transfer']['transfer_id']=$transfer_id[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_name']=$transfer_name[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_car_name']=$transfer_car_name[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_type']=$transfer_type[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_from_city']=$transfer_from_city[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_to_city']=$transfer_to_city[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_from_airport']=$transfer_from_airport[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_to_airport']=$transfer_to_airport[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_pickup']=$transfer_pickup[$dates_count];
  $booking_details_array[$dates_count]['transfer']['transfer_dropoff']=$transfer_dropoff[$dates_count];
  $booking_details_array[$dates_count]["transfer"]["transfer_vehicle_type"]=$transfer_vehicle_type[$dates_count];
  $booking_details_array[$dates_count]["transfer"]["transfer_guide_id"]=$transfer_guide_id[$dates_count];
  $booking_details_array[$dates_count]["transfer"]["transfer_guide_name"]=$transfer_guide_name[$dates_count];
  $booking_details_array[$dates_count]["transfer"]["transfer_guide_cost"]=$transfer_guide_cost[$dates_count];
  $booking_details_array[$dates_count]["transfer"]["transfer_cost"]=$transfer_cost[$dates_count];
  $booking_details_array[$dates_count]["transfer"]["transfer_total_cost"]=$transfer_total_cost[$dates_count];
      // $calculate_total_cost+=$transfer_cost[$dates_count]*$no_of_pax;
  $calculate_total_cost+=$transfer_total_cost[$dates_count];

}


if(!empty($activity_id[$dates_count]))
{
  if(!empty($activities_select_child_age[$dates_count]))
  {
   $activities_select_child_age_array=array_values($activities_select_child_age[$dates_count]);
} 
if(!empty($activities_select_child_age[$dates_count]))
{
   $activities_select_child_age_array=array_values($activities_select_child_age[$dates_count]);
}

for($activity_count=0;$activity_count<count($activity_id[$dates_count]);$activity_count++)
{
  if($activity_id[$dates_count][$activity_count]=="")
  { 
    continue;

}
if(!empty($activities_select_adults[$dates_count]))
{
   $booking_activity_adult_count=$activities_select_adults[$dates_count][$activity_count];
}
else
{
   $booking_activity_adult_count=0;
}

if(!empty($activities_select_child[$dates_count]))
{
  $booking_activity_child_count=$activities_select_child[$dates_count][$activity_count];
}
else
{
   $booking_activity_child_count=0;
}

if($booking_activity_child_count>0)
{
  if(!empty($activities_select_child_age_array[$activity_count]))
  {
     $booking_activities_select_child_age=$activities_select_child_age_array[$activity_count];
 }
 else
 {
    $booking_activities_select_child_age=array();
}
}
else
{
  $booking_activities_select_child_age=array();
}

$booking_details_array[$dates_count]['activity'][$activity_count]['activity_id']=$activity_id[$dates_count][$activity_count];
$booking_details_array[$dates_count]['activity'][$activity_count]['activity_name']=$activity_name[$dates_count][$activity_count];
$booking_details_array[$dates_count]['activity'][$activity_count]['activity_cost']=$activity_cost[$dates_count][$activity_count];
$booking_details_array[$dates_count]['activity'][$activity_count]['activity_no_of_adult']=$booking_activity_adult_count;
$booking_details_array[$dates_count]['activity'][$activity_count]['activity_no_of_child']=$booking_activity_child_count;
$booking_details_array[$dates_count]['activity'][$activity_count]['activity_child_age']=$booking_activities_select_child_age; 

   	    //API URL
$url = $GLOBALS["api_base_url"].'/api/activity/activityDetailView';

//create a new cURL resource
$ch = curl_init($url);

$data=array("activity_id"=>$activity_id[$dates_count][$activity_count]);
$activity= json_encode($data);

//attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $activity);
//set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
$result = curl_exec($ch);

//close cURL resource
curl_close($ch);
$result_data=json_decode($result,true);


$adult_price=0;

$adult_price_details=unserialize($result_data['activity']['adult_price_details']);
if(!empty($adult_price_details))
{
 $min_price=0;

 for($i=0;$i< count($adult_price_details);$i++)
 {

    if($adult_price_details[$i]['adult_min_pax']<=$booking_activity_adult_count && $adult_price_details[$i]['adult_max_pax']>=$booking_activity_adult_count)
    {
      $adult_price=$adult_price_details[$i]['adult_pax_price'];
      break;
  }

  if($adult_price_details[0]['adult_min_pax']>$booking_activity_adult_count)
  {
      $min_price++;
  }
}

if($adult_price==0 && $min_price>0)
{
 $adult_price=$adult_price_details[($i-1)]['adult_pax_price'];

}
else if($adult_price==0 && $min_price==0)
{
  if($i==0)
  {
     $adult_price=$adult_price_details[0]['adult_pax_price'];
 }
 else
 {
   $adult_price=$adult_price_details[($i-1)]['adult_pax_price'];
}
}

}
else
{
    $adult_price=0;
}



$child_price=0;

$child_price_details=unserialize($result_data['activity']['child_price_details']);
if(!empty($child_price_details))
{
   $min_price=0;
   for($i=0;$i< count($child_price_details);$i++)
   {

      if($child_price_details[$i]['child_min_pax']<=$booking_activity_child_count && $child_price_details[$i]['child_max_pax']>=$booking_activity_child_count)
      {
        $child_price=$child_price_details[$i]['child_pax_price'];
        break;
    }

    if($child_price_details[0]['child_min_pax']>$booking_activity_child_count)
    {
      $min_price++;
  }
}

if($child_price==0 && $min_price>0)
{
 $child_price=$child_price_details[0]['child_pax_price'];

}
else if($child_price==0 && $min_price==0)
{
 if($i==0)
 { 
   $child_price=$child_price_details[0]['child_pax_price'];
}
else
{
    $child_price=$child_price_details[($i-1)]['child_pax_price'];
}
}

}
else
{
  $child_price=0;
}






$supplier_adult_price=$adult_price;
$supplier_child_price=$child_price;
$booking_supplier_amount=0;
if(!empty($booking_activity_adult_count))
{
    $calculate_total_cost+=($booking_activity_adult_count*$supplier_adult_price);
}
if(!empty($booking_activity_child_count))
{
    $calculate_total_cost+=($booking_activity_child_count*$supplier_child_price);
}
}

}



if(isset($restaurant_id[$dates_count]))
{
    $restaurant_count=0;
    foreach($restaurant_id[$dates_count] as $restaurant_id_key =>$restaurant_id_value)
    {
      if($restaurant_id_value!="")
      {

          $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['restaurant_id']=$restaurant_id[$dates_count][$restaurant_id_key];
          $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['restaurant_name']=$restaurant_name[$dates_count][$restaurant_id_key];
          $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['restaurant_cost']=$restaurant_cost[$dates_count][$restaurant_id_key];
          $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['restaurant_food_for']=$restaurant_food_for[$dates_count][$restaurant_id_key];
          $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['restaurant_food_time']=$restaurant_food_time[$dates_count][$restaurant_id_key];
          $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['restaurant_pax_count']=$restaurant_pax_count[$dates_count][$restaurant_id_key];
          foreach($restaurant_food_id[$dates_count][$restaurant_id_key] as $restaurant_child_food_id_key =>  $restaurant_child_food_id_value)
          {

             $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['food_details'][$restaurant_child_food_id_key]['food_id']=$restaurant_food_id[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key];

             $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['food_details'][$restaurant_child_food_id_key]['food_name']=$restaurant_food_name[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key];

             $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['food_details'][$restaurant_child_food_id_key]['food_category_id']=$restaurant_food_category_id[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key];

             $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['food_details'][$restaurant_child_food_id_key]['food_unit']=$restaurant_food_unit[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key];

             $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['food_details'][$restaurant_child_food_id_key]['food_qty']=$restaurant_food_qty[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key];

             $booking_details_array[$dates_count]['restaurant'][$restaurant_count]['food_details'][$restaurant_child_food_id_key]['food_price']=$restaurant_food_price[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key];
          // echo $restaurant_food_qty[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key]."----".$restaurant_food_price[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key]."<br>";

             $calculate_total_cost+=($restaurant_food_qty[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key]*$restaurant_food_price[$dates_count][$restaurant_id_key][$restaurant_child_food_id_key]);
         }

         $restaurant_count++;
     }
     

 }

}



}
$booking_amount_without_markup=$calculate_total_cost;
$calculate_markup_cost=round(($calculate_total_cost*$booking_markup_per)/100);
$calculate_total_with_markup_cost=round($calculate_total_cost+$calculate_markup_cost);
$booking_amount=$calculate_total_with_markup_cost;

$total_cost=$booking_amount;

$currency="GEL";


$from_date=$booking_dates_array[0];
$to_date=end($booking_dates_array);


$booking_array=array("itinerary_id"=>$itinerary_details['itinerary']['itinerary_id'],

	"booking_amount"=>$booking_amount,

	"booking_amount_without_markup"=>$booking_amount_without_markup,

	"booking_from_date"=>$from_date,

	"booking_to_date"=>$to_date,

	"booking_rooms_count"=>$total_rooms_count,

	"booking_adult_count"=>$no_of_adults,

	"booking_child_count"=>$no_of_child,

	"booking_child_age"=>$child_age_array,

	"booking_markup_per"=>$booking_markup_per,

	"itinerary_currency"=>$currency,

	"booking_details_array"=>$booking_details_array

);
$_SESSION['booking_array']=$booking_array;

       //API URL
$url = $GLOBALS["api_base_url"].'/api/getCountries?booking_status=1';

//create a new cURL resource
$ch = curl_init($url);

//set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
$result = curl_exec($ch);

//close cURL resource
curl_close($ch);
$countries=json_decode($result,true);
?>
<style>
    .traveltour-page-title-wrap.traveltour-style-custom.traveltour-center-align
    {
        display:none;
    }
</style>
<div class="tourmaster-template-payment">
    <div class="traveltour-page-wrapper" id="traveltour-page-wrapper">
        <div class="tourmaster-page-wrapper" id="tourmaster-page-wrapper">
            <div class="tourmaster-payment-head tourmaster-with-background" style="background-image: url(<?php echo get_site_url(); ?>/wp-content/uploads/2019/05/vpksSoYMNDD9CzrNacXpOZjenU4Ydp.jpg);">
                <div class="traveltour-header-transparent-substitute"></div>
                <div class="tourmaster-payment-head-overlay-opacity"></div>
                <div class="tourmaster-payment-head-overlay"></div>
                <div class="tourmaster-payment-head-top-overlay"></div>
                <div class="tourmaster-payment-title-container tourmaster-container">
                    <h1 class="tourmaster-payment-title tourmaster-item-pdlr"><?php echo ucwords($itinerary_details['itinerary']['itinerary_name']); ?> </h1></div>
                    <div class="tourmaster-payment-step-wrap" id="tourmaster-payment-step-wrap">
                        <div class="tourmaster-payment-step-overlay"></div>
                        <div class="tourmaster-payment-step-container tourmaster-container">
                            <div class="tourmaster-payment-step-inner tourmaster-item-pdlr clearfix">
                                <div class="tourmaster-payment-step-item tourmaster-checked " data-step="1"><span class="tourmaster-payment-step-item-icon"><i class="fa fa-check" ></i><span class="tourmaster-text" >1</span></span><span class="tourmaster-payment-step-item-title">Select Tour</span></div>
                                <div class="tourmaster-payment-step-item tourmaster-current " data-step="2"><span class="tourmaster-payment-step-item-icon"><i class="fa fa-check" ></i><span class="tourmaster-text" >2</span></span><span class="tourmaster-payment-step-item-title">Customer Details</span></div>
                                <div class="tourmaster-payment-step-item " data-step="3"><span class="tourmaster-payment-step-item-icon"><i class="fa fa-check" ></i><span class="tourmaster-text" >3</span></span><span class="tourmaster-payment-step-item-title">Payment</span></div>
                                <div class="tourmaster-payment-step-item " data-step="4"><span class="tourmaster-payment-step-item-icon"><i class="fa fa-check" ></i><span class="tourmaster-text" >4</span></span><span class="tourmaster-payment-step-item-title">Complete</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tourmaster-template-wrapper" id="tourmaster-payment-template-wrapper" data-ajax-url="<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php" data-booking-detail="{&quot;tour-id&quot;:&quot;5692&quot;,&quot;tour-date&quot;:&quot;2019-11-20&quot;,&quot;tour-male&quot;:&quot;1&quot;,&quot;tour-female&quot;:&quot;&quot;}">
                    <div class="tourmaster-container">
                        <div class="tourmaster-page-content tourmaster-item-pdlr clearfix tour-master-pay-book">
                            <div class="tourmaster-tour-booking-bar-wrap" id="tourmaster-tour-booking-bar-wrap">
                                <div class="tourmaster-tour-booking-bar-outer">
                                    <div class="tourmaster-tour-booking-bar-inner" id="tourmaster-tour-booking-bar-inner">
                                        <div class="tourmaster-tour-booking-bar-summary">
                                            <h3 class="tourmaster-tour-booking-bar-summary-title"><?php echo ucwords($itinerary_details['itinerary']['itinerary_name']); ?></h3>
                                            <div class="tourmaster-tour-booking-bar-summary-info tourmaster-summary-travel-date"><span class="tourmaster-head">Start Date : </span><span class="tourmaster-tail"><?php echo date('F d, Y',strtotime($from_date)); ?>
                                        </span>
                                    </div>
                                    <div class="tourmaster-tour-booking-bar-summary-info tourmaster-summary-end-date"><span class="tourmaster-head">End Date : </span><span class="tourmaster-tail"><?php echo date('F d, Y',strtotime($to_date)); ?></span></div>
                                    <div class="tourmaster-tour-booking-bar-summary-info tourmaster-summary-period"><span class="tourmaster-head">Duration : </span><span class="tourmaster-tail"><?php echo $itinerary_details['itinerary']['itinerary_tour_days']." Nights ".($itinerary_details['itinerary']['itinerary_tour_days']+1)."  Days"; ?></span></div>
                                    <div class="tourmaster-tour-booking-bar-summary-people-wrap">
                                        <div class="tourmaster-tour-booking-bar-summary-people tourmaster-variable clearfix">
                                            <div class="tourmaster-tour-booking-bar-summary-people-amount tourmaster-male"><span class="tourmaster-head">PAX COUNT : </span><span class="tourmaster-tail"><?php echo $no_of_adults; ?></span></div>
                                        </div>
                                    </div>
                                       <!--  <div class="tourmaster-tour-booking-bar-coupon-wrap">
                                            <input type="text" class="tourmaster-tour-booking-bar-coupon" name="coupon-code" placeholder="Coupon Code" value="" /><a class="tourmaster-tour-booking-bar-coupon-validate" data-ajax-url="<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php" data-tour-id="5692">Apply</a>
                                            <div class="tourmaster-tour-booking-coupon-message"></div>
                                        </div> -->
                                     <!--    <div class="tourmaster-tour-booking-bar-price-breakdown-wrap"><span class="tourmaster-tour-booking-bar-price-breakdown-link" id="tourmaster-tour-booking-bar-price-breakdown-link">View Price Breakdown</span>
                                            <div class="tourmaster-price-breakdown">
                                                <div class="tourmaster-price-breakdown-base-price-wrap">
                                                    <div class="tourmaster-price-breakdown-base-price"><span class="tourmaster-head">Male Base Price</span><span class="tourmaster-tail"><span class="tourmaster-price-detail" >1 x $3,400</span><span class="tourmaster-price">$3,400.00</span></span>
                                                    </div>
                                                </div>
                                                <div class="tourmaster-price-breakdown-summary">
                                                    <div class="tourmaster-price-breakdown-sub-total "><span class="tourmaster-head">Sub Total Price</span><span class="tourmaster-tail tourmaster-right">$3,400.00</span></div>
                                                    <div class="tourmaster-price-breakdown-tax-rate"><span class="tourmaster-head">Tax Rate</span><span class="tourmaster-tail tourmaster-right">9%</span></div>
                                                    <div class="tourmaster-price-breakdown-tax-due"><span class="tourmaster-head">Tax Due</span><span class="tourmaster-tail tourmaster-right">$306.00</span></div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-- <div class="tourmaster-tour-booking-bar-total-price-wrap tourmaster-deposit">
                                        <input type="hidden" name="payment-type" value="partial" /><i class="icon_tag_alt"></i><span class="tourmaster-tour-booking-bar-total-price-title">Total Price</span><span class="tourmaster-tour-booking-bar-total-price">$3,706.00</span></div> -->


                                        <!--   <div class="tourmaster-tour-booking-bar-deposit-text tourmaster-active"><span class="tourmaster-tour-booking-bar-deposit-title">30% Deposit </span><span class="tourmaster-tour-booking-bar-deposit-price">$1,111.80</span><span class="tourmaster-tour-booking-bar-deposit-caption">*Pay the rest later</span></div> -->

                                        <div class="tourmaster-tour-booking-bar-deposit-text tourmaster-active"><i class="icon_tag_alt"></i><span class="tourmaster-tour-booking-bar-total-price-title">Total Price</span><span class="tourmaster-tour-booking-bar-deposit-price"><?php echo $currency; ?> <?php echo $total_cost; ?></span></div>

                                        <!--<center><button id="download_itinerary" class="buttondownload" type="button">Download Itinerary</button></center>-->


                                        <!-- <a class="tourmaster-tour-booking-continue tourmaster-button tourmaster-payment-step" data-step="3">Next Step</a> -->
                                    </div>
                                </div>
                                <div class="tourmaster-tour-booking-bar-widget  traveltour-sidebar-area pay-book-with-us">
                                    <div id="text-14" class="widget widget_text traveltour-widget">
                                        <div class="textwidget"><span class="gdlr-core-space-shortcode" style="margin-top: -20px ;"></span>
                                            <div class="gdlr-core-widget-list-shortcode background-div-box" id="gdlr-core-widget-list-0">
                                                <h3 class="gdlr-core-widget-list-shortcode-title">Why Book With Us?</h3>
                                                <ul>
                                                    <li><i class="fa fa-dollar" style="font-size: 15px ;color: #fff ;margin-right: 13px ;"></i>No-hassle best price guarantee</li>
                                                    <li><i class="fa fa-headphones" style="font-size: 15px ;color: #fff ;margin-right: 10px ;"></i>Customer care available 24/7</li>
                                                    <li><i class="fa fa-star" style="font-size: 15px ;color: #fff ;margin-right: 10px ;"></i>Hand-picked Tours & Activities</li>
                                                    <li><i class="fa fa-support" style="font-size: 15px ;color: #fff ;margin-right: 10px ;"></i>Free Travel Insureance</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="text-13" class="widget widget_text traveltour-widget">
                                        <div class="textwidget"><span class="gdlr-core-space-shortcode" style="margin-top: -10px ;"></span>

                                            <div class="gdlr-core-widget-box-shortcode " style="background-color: #133a67 ;">
                                                <h3 class="gdlr-core-widget-box-shortcode-title" style="color: #ffffff ;">Pay Safely With Us</h3><i class="gdlr-core-widget-box-shortcode-icon icon_lock_alt"></i>
                                                <div class="gdlr-core-widget-box-shortcode-content">
                                                    <p><span style="font-size: 13px; color: #ffffff; ">The payment is encrypted and<br />
                                                        transmitted securely with an <strong>SSL<br />
                                                        protocol.</strong></span></p>
                                                        <span class="gdlr-core-space-shortcode" style="margin-top: 25px ;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tourmaster-tour-payment-content" id="tourmaster-tour-payment-content">
                                   <form class="tourmaster-single-tour-booking-fields tourmaster-update-header-price tourmaster-form-field tourmaster-with-border" method="post" action="<?php echo get_site_url(); ?>/itinerary-booked" id="traveldoor-activity-confirm-booking" data-ajax-url="javascript:void(0);">
                           <!--  <div class="tourmaster-payment-traveller-info-wrap tourmaster-form-field tourmaster-with-border">
                                <h3 class="tourmaster-payment-traveller-info-title"><i class="fa fa-suitcase" ></i>Traveller Details</h3>
                                <div class="tourmaster-traveller-info-field clearfix  tourmaster-with-info-title"><span class="tourmaster-head">Traveller 1</span><span class="tourmaster-tail clearfix"><div class="tourmaster-combobox-wrap tourmaster-traveller-info-title" ><select name="traveller_title[]" ><option value="mr"  >Mr</option><option value="mrs"  >Mrs</option><option value="ms"  >Ms</option><option value="miss"  >Miss</option><option value="master"  >Master</option></select></div><input type="text" class="tourmaster-traveller-info-input" name="traveller_first_name[]" value="" placeholder="First Name *" data-required /><input type="text" class="tourmaster-traveller-info-input" name="traveller_last_name[]" value="" placeholder="Last Name *" data-required /><div style="float: left; width: 50%" ><div class="tourmaster-traveller-info-custom" ><div class="tourmaster-combobox-wrap" ><select name="traveller_age[]"  ><option value="" >Age</option><option value="12-15"  >12-15</option><option value=" 15-18"  > 15-18</option><option value="  18+"  >  18+</option></select></div></div></div><div style="float: left; width: 50%" ><div class="tourmaster-traveller-info-custom" ><input type="text" name="traveller_phone[]" value="" placeholder="Phone *" data-required  /></div></div></span></div>
                            </div> -->
                            <div class="tourmaster-payment-contact-wrap tourmaster-form-field tourmaster-with-border">

                                <h3 class="tourmaster-payment-contact-title"><i class="fa fa-file-text-o" ></i>Customer Details 

                                </h3>
                                <?php
                                        $user_id=wp_get_current_user()->ID;
                                      if (!empty(get_user_meta( $user_id, 'first_name', true ))) $fname = get_user_meta( $user_id, 'first_name', true );
                                      else $fname = '';
                                      if (!empty(get_user_meta( $user_id, 'last_name', true ))) $lname = get_user_meta( $user_id, 'last_name', true );
                                      else $lname = '';
                                      if (!empty(wp_get_current_user()->user_email)) $user_email = wp_get_current_user()->user_email;
                                      else $user_email = '';
                                      if (!empty(get_user_meta( $user_id, 'phone', true ))) $user_phone = get_user_meta( $user_id, 'phone', true );
                                      else $user_phone = '';
                                      if (!empty(get_user_meta( $user_id, 'country', true ))) $user_country = get_user_meta( $user_id, 'country', true );
                                      else $user_country = '';
                                    ?>
                                <div class="tourmaster-contact-field tourmaster-contact-field-first_name tourmaster-type-text clearfix">
                                    <div class="tourmaster-head">First Name<span class="tourmaster-req">*</span></div>
                                    <div class="tourmaster-tail clearfix">
                                   
                                        <input type="text" name="first_name" value="<?php echo $fname ;?>" data-required  required="required" />
                                    </div>
                                </div>
                                <div class="tourmaster-contact-field tourmaster-contact-field-last_name tourmaster-type-text clearfix">
                                    <div class="tourmaster-head">Last Name<span class="tourmaster-req">*</span></div>
                                    <div class="tourmaster-tail clearfix">
                                        <input type="text" name="last_name" value="<?php echo $lname ;?>" data-required required="required" />
                                    </div>
                                </div>
                                <div class="tourmaster-contact-field tourmaster-contact-field-email tourmaster-type-email clearfix">
                                    <div class="tourmaster-head">Email<span class="tourmaster-req">*</span></div>
                                    <div class="tourmaster-tail clearfix">
                                        <input type="email" name="email" value="<?php echo $user_email ;?>" data-required required="required" />
                                    </div>
                                </div>
                                <div class="tourmaster-contact-field tourmaster-contact-field-phone tourmaster-type-text clearfix">
                                    <div class="tourmaster-head">Phone<span class="tourmaster-req">*</span></div>
                                    <div class="tourmaster-tail clearfix">
                                        <input type="text" name="phone" value="<?php echo $user_phone ;?>" data-required required="required" />
                                    </div>
                                </div>
                                <div class="tourmaster-contact-field tourmaster-contact-field-country tourmaster-type-combobox clearfix">
                                    <div class="tourmaster-head">Country<span class="tourmaster-req">*</span></div>
                                    <div class="tourmaster-tail clearfix">
                                        <div class="tourmaster-combobox-wrap">
                                            <select name="country" data-required required="required">
                                                <option value="">SELECT COUNTRY</option>
                                                <?php
                                                foreach($countries as $country)
                                                {   
                                                    if($country['country_name'] == $user_country)
                                                    {
                                                        echo '<option value="'.$country['country_id'].'" selected>'.$country['country_name'].'</option>';
                                                    }
                                                    else
                                                    {
                                                        echo '<option value="'.$country['country_id'].'" >'.$country['country_name'].'</option>';
                                                    }
                                                    
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tourmaster-contact-field tourmaster-contact-field-contact_address tourmaster-type-textarea clearfix">
                                    <div class="tourmaster-head">Address</div>
                                    <div class="tourmaster-tail clearfix">
                                        <textarea name="contact_address" required="required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tourmaster-payment-additional-note-wrap tourmaster-form-field tourmaster-with-border">
                                <h3 class="tourmaster-payment-additional-note-title"><i class="fa fa-file-text-o" ></i>Notes</h3>
                                <div class="tourmaster-additional-note-field clearfix"><span class="tourmaster-head">Additional Notes</span><span class="tourmaster-tail clearfix"><textarea name="additional_notes" ></textarea></span></div>
                            </div>
                            <div class="tourmaster-tour-booking-required-error tourmaster-notification-box tourmaster-failure" data-default="Please fill all required fields." data-email="Invalid E-Mail, please try again." data-phone="Invalid phone number, please try again."></div>
                            <!--  <a class="tourmaster-tour-booking-continue tourmaster-button tourmaster-payment-step" data-step="3">Next Step</a> -->
                            <input type="hidden" name="user_id" value="<?php echo wp_get_current_user()->ID; ?>" required>
                            <input type="hidden" name="user_nicename" value="<?php echo wp_get_current_user()->user_nicename; ?>" required>
                            <input type="hidden" name="user_email" value="<?php echo wp_get_current_user()->user_email; ?>" required>
                            <input type="submit" value="Confirm Booking" class="tourmaster-button tourmaster-payment-step text-center">
                        </form>
                    </div>

                    <!-- ================ Pay Book Section in mobile ==================== view -->

                    <div class="tourmaster-tour-booking-bar-widget  traveltour-sidebar-area pay-book-with-us-mobile ">
                        <div id="text-14" class="widget widget_text traveltour-widget">
                            <div class="textwidget"><span class="gdlr-core-space-shortcode" style="margin-top: -20px ;"></span>
                                <div class="gdlr-core-widget-list-shortcode background-div-box" id="gdlr-core-widget-list-0">
                                    <h3 class="gdlr-core-widget-list-shortcode-title">Why Book With Us?</h3>
                                    <ul>
                                        <li><i class="fa fa-dollar" style="font-size: 15px ;color: #fff ;margin-right: 13px ;"></i>No-hassle best price guarantee</li>
                                        <li><i class="fa fa-headphones" style="font-size: 15px ;color: #fff ;margin-right: 10px ;"></i>Customer care available 24/7</li>
                                        <li><i class="fa fa-star" style="font-size: 15px ;color: #fff ;margin-right: 10px ;"></i>Hand-picked Tours & Activities</li>
                                        <li><i class="fa fa-support" style="font-size: 15px ;color: #fff ;margin-right: 10px ;"></i>Free Travel Insureance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="text-13" class="widget widget_text traveltour-widget">
                            <div class="textwidget"><span class="gdlr-core-space-shortcode" style="margin-top: -10px ;"></span>

                                <div class="gdlr-core-widget-box-shortcode " style="background-color: #133a67 ;">
                                    <h3 class="gdlr-core-widget-box-shortcode-title" style="color: #ffffff ;">Pay Safely With Us</h3><i class="gdlr-core-widget-box-shortcode-icon icon_lock_alt"></i>
                                    <div class="gdlr-core-widget-box-shortcode-content">
                                        <p><span style="font-size: 13px; color: #ffffff; ">The payment is encrypted and<br />
                                            transmitted securely with an <strong>SSL<br />
                                            protocol.</strong></span></p>
                                            <span class="gdlr-core-space-shortcode" style="margin-top: 25px ;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pay ================Book Section in mobile  view Closed================== -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

add_shortcode('show_itinerary_booking', 'itinerary_booking');
?>