<?php 

class Mailchimp
{

    /**
     * Access the mailchimp lists API and subscribe user
     * @author Versha dhiman
     * for more info check "https://apidocs.mailchimp.com/api/2.0/lists/subscribe.php"
    */
    public static function addEmailToList($email)
    {

        $apiKey = 'ab412eabc2377880a8166624684b403c-us17'; // API key for mailchimp
        $listID = '67ebeeb19b'; //Id of newsletter list
        
        // MailChimp API URL
        $memberID = md5(strtolower($email));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;

        // member information
        $json = json_encode([
            'email_address' => $email,
            'status'        => 'subscribed',
        ]);

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // store the status message based on response code
        if ($httpCode == 200) {
           echo $msg = '<p style="color:green;"> have successfully subscribed</p>';
        } else {
            switch ($httpCode) {
                case 214:
                  echo   $msg = '<p style="color:orange;"> are already subscribed.</p>';
                    //break;
                default:
                    echo $msg = '<p style="color:red;">Some problem occurred, please try again.</p>';
                    //break;
            }
        }
    }
    
}