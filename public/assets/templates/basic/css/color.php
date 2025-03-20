<?php
header("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here
function checkhexcolor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color or !checkhexcolor($color)) {
    $color = "#336699";
}
$danger_color='#f53a3a';
$danger_color_rgb='245, 58, 58';
$success_color='#65c18c';
$success_color_rgb='101, 193, 140';

?>


.btn--base,.cmn--btn,.social-links li a:hover{
background-color: <?php echo $color ?> !important;
border-color: <?php echo $color ?> !important;
}
.price,.job__item-content .title a:hover,.menu li a:hover,.menu li a.active, .social-links li a,.header__top__wrapper .contacts li a:hover,.text--base,.subtitle,.counter__item-icon,.about__content .about__info li::before,.service__item-icon,.footer__widget .widget-title,.faq__tab__menu .tab__link:hover *, .faq__tab__menu .tab__link.active *,.contact__info__wrapper .title,.user__profile-content .designation,.dashboard__sidebar__menu li a:hover, .dashboard__sidebar__menu li a.active,.dashboard__sidebar__menu li a:hover,.overview__content__wrapper .btn,.title a:hover,.read-more:hover,.footer-links li a:hover,.latest-posts .post-info .posts-date i,.announcement__meta li i,.sidebar__widget .info__item .content .title,.pagination .page-item a,
.pagination .page-item span,.username,.dashboard__sidebar__toggler,.job__price,.text--primary,.tag,.icon ,.breadcums li a:hover,.preloader .search-icon,.dashboard__sidebar__menu li::before, .menu .btn,.finished__job__item .job__header-title a:hover, .post-info a:hover{
color: <?php echo $color ?> !important;
}
.scrollToTop,.counter__item::before, .counter__item::after,.overview__content__wrapper.right-bg .btn,.read-more:hover::before,.bg--base {
background-color: <?php echo $color ?> !important;
}
.overview__content__wrapper::before,.section__header-title::before,.slick-arrow:hover, .slick-arrow.arrow-right,.slick-arrow:hover, .slick-arrow.arrow-right,.slick-arrow,.slick-dots li.slick-active button,.footer__widget .widget-title::before,.post-widget .pro-title::before,.post-widget .pro-title::after,.faq__tab__menu .tab__link::before,.faq__item.open .faq__item-title::before,.custom--card .card-header,.sidebar__widget-title::before, .sidebar__widget-title::after,.table thead tr, .user__profile::after,.pagination .page-item.active span, .pagination .page-item.active a, .pagination .page-item:hover span, .pagination .page-item:hover a,.custom--checkbox input[type=checkbox]::after
background: <?php echo $color ?> !important;
}
.social-links li a,.form--control:focus,.nice-select:focus,.pagination .page-item a, .pagination .page-item span{
border:1px solid <?php echo $color ?> !important;
}
.popular__tags .tags-list li a:hover{
color: <?php echo $color ?> !important;
border-color: <?php echo $color ?> !important;
}
.btn--primary{
border-color: <?php echo $color ?> !important;
background-color: <?php echo $color ?> !important;
}
.user__profile::before{
background-color: <?php echo $color ?>15 !important;
}
.slick-dots li button{
background: <?php echo $color ?>33 !important;
}
.job__search .form--group{
box-shadow:0 0 10px 5px <?php echo $color ?>26 !important;
}

@media (max-width: 575px){
.job__search .form--group {
box-shadow: none !important;
}
}
.cmn--btn,.user__profile-thumb {
box-shadow:0 0 10px 5px <?php echo $color ?>1a !important;
}
.form--control:focus{
box-shadow: 1px 1px 15px 3px <?php echo $color ?>14 !important;
}
.ticket__wrapper{
box-shadow: 0 0 10px <?php echo $color ?>66 !important;
}
.reply-item{
border: 1px solid <?php echo $color ?>33 !important;
}
.counter__item:hover{
background: <?php echo $color ?>05 !important;
}
.part{
border-color: <?php echo $color ?> !important;
}


.custom--accordion .accordion-button:not(.collapsed) {
background-color: <?php echo $color ?> !important;

}

.subscription-btn {
    display: inline-block;
    font-size: 16px;
    font-weight: 400;
    line-height: 20px;
    color: <?php echo $color; ?>;
    border: 1px solid <?php echo $color; ?>;
    background-color: #fff;
    padding: 7px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: all .3s;
}
.subscription-btn:hover,
.subscription-btn.active {
    background-color: <?php echo $color; ?> !important;
    color: #fff;
    border-color: <?php echo $color; ?> !important;
}

