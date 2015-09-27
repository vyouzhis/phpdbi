<?php
/**
*
* @Copyright (C), 2011-, King.$i
* @Name  Smtp.php
* @Author  King
* @Version  Beta 1.0
* @Date  Sun Jan 22 23:01:49 CST 2012
* @Description 
* @Class List 
*  	1. Smtp �����ʼ��� SMTPЭ��Socketʵ��
*  @Function List 
*   1.
*  @History 
*      <author>    <time>                        <version >   <desc>
*        King      Sun Jan 22 23:01:49 CST 2012  Beta 1.0           ��һ�ν������ļ�
*
*/

/**
* @desc SocketЭ��ʵ�ֵ�Smtp�����ʼ���
* @package  Mail
* @since  Sun Jan 22 23:03:12 CST 2012
* @final  Sun Jan 22 23:03:12 CST 2012
*/
class Smtp
{
    /**
    * @desc  ���ı������ʼ�
    * @var  int
    * @access  public
    */
    const MAIL_TYPE_TEXT = 0;

    /**
    * @desc  ��HTML�����ʼ�
    * @var  int
    * @access  public
    */
    const MAIL_TYPE_HTML = 1;

    /**
    * @desc  SmtpЭ��˿�
    * @var  int
    * @access  public
    */
    public $smtpPort = 25;

    /**
    * @desc  �����ʼ���Socket���ӳ�ʱʱ��
    * @var  int
    * @access  public
    */
    public $timeOut = 30;

    /**
    * @desc  ��������
    * @var  string
    * @access  public
    */
    public $hostName = '';

    /**
    * @desc  ��־�ļ�·��
    * @var  string
    * @access  public
    */
    public $logFile = '';

    /**
    * @desc  ��ʵ������·��
    * @var  string
    * @access  public
    */
    public $relayHost = '';

    /**
    * @desc  �Ƿ�������ģʽ
    * @var  bool
    * @access  public
    */
    public $debug = false;

    /**
    * @desc  �Ƿ���������֤
    * @var  bool
    * @access  
    */
    public $auth = false;

    /**
    * @desc  ��֤�ĵ�¼�û���
    * @var  string
    * @access  public
    */
    public $username;

    /**
    * @desc  ��֤ʱ�ĵ�¼����
    * @var  string
    * @access  public
    */
    public $password;

    /**
    * @desc  Socket���Ӿ��
    * @var  #resource
    * @access  private 
    */
    private $_sock;

    /* Constractor */
    function __construct($relayHost = "", $smtpPort = 25, $auth = false, $username = '', $password = '')
    {
        $this->debug = false;
        $this->smtpPort = (int)$smtpPort;
        $this->relayHost = $relayHost;
        $this->auth = (bool)$auth;
        $this->username = $username;
        $this->password = $password;
        $this->hostName = "localhost"; //is used in HELO command
        $this->logFile = "";
        $this->_sock = false;
    }

