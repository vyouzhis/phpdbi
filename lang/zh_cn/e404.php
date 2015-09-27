<?php
if (!defined('M5CPL'))
exit;

class _e404 extends LibLang{
	private $_lan_article;
	
	public function __construct() {
		$this->_lan_article = array(
			"articl_list"=>"文章列表",
			"article_desc"=>"用户在这儿可以查看及添加文章",
			"articl_">"dfd",
		);
		
		parent::__construct($this->_lan_article); 					
	}

	
}
