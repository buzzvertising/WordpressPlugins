<?php
require('aweber_api/aweber_api.php');

class MyApp{

    function __construct() {
        # replace XXX with your real keys and secrets
        $this->consumerKey = 'xxxx';
        $this->consumerSecret = 'xxxx';
        $this->accessToken = 'xxxxx';
        $this->accessSecret = 'xxxxx';

        $this->application = new AWeberAPI($this->consumerKey, $this->consumerSecret);
    }

    function findList($listName) {
        $account = $this->application->getAccount($this->accessToken, $this->accessSecret);
        print_r($account->lists);

        $foundLists = $account->lists->find(array('name' => $listName));
        //must pass an associative array to the find method

        print_r($foundLists);

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
            print $exc;
        }
    }
}

$app = new MyApp();

$list = $app->findList($name='4-free-dogs');

$subscriber = array(
    'email' => 'mircea@cum-vreau.eu',
    'name'  => 'John Doe'
);

$app->addSubscriber($subscriber, $list);
