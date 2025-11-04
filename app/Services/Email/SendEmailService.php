<?php

namespace App\Services\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendEmailService
{

    public static $smtpLogPath = 'smtpdebug.log';
    public static $smtpPort = APP_SMTP_PORT;
    public static $smtpHost;
    public static $smtpUsername;
    public static $smtpPassword;
    public static $fromEmail;
    public static $fromName;
    public static $subject;
    public static $htmlMessage;
    public static $smtpSecure;
    public static $autoTLS;
    public static $isExchange = false;

    /**
     *
     * @param array $data
     */
    public static function sendEmail(array $data)
    {

        self::$smtpPort =  $_ENV['APPLICATION_ENVIRONMENT'] == 'development' ? $_ENV['MAILCATCHER_SMTP_PORT'] : $_ENV['APP_SMTP_PORT'];
        self::$smtpHost = $_ENV['APPLICATION_ENVIRONMENT'] == 'development' ? $_ENV['MAILCATCHER_SMTP_HOST'] : $_ENV['APP_SMTP_HOST'];
       
        self::$smtpUsername = $_ENV['APP_SMTP_USERNAME'];
        self::$smtpPassword = $_ENV['APP_SMTP_PASSWORD'];
        self::$fromEmail =  APP_EMAIL_FROM_EMAIL;
        self::$fromName = APP_EMAIL_FROM_EMAIL;
        self::$smtpSecure = APP_EMAIL_SMTP_SECURE;
        self::$autoTLS = APP_EMAIL_AUTOTLS;
        self::$subject = isset($data['subject']) ? $data['subject'] : '';
        
        if(empty($data['message']) || !isset($data['message']))
        {
            throw new \Exception(' $data[message] is empty or null or missing, a message needs to be set');
        }
        /**
         * @todo need to sanitize string, need check for UT8 characterset, also htmlentities(string,flags,character-set,double_encode)
         */
        self::$htmlMessage = $data['message'];
        if(isset($data['tracking_code']) && !empty($data['tracking_code']))
        {
            self::$htmlMessage .= '<img style="display:none;"src="'.APP_URL.'email-tracking/'.$data['tracking_code'].'" >';
        }
        if(isset($data['tracking_id']) && !empty($data['tracking_id']))
        {
            self::$htmlMessage .= '<img style="display:none;"src="'.APP_URL.'emailtrack/'.$data['tracking_id'].'" >';
        }
        
        $debug = isset($_ENV['DEBUG_TO_EMAIL'])?$_ENV['DEBUG_TO_EMAIL']:0;
        
        $mail = new PHPMailer(true);
        try
        {
            
            if (!empty($debug) && intval($debug) > 0)
            {
                //$data['to'] = getenv('DEBUG_TO_EMAIL');
                $mail->SMTPDebug = intval($debug);
            }
            $mail->IsSMTP(TRUE);
            $mail->Host = self::$smtpHost; // SMTP server
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);

            $_ENV['APPLICATION_ENVIRONMENT'] == 'production' ? $mail->SMTPAuth = true : '';
           
            
            $mail->Port = self::$smtpPort;                   // set the SMTP port for the GMAIL server
            $mail->Username = self::$smtpUsername;  // SMTP username
            $mail->Password = self::$smtpPassword;
            $mail->SetFrom(self::$fromEmail, self::$fromName);
            $mail->AddReplyTo(self::$fromEmail, self::$fromName);

            // optional items
            $mail->Subject = self::$subject;
            $mail->MsgHTML(self::$htmlMessage);

            if($_ENV['APPLICATION_ENVIRONMENT'] == 'production') 
            {
                if($_ENV['APP_EMAIL_SMTP_SECURE_REQUIRED'] == 1)
                {
                    /**
                     * $smtpSecure = For Mail servers such as Adit, which required SMTP secure true.
                     *  
                     */
                    (self::$smtpSecure=='' || self::$smtpSecure==false)?$mail->SMTPSecure=false:$mail->SMTPSecure=true;
                }
                /**
                 * $autoTLS = Required for servers for which required auto TLS.
                 */
                (self::$autoTLS=='' || self::$autoTLS==false)?$mail->SMTPAutoTLS=false:$mail->SMTPAutoTLS=true;
            }
             
            if (isset($data['to']) && strpos($data['to'], ',') !== false)
            {
                $to_emails = explode(',', $data['to']);
                for ($i = 0; $i < count($to_emails); $i++)
                {
                    if (!empty($to_emails[$i]))
                    {
                        $mail->AddAddress($to_emails[$i]);
                    }
                }
            }
            else
            {

                $mail->AddAddress($data['to'], $data['to']);
            }

            if (isset($data['cc']) && strpos($data['cc'], ',') !== false)
            {
                $cc_emails = explode(',', $data['cc']);
                for ($i = 0; $i < count($cc_emails); $i++)
                {
                    if (!empty($cc_emails[$i]))
                    {
                        $mail->AddCC($cc_emails[$i], $cc_emails[$i]);
                    }
                }
            }
            else if (isset($data['cc']) && !empty($data['cc']))
            {
                $mail->AddCC($data['cc'], $data['cc']);
            }

            if (isset($data['bcc']) && strpos($data['bcc'], ',') !== false)
            {
                $bcc_emails = explode(',', $data['bcc']);
                $bcc = $bcc_emails;
                for ($i = 0; $i < count($bcc_emails); $i++)
                {
                    if (!empty($bcc_emails[$i]))
                    {
                        $mail->AddBCC($bcc_emails[$i], $bcc_emails[$i]);
                    }
                }
            }
            else if (isset($data['bcc']) && !empty($data['bcc']))
            {
                $mail->AddBCC($data['bcc']);
            }
            if (!empty($data['attachments']) && isset($data['attachments']))
            {
                if(is_array($data['attachments']))
                {
                    if (count($data['attachments']) > 0)
                    {

                        for ($i = 0; $i < count($data['attachments']); $i++)
                        {
                            $mail->AddAttachment($data['attachments'][$i]);
                        }
                        //TODO save to server
                        // added for saving to sever
                    }
                }
                else
                {
                    $mail->AddAttachment($data['attachments']);
                }
            }
            if (!empty($data['addStringAttachment']) && isset($data['addStringAttachment']))
            {
                if (count($data['addStringAttachment']) > 0)
                {
                    for ($i = 0; $i < count($data['addStringAttachment']); $i++)
                    {

                        $mail->addStringAttachment($data['addStringAttachment'][$i]['content'], $data['addStringAttachment'][$i]['filename']);
                    }
                   
                }
            }
            $mail->Debugoutput = function($str, $level) {
                if (!file_exists((isset($_ENV['LOG_PATH'])?$_ENV['LOG_PATH']:'').self::$smtpLogPath))
                {
                    //mkdir((isset($_ENV['LOG_PATH'])?$_ENV['LOG_PATH']:'').self::$smtpLogPath, 0755, TRUE);
                }
                \EB\Core\Logger\EBLogger::info('smtp_log', ['smtp_log' => $str,'level' => $level], (isset($_ENV['LOG_PATH'])?$_ENV['LOG_PATH']:'').self::$smtpLogPath);
            };
            if ($mail->Send())
            {
                 self::setEmailLogStatus($data,\EB\Model\Eloquent\EmailLog::EMAIL_STATUS_SUCCESS);
                 $mail->ClearAllRecipients(); 
                 $mail->ClearAttachments(); 
                 $mail->clearAddresses();
                 $mail=NULL;
                return true;
            }
            else
            {
                self::setEmailLogStatus($data,\EB\Model\Eloquent\EmailLog::EMAIL_STATUS_FAIL,['fail_info' => $mail->ErrorInfo]);
                if (!empty($debug) && intval($debug) > 1) {
                    \EB\Model\Eloquent\ApplicationCodeException::create([
                       'message' =>  $mail->ErrorInfo,
                       'method' => 'email',
                       'class' => 'SendEmailService',
                       'trace_as_string' => $mail->ErrorInfo
                    ]);
                }
                return false;
            }
            
        }
        catch (\phpmailerException $e)
        {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer

//            $message = array();
//            $message['error'] = array('type' => 'Email Error 1: ', 'message' => '<pre>' . $e->errorMessage() . '</pre>');
//
//            die(json_encode($message));
            return false;
        }
        catch (\Exception $e)
        {
            echo $e->getMessage(); //Boring error messages from anything else!
            return false;
//            $message = array();
//            $message['error'] = array('type' => 'Email Error 2: ', 'message' => '<pre>' . $e->getMessage() . '</pre>');
//            $message['data'] = '';
//            die(json_encode($message));
        }
    }
    /**
     * @todo Need to implement auto TLS & smtp Secure.
     * @param array $data
     * @return boolean
     * @throws \Exception
     * 
     */
    public static function sendEmailWithSlim(array $data)
    {

        self::$smtpPort =  $_ENV['APPLICATION_ENVIRONMENT'] == 'development' ? $_ENV['MAILCATCHER_SMTP_PORT'] : $_ENV['APP_SMTP_PORT'];
        self::$smtpHost = $_ENV['APPLICATION_ENVIRONMENT'] == 'development' ? $_ENV['MAILCATCHER_SMTP_HOST'] : $_ENV['APP_SMTP_HOST'];

        self::$smtpUsername = $_ENV['APP_SMTP_USERNAME'];
        self::$smtpPassword = $_ENV['APP_SMTP_PASSWORD'];
        self::$fromEmail = $_ENV['APP_SMTP_USERNAME'];
        self::$fromName = $_ENV['APP_EMAIL_FROM_EMAIL'];
        self::$subject = isset($data['subject']) ? $data['subject'] : '';

        if(empty($data['message']) || !isset($data['message']))
        {
            throw new \Exception(' $data[message] is empty or null or missing, a message needs to be set');
        }
        /**
         * @todo need to sanitize string, need check for UT8 characterset, also htmlentities(string,flags,character-set,double_encode)
         */
        self::$htmlMessage= $data['message'];

        $debug = 0;

        $mail = new PHPMailer(true);
        try
        {

            if (!empty($debug) && intval($debug) == 1)
            {
                //$data['to'] = getenv('DEBUG_TO_EMAIL');
                $mail->SMTPDebug = 3;
            }
            $mail->IsSMTP(TRUE);
            $mail->Host = self::$smtpHost; // SMTP server
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);

            $_ENV['APPLICATION_ENVIRONMENT'] == 'production' ? $mail->SMTPAuth = true : '';


            $mail->Port = self::$smtpPort;                   // set the SMTP port for the GMAIL server
            $mail->Username = self::$smtpUsername;  // SMTP username
            $mail->Password = self::$smtpPassword;
            $mail->SetFrom(self::$fromEmail, self::$fromName);
            $mail->AddReplyTo(self::$fromEmail, self::$fromName);

            // optional items
            $mail->Subject = self::$subject;
            $mail->MsgHTML(self::$htmlMessage);
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;

            if (isset($data['to']) && strpos($data['to'], ',') !== false)
            {
                $to_emails = explode(',', $data['to']);
                for ($i = 0; $i < count($to_emails); $i++)
                {
                    if (!empty($to_emails[$i]))
                    {
                        $mail->AddAddress($to_emails[$i]);
                    }
                }
            }
            else
            {

                $mail->AddAddress($data['to'], $data['to']);
            }

            if (isset($data['cc']) && strpos($data['cc'], ',') !== false)
            {
                $cc_emails = explode(',', $data['cc']);
                for ($i = 0; $i < count($cc_emails); $i++)
                {
                    if (!empty($cc_emails[$i]))
                    {
                        $mail->AddCC($cc_emails[$i], $cc_emails[$i]);
                    }
                }
            }
            else if (isset($data['cc']) && !empty($data['cc']))
            {
                $mail->AddCC($data['cc'], $data['cc']);
            }

            if (isset($data['bcc']) && strpos($data['bcc'], ',') !== false)
            {
                $bcc_emails = explode(',', $data['bcc']);
                $bcc = $bcc_emails;
                for ($i = 0; $i < count($bcc_emails); $i++)
                {
                    if (!empty($bcc_emails[$i]))
                    {
                        $mail->AddBCC($bcc_emails[$i], $bcc_emails[$i]);
                    }
                }
            }
            else if (isset($bcc) && !empty($bcc))
            {
                $mail->AddBCC($bcc);
            }
            if (!empty($data['attachments']) && isset($data['attachments']))
            {
                if(is_array($data['attachments']))
                {
                    if (count($data['attachments']) > 0)
                    {

                        for ($i = 0; $i < count($data['attachments']); $i++)
                        {
                            $mail->AddAttachment($data['attachments'][$i]);
                        }
                        //TODO save to server
                        // added for saving to sever
                    }
                }
                else
                {
                    $mail->AddAttachment($data['attachments']);
                }
            }
            if (!empty($data['addStringAttachment']) && isset($data['addStringAttachment']))
            {
                if (count($data['addStringAttachment']) > 0)
                {

                    for ($i = 0; $i < count($data['addStringAttachment']); $i++)
                    {

                        $mail->addStringAttachment($data['addStringAttachment'][$i]['content'], $data['addStringAttachment'][$i]['filename']);
                    }

                }
            }

            //echo "<pre>",print_r($mail);die();
            if ($mail->Send())
            {
                 $mail->ClearAllRecipients(); 
                 $mail->ClearAttachments(); 
                 $mail->clearAddresses();
                 $mail=NULL;
                return true;
            }
            else
            {
               return false;
            }
        }
        catch (\phpmailerException $e)
        {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer

//            $message = array();
//            $message['error'] = array('type' => 'Email Error 1: ', 'message' => '<pre>' . $e->errorMessage() . '</pre>');
//
//            die(json_encode($message));
            return false;
        }
        catch (\Exception $e)
        {
            echo $e->getMessage(); //Boring error messages from anything else!
            return false;
//            $message = array();
//            $message['error'] = array('type' => 'Email Error 2: ', 'message' => '<pre>' . $e->getMessage() . '</pre>');
//            $message['data'] = '';
//            die(json_encode($message));
        }
    }
    
    /**
     * 
     * @param array $data -> This will give the email data
     * @param int $emailStatus -> This will give whether email sent succesfully or not
     * @param array $addtionalData -> additional data to save in DB.
     */
    private static function setEmailLogStatus(array $data,int $emailStatus,array $addtionalData = []): void {
        if(isset($data['tracking_id']) && !empty($data['tracking_id']))
        {
            $emailLog = \EB\Model\Eloquent\EmailLog::where('id', intval($data['tracking_id']))->first();
            if(isset($emailLog) && !empty($emailLog)) {
                $emailLog->email_status = intval($emailStatus);
                if(isset($addtionalData) && !empty($addtionalData)) {
                    $emailLogData = $emailLog->email_data;
                    $emailLogData['additional_data'] = $addtionalData;
                    $emailLog->email_data = $emailLogData;
                }
                $emailLog->save();
            }
        }
    }
}
