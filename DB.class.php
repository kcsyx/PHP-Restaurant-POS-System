<?php
	/******************************************************************
	DB.class.php
	This file has all the methods to run database queries.
	******************************************************************/
class DB
{
	/****************************************************************************
	* ATTRIBUTES                                                                *
	****************************************************************************/
	
	var $id;
	var $host;
	var $db;
	var $user;
	var $mysqli;
	var $password;
	var $connection;
	var $connected;
	
	var $querystring;
	var $debugmode;
	var $logfile;
	var $totalquerytime;
	
	/****************************************************************************
	* CONSTRUCTOR                                                               *
	****************************************************************************/
	
	function __construct($id="")
	{
		$this->id = $id; // Unique Identifier
		$this->mysqli = "";
		$this->host = "";
		$this->db = "";
		$this->user = "";
		$this->password = "";
		$this->connected = 0;
	}
	
	//FOR SELECTION OF TABLES ONLY AND RETURN OF DATA
	// Default $number of results is more than 0 = more than 1 results
	// If the return result is only 1 like getting a single item, pass in $numberOfResults = 1
	function select_query($querystring, $numberOfResults=0)
	{
		//echo "QUERY: $querystring<br>";	
		// NEW SQL STANDARD
		$mysqli = new MySQLi($this->host, $this->user, $this->password, $this->db);
		$mysqli->set_charset("utf8");
		
		if ($resultQuery = $mysqli->query($querystring)):
			// returns array for results likely more than one row
			if ($numberOfResults == 0):
				$rows = array();
				while ($row = $resultQuery->fetch_assoc()):
					array_push($rows, $row);	
				endwhile;
				
				$result = $rows;			
			else:
				// returns only one result	
				$result = $resultQuery->fetch_assoc();
				// Check for NULL return
				if ($result == NULL):
					$result = 505;
				endif;
			endif;
			 // free result set
			$resultQuery->close();	
		else:
			// error from DB / SQL
			$result = 500;
		endif;
	
		// close connection
		$mysqli->close();
						
		return $result;
	}
	
	function update_query($querystring)
	{	
		$id = "";
	
		// NEW SQL STANDARD
		$mysqli = new MySQLi($this->host, $this->user, $this->password, $this->db);
		$mysqli->set_charset("utf8");
		
		if ($resultQuery = $mysqli->query($querystring)):
			if ($mysqli->affected_rows > 0):
				if (isset($mysqli->insert_id)):
					$id = $mysqli->insert_id;				
				endif;
				
				$result = array('update'=>200, 'id' => $id);
			else:
				$result = 503;
			endif;
		else:
			// error from DB / SQL
			$result = 500;
		endif;
		
		// close connection
		$mysqli->close();
			
		return $result;
	}
	
	//-----------------------------------------------------------------------------
	function logerrors($logfile)
	{
		$this->logfile = $logfile;
	}

} // end Class

?>