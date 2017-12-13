<?php
/*
Plugin Name: Donate Extra Addons
Plugin URI: http://buzzvertising.ro
Description: Adds a custom shortcode. Usage: [donate_rolda name='unique_name' recurring='M/Y/1'  button='path/to/button_image.png' under='NO PayPal account Needed' class='' secondary='on/off' language='US/no_NO/DE' currency='EUR' ]&lt;li&gt;350 | Donation Purpose&lt;/li&gt;[/donate_rolda]
Version: 1.0
Author: buzzvertising
Author URI: http://buzzvertising.ro
License: GPLv2
*/

//[donate_rolda recurring='M/Y/1'  button='path/to/button_image.png' under='NO PayPal account Needed' class='' secondary='on/off' language='US/no_NO/DE' currency='EUR' return="/we-are-grateful-for-your-contribution"]<li>350 | Donation Purpose</li>[/donate_rolda]
function donate_rolda( $atts, $content = null ){

	extract( shortcode_atts( array(
		'name' => 'donate',
		'recurring' => 'M',
		'secondary'=>'off',
		'button'=> plugins_url( ) . '/donate-extra-addons/images/paypal-button.png',
		'under'=>'NO PayPal account Needed',
		'language'=>'US',
		'currency'=>'EUR',
		'symbol'=>'&euro;',
		'class'=>'',
		'return'=>'/we-are-grateful-for-your-contribution',
		'button_align'=>'center'
		
	), $atts ) );
	
	ob_start();
	$output='';
	// Create a new DOM Document
	$xml = new DOMDocument();
	// Load the html contents into the DOM
	$xml->loadHTML($content);
	
	if( $xml->getElementsByTagName('li')->length > 1 ){
		foreach ($xml->getElementsByTagName('li') as $li):
			$purpose = $li->nodeValue;
			if ( !strpos($purpose, '|')) $purpose = '5|'. $purpose;
			list($amount, $description) = explode ( '|', $purpose);
			$output .= '<div class="'.$class.'"><input type="radio" name="'.$name.'" value="'.strip_tags($purpose).'" checked>'.$amount. $symbol.' '.$description.'</div>';
		endforeach;
	}
	else {
		foreach ($xml->getElementsByTagName('li') as $li):
			$purpose = $li->nodeValue;
			if ( !strpos($purpose, '|')) $purpose = '|'. $purpose;
			$output .= '<input type="hidden" name="'.$name.'" value="'.strip_tags($purpose).'" checked>';
		endforeach;
	}
	echo $output;?>
	<div style="text-align: <?php echo $button_align; ?>; padding:10px;">
		<span onclick="javascript:DoSubmit('<?php echo  $name?>','<?php echo $recurring;?>');" style="cursor: pointer; cursor: hand;"><img class="give-now-button" src="<?php echo $button; ?>" ></span>	
		<br/>
		<span class="no-account"><?php echo $under; ?></span>
		<?php $dextra = get_option( 'DonateExtra' );?>
	</div>	
		<?php if ( $secondary == 'off'){ ?>
	
		<script>
			function DoSubmit( name, recurring){

				var radios = document.getElementsByName( name );

				for (var i = 0, length = radios.length; i < length; i++) {
					if (radios[i].checked || length == 1) {
						// do whatever you want with the checked radio
						donationInfo = radios[i].value.split("|");
						// only one radio can be logically checked, don't check the rest
						break;
					}
				}
				var a = donationInfo[0];
				var n = donationInfo[1];
				
				if ( recurring == 1) {
					var input = document.createElement("input");
					input.setAttribute("type", "hidden");
					input.setAttribute("name", "srt");
					input.setAttribute("value", "");
					document.donation.appendChild(input);
					
					document.donation.cmd.value = '_donations';
					document.donation.amount.value = a;
					document.donation.a3.value = a;
					document.donation.p3.value = '1';
					document.donation.t3.value = '0';
					document.donation.src.value = '0';
					document.donation.srt.value = '';
					document.donation.sra.value = '1';
					document.donation.item_name.value = n;
				}
				if ( recurring != 1){
					document.donation.amount.value = a;
					document.donation.a3.value = a;
					document.donation.item_name.value = n;
					document.donation.t3.value = recurring;
				}
				document.donation.submit();
				//ga('send', 'pageview', 'page': '/outbound/paypal', 'title': 'Paypal - Rolda donation page' });
				dataLayer.push({
				'event':'VirtualPageview',
				'virtualPageURL':'/outbound/paypal',
				'virtualPageTitle' : 'Paypal - Rolda donation page'
				});
			};		
		</script>
		<script type="text/javascript">
		function _uGC(l,n,s) {
		if (!l || l=="" || !n || n=="" || !s || s=="") return "-";
		var i,i2,i3,c="-";
		i=l.indexOf(n);
		i3=n.indexOf("=")+1;
		if (i > -1) {
		i2=l.indexOf(s,i); if (i2 < 0) { i2=l.length; }
		c=l.substring((i+i3),i2);
		}
		return c;
		}
		var uaGaCkVal= _uGC(document.cookie, '_ga=', ';');
		var uaGaCkValArray= uaGaCkVal.split('.');
		var uaUIDVal="";
		var gacid="";
		if(uaGaCkValArray.length==4) {
		uaUIDVal=uaGaCkValArray[2] + "." + uaGaCkValArray[3];
		gacid='=' + uaUIDVal.replace(/%2F/g,"-");
		}
		else {
		uaUIDVal="";
		gacid="";
		}
		var myNewValue = gacid;
		</script>		
		<form name="donation" id="donateextraform" style="display:none;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" id="cmd" name="cmd" value="_donations">
			<input type="hidden" name="amount" id="1" value="0">
			<input type="hidden" name="a3" id="a3" value="0">
			<input type="hidden" name="p3" id="p3" value="1">
			<input type="hidden" name="t3" id="t3" value="Y">  <!-- value="0" Do not repeat, value="M" Month(s), value="Y" Year(s) -->
			<input type="hidden" name="src" id="src" value="1">
			<input type="hidden" name="sra" value="1">
			<input type="hidden" name="notify_url" value="<?php echo plugins_url( ) . '/donate-extra/paypal.php'; ?>">
			<input type="hidden" name="item_name" value="">
			<input type="hidden" name="business" value="<?php echo $dextra['paypal_email']; ?>">
			<input type="hidden" name="lc" value="<?php echo $language; ?>">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="return" value="<?php echo get_site_url() . $return; ?>">
			<input type="hidden" name="custom" value="">
			<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>		

	<?php
	}
	return ob_get_clean();
}

add_shortcode( 'donate_rolda', 'donate_rolda' );
?>