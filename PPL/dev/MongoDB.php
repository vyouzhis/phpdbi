<?php

class MGDB {
	//static MGDB dataSource = null;
	//private MongoClient mongoClient = null;
	//private DB db = null;

	private  $gt = "\$gt"; // >
	private  $gte = "\$gte"; // >=
	private  $in = "\$in"; // in
	private  $lt = "\$lt"; // <
	private  $lte = "\$lte"; // <=
	private  $ne = "\$ne"; // !=
	private  $nin = "\$nin"; // !in
	private  $or = "\$or";
	private  $and = "\$and";
	private  $not = "\$not";
	private  $exists = "\$exists";
	private  $type = "\$type";
	private  $mod = "\$mod";
	private  $regex = "\$regex";
	private  $text = "\$text";
	private  $where = "\$where";

	//	// Geospatial
	//	/*
	//	 * getwithin
	//	 */
	//	private  geoWithin = "$geoWithin";
	//	private  geoIntersects = "$geoIntersects";
	//	private  near = "$near";
	//	private  nearSphere = "$nearSphere";
	//
	//	// Array
	//	private  all = "$all";
	//	private  elemMatch = "$elemMatch";
	//	private  size = "$size";
	//	// Projection Operators
	//	// private  elemMatch="$elemMatch";
	//	private  meta = "$meta";
	//	private  slice = "$slice";
	//
	//	//
	//	private BasicDBObject obj_project = null;
	//	private BasicDBObject obj_where = null;
	//	private BasicDBObject obj_sort = null;
	//	private int obj_limit = 30;
	//	private BasicDBObject obj_row = null;
	//	private BasicDBObject obj_columns = null;

	private  $link_ID;
	private $db;
	private $collection;

	public function __construct(){
		$this->link_ID = new MongoClient("mongodb://127.0.0.1:27017");
		$this->db = $this->link_ID->horribilis;
		$col ="mc_".date("Y-m-d", time());
		$this->collection = $this->db->$col;
		
	}

	public function Query( $sql) {
		$collection->insert($this->document);
	}

	public function collectionList() {
		$list = $this->db->getCollectionNames();

		foreach ($list as $coltion) {
			$colName = $coltion;
			echo '<p>Collection name is: <em>';
			echo $colName;
			echo '.</em></p>';
		}
	}

	public function clean_obj() {
		
	}

	/**
	 * projection list
	 *
	 * @param doProjection
	 */
	public function doProjection($pList) {

		

	}

	/**
	 *
	 * @return
	 */
	public function doWhere($wMap) {
		
	}

	public function doSort($sList) {
		
	}

	public function doLimit( $dInt) {
		
	}

	public function doRow($rMap) {
		
	}

	/*
	 * arg = {"collection":"dd", "sort":"s","limit":10, "where":"s"}
	 */
	public function  FetchListMap($collection) {
		
		return null;
	}

	
	public function FetchOneMap( $collection) {
		

		return null;
	}

	public function Delete( $collection) {
		
	}

	public function Insert( $collection) {
		
	}

	public function Update( $collection) {
		
	}

	public function save( $collection) {
		
	}

}