    /**
    * @desc �����ʼ�
    * @access   public
    * @param string $to �ռ��ʼ���ַ ����ʼ���ַ����,����
    * @param string $from �����ʼ���ַ
    * @param string $subject �ʼ�����
    * @param string $body �ʼ�����
    * @param string $mailtype �ʼ����� HTML��TEXT
    * @param string $cc ���͵�ַ
    * @param string $bcc 
    * @param string ���͵�Headerͷ 
    * @return  bool
    */
    function sendMail($to, $from, $subject = "", $body = "", $mailType = self::MAIL_TYPE_TEXT, $fromName = '',$cc = "", $bcc = "", $additionalHeaders = "")
    {
        $mailFrom = $this->_getAddress($this->_getStripComment($from));
        $body = preg_replace("/(^|(\r\n))(\.)/", "$1.$3", $body);
        $header .= "MIME-Version:1.0\r\n";
        if($mailType == self::MAIL_TYPE_HTML)
        {
            $header .= "Content-Type:text/html;\r\n";
        }
        else 
        {
             $header .= "Content-Type:text/plain;\r\n";
        }
        $header .= "To: " . $to . "\r\n";
        if ($cc != "")
        {
            $header .= "Cc: " . $cc . "\r\n";
        }
        if ($fromName == '') 
        {
        	$fromName = "��¡����";
        }
        $header .= "From: " . $fromName. "<" . $from . ">\r\n";
        $header .= "Subject: =?UTF-8?B?".base64_encode($subject)."\r\n";
        $header .= $additionalHeaders;
        $header .= "Date: " . date("r") . "\r\n";
        $header .= "X-Mailer:By Redhat (PHP/" . PHP_VERSION . ")\r\n";
        list($msec, $sec) = explode(" ", microtime());
        $header .= "Message-ID: <" . date("YmdHis", $sec) . "." . ($msec * 1000000) . "." . $mailFrom.">\r\n";
        $to = explode(",", $this->_getStripComment($to));

        if ($cc != "")
        {
            $to = array_merge($to, explode(",", $this->_getStripComment($cc)));
        }
        if ($bcc != "")
        {
            $to = array_merge($to, explode(",", $this->_getStripComment($bcc)));
        }

        $sent = true;
        foreach ($to as $rcptTo)
        {
            $rcptTo = $this->_getAddress($rcptTo);
            if (!$this->_open($rcptTo))
            {
                $this->_writeLog("Error: Cannot send email to " . $rcptTo . "\n");
                $sent = false;
                continue;
            }
            if ($this->_send($this->hostName, $mailFrom, $rcptTo, $header, $body, $attachment))
            {
                $this->_writeLog("E-mail has been sent to <" . $rcptTo . ">\n");
            }
            else
            {
                $this->_writeLog("Error: Cannot send email to <" . $rcptTo . ">\n");
                $sent = false;
            }
            fclose($this->_sock);
            $this->_writeLog("Disconnected from remote host\n");
        }
        return $sent;
    }

    /**
    * @desc ����SMTP������
    * @access   private
    * @param $helo ������
    * @return  bool
    */
    private function _send($helo, $from, $to, $header, $body = '')
    {
        if (!$this->_putcmd("HELO", $helo))
        {
            return $this->_addError("sending HELO command");
        }

        /*�������Ȩ����֤*/
        if($this->auth)
        {
            if (!$this->_putcmd("AUTH LOGIN", base64_encode($this->username)))
            {
                return $this->_addError("sending HELO command");
            }
            if (!$this->_putcmd("", base64_encode($this->password)))
            {
                return $this->_addError("sending HELO command");
            }
        } /*end of if($this->auth)*/


        if (!$this->_putcmd("MAIL", "FROM:<" . $from . ">"))
        {
            return $this->_addError("sending MAIL FROM command");
        }
        if (!$this->_putcmd("RCPT", "TO:<" . $to . ">"))
        {
            return $this->_addError("sending RCPT TO command");
        }
        if (!$this->_putcmd("DATA"))
        {
            return $this->_addError("sending DATA command");
        }
        if (!$this->_putMessage($header, $body))
        {
            return $this->_addError("sending message");
        }

        if (!$this->_eom())
        {
            return $this->_addError("sending <CR><LF>.<CR><LF> [EOM]");
        }
        if (!$this->_putcmd("QUIT"))
        {
            return $this->_addError("sending QUIT command");
        }
        return true;
    }

    /**
    * @desc ��SmtpЭ���Socket����
    * @access   private
    * @param string $address �ʼ���ַ
    * @return  bool
    */
    private function _open($address)
    {
        if ($this->relayHost == "")
        {
            return $this->_mx($address);
        }
        else
        {
            return $this->_relay();
        }
    }

    /**
    * @desc Ӧ��
    * @access  private 
    * @param void
    * @return  bool
    */
    private function _relay()
    {
        $this->_writeLog("Trying to " . $this->relayHost . ":" . $this->smtpPort . "\n");
        $this->_sock = fsockopen($this->relayHost, $this->smtpPort, $errorNo, $errorString, $this->timeOut);
        if (!($this->_sock && $this->_ok()))
        {
            $this->_writeLog("Error: Cannot connenct to relay host " . $this->relayHost . "\n");
            $this->_writeLog("Error: " . $errorString . " (" . $errorNo . ")\n");
            return false;
        }
        $this->_writeLog("Connected to relay host " . $this->relayHost . "\n");
        return true;;
    }

