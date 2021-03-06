<?php
/******************************
 * $Id: localization.php,v 1.7.0 2006/06/14 07:16:03 Ulminia Exp $
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}


// common loc

$lang['rgname'] = 'Roster Gallery';
$lang['rg_button'] = 'Roster Gallery|Image screenshot database';
$lang['name'] = 'Roster Gallery';
$lang['next'] = 'Next';
$lang['previous'] = 'Previous';

$lang['all'] = 'All';
$lang['vote'] = 'Vote!';
$lang['lock'] 			= 'Locked';
$lang['unlock']			= 'Unlocked';
$lang['rating'] = 'Rating';
$lang['nodisc'] = 'No Discrption';
$lang['norating'] = 'No Rating';
$lang['nocaption'] = 'No Caption';

$lang['imgloc'] = 'Image location';
$lang['capt'] = 'Caption';
$lang['cat'] = 'Category';
$lang['desc'] = 'Description';
$lang['upimg'] = 'Upload Image';

$lang['upload'] = 'Upload';
$lang['view'] = 'View';
$lang['uninstallmsg'] = "Roster Gallery was Uninstalled successfuly.<br><br>All tables and config settings have been removed from the sql database as well as the screenshots folder and any subfolders where deleted.<br><br>Thank you for trying the Roster Gallory and i am sorry this addon was not what your where looking for.<br><br>Ulminia - Azjol-Nerub (joseph foster) please emailme at warcraftalliance @ gmail.com for any help or comments you would like to make :)";
$lang['uninstallmsgtitle'] = "Roster Gallery Un-Installer";
$lang['height'] = 'Height';
$lang['width'] = 'Width';
$lang['rows'] = 'Rows';
$lang['amount'] = 'Amount';
$lang['yes'] = 'Yes';
$lang['no'] = 'No';
$lang['enable'] = 'Enable';
$lang['disable'] = 'Disable';
// status page test


$lang['admin']['gallery_uap'] = 'Unaproved Images';
$lang['admin']['gallery_ap'] = 'Aproved images';
$lang['admin']['gallery_conf'] = 'Gallery Conf';
$lang['admin']['gallery_cat'] = "Catagory Config";

$lang['gal_cat_add'] = 'Add Catagory';
$lang['gal_cat_title'] = 'Title';
$lang['gal_cat_desc'] = 'Description';
$lang['gal_cat_order'] = 'Order';
$lang['gal_cat_access'] = 'Access';
$lang['gal_cat_disable'] = 'Disable';
$lang['gal_cat_active'] = 'Active';
$lang['gal_cat_delete'] = 'Delete';
$lang['gal_cat_default'] = 'Default';


$lang['admin']['dir_is_write'] = 'Is writeable';
$lang['admin']['dir_not_write'] = 'is not Writeable Click <a href="%1$s" onclick="return popitup(\'%1$s\')">HERE</a> to chmod';
$lang['admin']['status_info'] = 'This section displays weather the directories that<br>Roster Gallery uses to save images and upload them are writeable';
$lang['admin']['dir_check'] = 'Checking directory <span class="green">[%1$s]</span> for write access';
$lang['admin']['status_chmod'] = 'Updating Dir Attrib';
$lang['admin']['status_chmod_changed'] = 'Attributes Changed';
$lang['admin']['status_chmod_unchanged'] = 'Attributes not change do so manualy';
$lang['admin']['status_chmod_nodir'] = 'The directory <span class="red">[%1$s]</span><br>does not exist or is hidden';
//$lang['admin']['vote_config'] = '';
// admin vote page txt

$lang['admin']['page_vote'] = "Voteing Settings";
$lang['admin']['page_vote_disc'] = "These are the settings you change for the voting system display settings<br> how to vote to enable to disable it all that good stuff";
$lang['admin']['popup_on'] = 'Use Popup';
$lang['admin']['popup_off'] = 'Normal';
// upload settings
$lang['admin']['rg_upload_size'] = 'Size';
$lang['admin']['open'] = 'Open';
$lang['admin']['closed'] = 'Closed';
//begin admin loc
$lang['admin']['edit'] = 'Edit';
$lang['admin']['configurationsettings'] = "Configuration Settings";
$lang['admin']['configurationgreeting'] = "Welcome to the Roster Gallery admin section.<BR>Here you can delete screenshots that are not wanted<BR>and approve Images that have been uploaded";
$lang['admin']['sssize'] = "Screenshots Folder Size";
$lang['admin']['w_version'] = "Version";
$lang['admin']['rg_version'] = "Roster Gallery Version";
$lang['admin']['applysettings'] = "Apply Settings";
$lang['admin']['approvedimages'] = "Approved Images";
$lang['admin']['un-approvedimages'] = "Un-Approved Images";
$lang['admin']['imageapproved'] = "Image Approved";
$lang['admin']['imagenotapproved'] = "Approve Image?";
$lang['admin']['na1'] = 'The image with id %1$s is not approved. To approve it, check the box next to the red text, then click approve at the bottom';
$lang['admin']['delimage'] = "Delete Image";
$lang['admin']['delimage_ov'] = 'To Delete The image with id %1$s check the box next to Delete, then click Delete Selected at the bottom';
$lang['admin']['delsel'] = "Delete Selected";
$lang['admin']['approve'] = "Approve";
$lang['admin']['messages'] = "Messages";
$lang['admin']['uninstall'] = "Uninstall";
// begin tool tip loc
$lang['admin']['tooltip01cap'] = "Thumbnail size";
$lang['admin']['tooltip01'] = "Size of the Thumbnail image to be displayed and generated";
$lang['admin']['rg_ipp'] = "Images per page|This sets the amount of images per page";
$lang['admin']['tooltip03cap'] = "Images per line";
$lang['admin']['tooltip03'] = "This sets the amount of images per line";
$lang['admin']['rg_lpp'] = "Rows Per Page|This sets the amount of rows of images there are per page";
$lang['admin']['tooltip05cap'] = "Send to friend link";
$lang['admin']['tooltip05'] = "This sets whether or not the send to friend link exists or not";
$lang['admin']['rg_dct'] = "Caption Text|This sets whether or not the caption text will be dislayed";
$lang['admin']['rg_dul'] = "Upload Settings|Enable this Option to allow Uploads Disable to turn them off";
$lang['admin']['rg_dspc'] = "Show Catagory|This option sets whether to show the catagory selection or not";
$lang['admin']['rg_dscp'] = "Show Caption|This option sets whether to show the caption of images";


$lang['admin']['dispconfig'] = "Display Config";
$lang['admin']['uplodconfig'] = "Upload Config";
$lang['admin']['thumbconfig'] = "Thumbnail Config";
$lang['admin']['apconfig'] = "Approved Screenshots";
$lang['admin']['unapconfig'] = "Pending Screenshots";
$lang['admin']['vote_config'] = "Voteing Settings";
$lang['admin']['status_config'] = 'Addon Status';
# menu tooltips
$lang['admin']['status_configt'] = 'Information to make sure that the addon is working properly';
$lang['admin']['vote_configt'] = "Options to deal with the way image voting is delt with";
$lang['admin']['dispconfigt'] = "Options to change the way Roster gallery looks and collor setting";
$lang['admin']['uplodconfigt'] = "Screenshoot upload settings";
$lang['admin']['thumbconfigt'] = "Comfiguration to deal with the look of screenshots";
$lang['admin']['catconfigt'] = "Options to change the way catagorys work look or are enabled or diabled as well as named";
$lang['admin']['apconfigt'] = "A list of approved screenshots here you can change there catagoy or delete them";
$lang['admin']['unapconfigt'] = "Approve Recently uploaded screenshots in this section";


$lang['admin']['rg_catcount'] = "Display|This option is used to set the active amount of cataories to display<br><font color=red> MUST! = The number of enabled catagories!</font>";
$lang['admin']['tooltip11cap'] = "Catagory";
$lang['admin']['tooltip11'] = "This is one of 10 enabled catagories. Set the name and choose:<br> Enable: This makes the Catagory able to show in the menue and upload bars<br>Disable: This removes this Catagory from showing in Catagory menues and lists";

$lang['admin']['rg_rating_align'] = "Rating Placement|This option sets the alignment of the Rating value of an image to ither the top or the botton of the thumbnail";

$lang['admin']['rg_caption_align'] = "Caption Placement|This option sets the alignment of the Caption of an image to ither the top or the botton of the thumbnail";

$lang['admin']['rg_u_ovlb'] = "Overlib Caption|This feature will alow you to chose to use the new overlib display for the thumbs on the main page making them when moused over display the discription for the image";

$lang['admin']['rg_wm_use'] = "Watermark Use|Thsi feature when activated will use the watermark image you have uploaded and selected in the Thumnail config section";

$lang['admin']['tooltip16cap'] = "Watermark Location";
$lang['admin']['tooltip16'] = "Here you can select wich corner of the thimbnail image you want the watermark to appear in";

$lang['admin']['tooltip17cap'] = "Watermark Image";
$lang['admin']['tooltip17'] = "this is an array of the wm filder in the Roster Gallery folder of all the png images this is the default type of image that will be used in placing a water mark";

$lang['admin']['tooltip18cap'] = "Main Gallery Border Color";
$lang['admin']['tooltip18'] = "This option sets the main color of the border of the roster gallery with one of the 8 allready in the border colors of the main roster";

$lang['admin']['tooltip19cap'] = "Thumbnail Border Color";
$lang['admin']['tooltip19'] = "This option sets the color of the border of the thumbnails with one<br> of the 8 allready in the border colors of the main roster";

$lang['admin']['rg_u_lb'] = "Use LightBox|Activating this option allowes <br>You to use the New Lightbox feature to display <br>the image Insted of going to a nother page";

$lang['admin']['tooltip21cap'] = "Voteing Display";
$lang['admin']['tooltip21'] = "This will chose how the voting option is displayed.<br> There are 2 options Pop-up and Normal.<br>Pop-up: when the link is clicked a new small window will open and display the image thumb and the standard voting selection<br>Normal: the page will go to the old style of voting showing the image large sized with a clickable link to go back to the roster.";

$lang['admin']['tooltip22cap'] = "Voteing Display";
$lang['admin']['tooltip22'] = "This will chose how the voting option is displayed.<br> There are 2 options Pop-up and Normal.<br>Pop-up: when the link is clicked a new small window will open and display the image thumb and the standard voting selection<br>Normal: the page will go to the old style of voting showing the image large sized with a clickable link to go back to the roster.";

$lang['admin']['tooltip23cap'] = "Upload size";
$lang['admin']['tooltip23'] = "Set the max size that a file can<br> be in KB remember 1024kb = 1mb lol";

$lang['admin']['tooltip24cap'] = "Upload Window";
$lang['admin']['tooltip24'] = "Sets weather the upload screenshot<br> window is open or closed";

$lang['admin']['tooltip25cap'] = "Upload Window";
$lang['admin']['tooltip25'] = "chose weather or not to have the upload window open or closed ";

$lang['cannot_check_version'] = 'Cannot Check the File Version';
$lang['new_rg_available'] = 'There is  new version of the Roster Gallery Avualable <span class="green">v %1$s</span> <b>Click Here!</b> to get it';
$lang['cannot_writeto_ss_folder'] = 'Custom Images folder is not writable<br />Click <a href="%1$s">HERE</a> to try to chmod [<span class="green">%2$s</span>]<br />Custom Image uploading is temporarily disabled';

