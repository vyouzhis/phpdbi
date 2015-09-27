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
*  	1. Smtp 发送邮件类 SMTP协议Socket实现
*  @Function List 
*   1.
*  @History 
*      <author>    <time>                        <version >   <desc>
*        King      Sun Jan 22 23:01:49 CST 2012  Beta 1.0           第一次建立该文件
*
*/

/**
* @desc Socket协议实现的Smtp推送邮件类
* @package  Mail
* @since  Sun Jan 22 23:03:12 CST 2012
* @final  Sun Jan 22 23:03:12 CST 2012
*/
class Smtp
{
    /**
    * @desc  以文本发送邮件
    * @var  int
    * @access  public
    */
    const MAIL_TYPE_TEXT = 0;

    /**
    * @desc  以HTML发送邮件
    * @var  int
    * @access  public
    */
    const MAIL_TYPE_HTML = 1;

    /**
    * @desc  Smtp协议端口
    * @var  int
    * @access  public
    */
    public $smtpPort = 25;

    /**
    * @desc  发送邮件的Socket连接超时时间
    * @var  int
    * @access  public
    */
    public $timeOut = 30;

    /**
    * @desc  发送域名
    * @var  string
    * @access  public
    */
    public $hostName = '';

    /**
    * @desc  日志文件路径
    * @var  string
    * @access  public
    */
    public $logFile = '';

    /**
    * @desc  真实服务器路径
    * @var  string
    * @access  public
    */
    public $relayHost = '';

    /**
    * @desc  是否开启调试模式
    * @var  bool
    * @access  public
    */
    public $debug = false;

    /**
    * @desc  是否进行身份验证
    * @var  bool
    * @access  
    */
    public $auth = false;

    /**
    * @desc  验证的登录用户名
    * @var  string
    * @access  public
    */
    public $username;

    /**
    * @desc  验证时的登录密码
    * @var  string
    * @access  public
    */
    public $password;

    /**
    * @desc  Socket连接句柄
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
    * @desc 发送邮件
    * @access   public
    * @param string $to 收件邮件地址 多个邮件地址可以,隔开
    * @param string $from 发送邮件地址
    * @param string $subject 邮件标题
    * @param string $body 邮件主体
    * @param string $mailtype 邮件类型 HTML和TEXT
    * @param string $cc 抄送地址
    * @param string $bcc 
    * @param string 附送的Header头 
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
        	$fromName = "义隆金融";
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
    * @desc 发送SMTP握手语
    * @access   private
    * @param $helo 握手语
    * @return  bool
    */
    private function _send($helo, $from, $to, $header, $body = '')
    {
        if (!$this->_putcmd("HELO", $helo))
        {
            return $this->_addError("sending HELO command");
        }

        /*如果进行权限认证*/
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
    * @desc 打开Smtp协议的Socket链接
    * @access   private
    * @param string $address 邮件地址
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
    * @desc 应答
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
    * @desc 没有真实IP时的握手
    * @access   private
    * @param string $address 邮件地址
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
    * @desc 压入Message
    * @access   public
    * @param $header 文件头信息
    * @param  string $body 主体信息
    * @return  bool
    */
    private function _putMessage($header, $body)
    {
        fputs($this->_sock, $header . "\r\n" . $body);
        $this->_debug("> " . str_replace("\r\n", "\n" . "> ", $header . "\n> " . $body."\n> "));
        return true;
    }

    /**
    * @desc 邮件内容边界符
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
    * @desc 压入CMD
    * @access   private
    * @param string $cmd CMD内容 
    * @return  string $arg 参数
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
    * @desc 添加一条错误
    * @access   private
    * @param string $errorMessage 错误信息
    * @return  false
    */
    private function _addError($errorMessage)
    {
        $this->_writeLog("Error: Error occurred while " . $errorMessage . ".\n");
        return false;
    }

    /**
   * @desc 写入日志
   * @access   private
   * @param string $message 日志内容
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
    * @desc 消除脚本格式
    * @access   private
    * @param string $address  地址
    * @return  string
    */
    private function _getStripComment($address)
    {

        return preg_replace("/\([^()]*\)/", "", $address);
    }

    /**
    * @desc 获取邮件地址
    * @access   private
    * @param string $address 地址 
    * @return  string
    */
    private function _getAddress($address)
    {
        return preg_replace("/^.*<(.+)>.*$/", "$1", preg_replace("/([\s\t\r\n])+/", "", $address));
    }

    /**
    * @desc 输出调试信息 
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