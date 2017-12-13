<?php
require('aweber_api/aweber_api.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/class-phpmailer.php' );
define( 'WP_USE_THEMES', false );        
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');  

//Post user Data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];

$country = $_POST['country'];
$post_ID = $_POST['post_id'];


$full_name = $first_name . " " . $last_name;

//Custom Fields
$to_name = get_field("causes_addressed_to", $post_ID );
$to_email = get_field("causes_email", $post_ID );

$causes_subject = get_field("causes_subject", $post_ID );
$causes_message = get_field("causes_message", $post_ID );

$causes_message .= " <br /> " . $first_name . " " . $last_name . " <br /> " . $country;


if(empty($_POST['url'])){
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SetFrom($email, $full_name);
	$mail->AddReplyTo($email, $full_name);
	$mail->AddAddress($to_email, $to_name);
	$mail->Subject = $causes_subject;
	$mail->MsgHTML($causes_message);

	if ($mail->Send()) {
		$signatures = get_post_meta( $post_ID, 'cause_signed_people', true );
		// check if the custom field has a value
		if( ! empty( $signatures ) ) {
			$signatures ++;
			update_post_meta($post_ID, 'cause_signed_people', $signatures);
		} 
		else {
			update_post_meta($post_ID, 'cause_signed_people', '1');
		}
		echo "Your message was sent";
		
	} else {
		echo "There was an error. Please try later!";
	}
}


// Subscribe to Newsletter

if(empty($_POST['newsletter'])){
	$aweber_list = get_field("causes_aweber_default_list_id", $post_ID );
}
else {
	$aweber_list = get_field("causes_aweber_list_id", $post_ID );
}

$app = new MyApp();

$list = $app->findList($name=$aweber_list);

$subscriber = array(
	'email' => $email,
	'name'  => $full_name,
	'ad_tracking' => 'client_lib_example',
);

$app->addSubscriber($subscriber, $list);


class MyApp{

    function __construct() {
        # replace XXX with your real keys and secrets
        $this->consumerKey = 'xxxxx';
        $this->consumerSecret = 'xxxxxx';
        $this->accessToken = 'xxxxxxx';
        $this->accessSecret = 'xxxxxx';

        $this->application = new AWeberAPI($this->consumerKey, $this->consumerSecret);
    }

    function findList($listName) {
        $account = $this->application->getAccount($this->accessToken, $this->accessSecret);
        $foundLists = $account->lists->find(array('name' => $listName));
        //must pass an associative array to the find method
        return $foundLists[0];
    }	
	
    function addSubscriber($subscriber, $list) {
        # get your aweber account
        $account = $this->application->getAccount($this->accessToken, $this->accessSecret);

        # get your list
        $listUrl = "/accounts/$account->id/lists/$list->id";
        $list = $account->loadFromUrl($listUrl);

        try {
            # create your subscriber
            $list->subscribers->create($subscriber);
        }

        catch(Exception $exc) {
        }
    }
}


?>