/* Pricing CSS */
.single-pricing {
    padding: 20px;
    -webkit-transition: 0.3s;
    transition: 0.3s;
    background-color: #fff;
}

.single-pricing:hover {
    -webkit-transform: translateY(-5px);
    transform: translateY(-5px);
    -webkit-box-shadow: 0 0 50px <?php echo $color; ?>;
    box-shadow: 0 0 50px <?php echo $color; ?>;
    border: transparent;
}

div[class*=col]:nth-child(2) .single-pricing {
    -webkit-box-shadow: 0 0 50px <?php echo $color; ?>;
    box-shadow: 0 0 50px <?php echo $color; ?>;
    border: 0;
}

div[class*=col]:nth-child(2) .single-pricing .btn-wrapper .cmn-btn {
    background-color: <?php echo $color; ?>;
    color: #fff;
}

.single-pricing:hover .btn-wrapper .cmn-btn {
    background-color: <?php echo $color; ?>;
    color: #fff;
}

.single-pricing.featured {
    -webkit-box-shadow: 0 0 50px <?php echo $color; ?>;
    box-shadow: 0 0 50px <?php echo $color; ?>;
    border: 0;
}

.single-pricing.featured .cmn-btn {
    background-color: <?php echo $color; ?>;
    color: #fff;
}

.single-pricing.featured .cmn-btn:hover {
    background-color: <?php echo $color; ?>;
}

.single-pricing-border {
    border: 1px solid <?php echo $color; ?>;
}

.single-pricing-title {
    font-size: 20px;
    line-height: 28px;
    font-weight: 700;
    margin-bottom: 0;
    color: <?php echo $color; ?>;
}

.single-pricing-para {
    font-size: 16px;
    line-height: 24px;
    font-weight: 400;
    margin-bottom: 0;
}

@media (min-width: 992px) and (max-width: 1199.98px) {
    .single-pricing-title {
        font-size: 18px;
    }
}

@media only screen and (max-width: 375px) {
    .single-pricing-title {
        font-size: 18px;
    }
}

.single-pricing-list-item-icon {
    background-color: rgba(<?php echo $success_color_rgb; ?>, .2);
    color: <?php echo $success_color; ?>;
    border: 1px solid <?php echo $success_color; ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 12px;
    height: 20px;
    width: 20px;
    flex-shrink: 0;
}

.single-pricing-list-item-icon.cross-icon {
    color: <?php echo $danger_color; ?>;
    background-color: rgba(<?php echo $danger_color_rgb; ?>, .2);
    border: 1px solid <?php echo $danger_color; ?>;
}

.pricing-tabs ul li.active,
.pricing-tabs ul .tab-list.active {
    color: <?php echo $color; ?>;
}

.pricing-tabs-switch .input-switch:checked+label {
    background: rgba(<?php echo hex2rgb($color); ?>, 0.15);
}

.pricing-tabs-switch .input-switch:checked+label:after {
    left: calc(100% - 5px);
    -webkit-transform: translateX(-100%);
    transform: translateX(-100%);
}
.cmn-btn.btn-bg-gray {
  background-color: #fff;
  color: <?php echo $color; ?>;
  border: 2px solid <?php echo $color; ?>;
}

.cmn-btn.btn-bg-gray:hover {
  background-color: <?php echo $color; ?>;
  color: #fff;
}
.radius-10 {
    border-radius: 10px;
}
.cmn-btn{
    border-radius: 5px;
}
.subsription-btn:hover,
.subsription-btn.active {
  background-color: <?php echo $color; ?>;
  color: #fff;
  border-color: <?php echo $color; ?>;
}
.subsription-btn {
  display: inline-block;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  color: <?php echo $color; ?>;
  border: 1px solid <?php echo $color; ?>;
  background-color: #fff;
  padding: 7px 15px;
  border-radius: 5px;
  cursor: pointer;
  transition: all .3s;
}
.btn-profile.extra-width {
  padding-inline: 50px;
}
.btn-profile.btn-bg-1 {
  background-color: <?php echo $color; ?>;
  color: #fff;
}

.payment_getway_image>ul li.selected:after {
    background: <?php echo $color; ?>;
}
.payment_getway_image>ul li.selected {
    border-color: <?php echo $color; ?>;
}

/* Convert HEX to RGB */
<?php
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if (strlen($hex) == 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return "$r, $g, $b";
}
?>