    /**
    * @desc û����ʵIPʱ������
    * @access   private
    * @param string $address �ʼ���ַ
    * @return  bool
    */
    private function _mx($address)
    {
        $domain = preg_replace("/^.+@([^@]+)$/", "$1", $address);
        if (!getmxrr($domain, $mxHosts))
        {
            $this->_writeLog("Error: Cannot resolve MX \"" . $domain . "\"\n");
            return false;
        }

        foreach ($mxHosts as $host)
        {
            $this->_writeLog("Trying to " . $host.":" . $this->smtpPort . "\n");
            $this->_sock = fsockopen($host, $this->smtpPort, $errno, $errstr, $this->timeOut);
            if (!($this->_sock && $this->_ok()))
            {
                $this->_writeLog("Warning: Cannot connect to mx host " . $host . "\n");
                $this->_writeLog("Error: " . $errstr." (" . $errno . ")\n");
                continue;
            }
            $this->_writeLog("Connected to mx host " . $host . "\n");
            return true;
        } /*end of foreach ($mxHosts as $host)*/

        $this->_writeLog("Error: Cannot connect to any mx hosts (".implode(", ", $mxHosts).")\n");
        return false;
    }

    /**
    * @desc ѹ��Message
    * @access   public
    * @param $header �ļ�ͷ��Ϣ
    * @param  string $body ������Ϣ
    * @return  bool
    */
    private function _putMessage($header, $body)
    {
        fputs($this->_sock, $header . "\r\n" . $body);
        $this->_debug("> " . str_replace("\r\n", "\n" . "> ", $header . "\n> " . $body."\n> "));
        return true;
    }

    /**
    * @desc �ʼ����ݱ߽��
    * @access   private
    * @param void
    * @return  bool
    */
    private function _eom()
    {
        fputs($this->_sock, "\r\n.\r\n");
        $this->_debug(". [EOM]\n");
        return $this->_ok();
    }

    /**
    * @desc OK 
    * @access  private 
    * @param void
    * @return  bool
    */
    private function _ok()
    {
        $response = str_replace("\r\n", "", fgets($this->_sock, 512));
        $this->_debug($response."\n");
        if (!preg_match("/^[23]/", $response))
        {
            fputs($this->_sock, "QUIT\r\n");
            fgets($this->_sock, 512);
            $this->_writeLog("Error: Remote host returned \"".$response."\"\n");
            return false;
        }
        return true;
    }

    /**
    * @desc ѹ��CMD
    * @access   private
    * @param string $cmd CMD���� 
    * @return  string $arg ����
    */
    private function _putcmd($cmd, $arg = '')
    {
        if ($arg != "")
        {
            $cmd = ($cmd == '') ? $arg : $cmd . ' ' . $arg;
        }

        fputs($this->_sock, $cmd . "\r\n");
        $this->_debug('> ' . $cmd . "\n");
        return $this->_ok();
    }

    /**
    * @desc ���һ������
    * @access   private
    * @param string $errorMessage ������Ϣ
    * @return  false
    */
    private function _addError($errorMessage)
    {
        $this->_writeLog("Error: Error occurred while " . $errorMessage . ".\n");
        return false;
    }

    /**
   * @desc д����־
   * @access   private
   * @param string $message ��־����
   * @return  bool
   */
    private function _writeLog($message)
    {
        $this->_debug($message);
        if ($this->logFile == "")
        {
            return true;
        }
        $message = date("M d H:i:s ") . get_current_user() . "[" . getmypid() . "]: " . $message;
        if (!is_file($this->logFile) || !($fp = fopen($this->logFile, "a")))
        {
            $this->_debug("Warning: Cannot open log file \"" . $this->logFile ."\"\n");
            return false;
        }
        flock($fp, LOCK_EX);
        fputs($fp, $message);
        fclose($fp);
        return true;
    }

    /**
    * @desc �����ű���ʽ
    * @access   private
    * @param string $address  ��ַ
    * @return  string
    */
    private function _getStripComment($address)
    {

        return preg_replace("/\([^()]*\)/", "", $address);
    }

    /**
    * @desc ��ȡ�ʼ���ַ
    * @access   private
    * @param string $address ��ַ 
    * @return  string
    */
    private function _getAddress($address)
    {
        return preg_replace("/^.*<(.+)>.*$/", "$1", preg_replace("/([\s\t\r\n])+/", "", $address));
    }

    /**
    * @desc ���������Ϣ 
    * @access  private
    * @param $message
    * @return  void
    */
    private function _debug($message)
    {
        if ($this->debug)
        {
            echo $message . '<br />';
        }
    }

}
?>