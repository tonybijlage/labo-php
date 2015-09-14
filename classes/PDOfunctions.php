<?php

/*

 	openConnection()
	getDataFromTable($query, $placeHolders = array())
	getColumnNames($data, $leading = false)
	returnConnection()
	performQuery($query, $placeHolders = array())


*/
class PDOfunctions
{	

	public $password;
	public $host;
	public $userName;
	public $dBase;
	public $connect;

	function __construct($host, $dBase, $userName,$password = '')
	{

		$this->host 	= $host;		
		$this->dBase 	= $dBase;
		$this->userName = $userName;
		$this->password = $password;
		$this->connect 	= $this->openConnection();

	}

	private function openConnection()
	{
		$con = new PDO('mysql:=' . $this->host .
					 ';dbname=' . $this->dBase,
					 $this->userName, 
					 $this->password);
		return	$con;
	}
	
	public function getDataFromTable($query, $placeHolders = array())
	{		

		//$statement = $this->dBase->prepare( $query );
		$statement = $this->connect->prepare( $query );
			
			if ( $placeHolders )
				{
					foreach ( $placeHolders as $ph => $phValue )
						{
							$statement->bindValue( $ph, $phValue );
						}
				}

		
		$statement->execute( );

		$result = $this->returnArray( $statement );
			
		if ( empty( $result ) )
		{
			$result = FALSE;
		}

		return $result;

	}

	public function performQuery($query, $placeHolders = array())
	{

		$statement = $this->connect->prepare( $query );
		
			if ( $placeHolders )
			{
				foreach ( $placeHolders as $ph => $phValue )
					{
						$statement->bindValue( $ph, $phValue );
					}
			}

			$statement->execute();

			$result = $this->returnArray( $statement );

			if ( empty( $result ) )
			{
				$result = FALSE;
			}
			return $result;

	}

	public function returnArray( $statement )
		{
			$container	=	array();
			while( $row = $statement->fetch( PDO::FETCH_ASSOC ) )
			{
				$container[] = $row;
			}
			return $container;
		}

	public function returnConnection()
	{
		return $this->connect;
	}

	public function getColumnNames($data, $leading = false)
	{
		
		$headers = array();
		if ($data)
			{
			
				if (count($data > 0))
					{
						if( $leading)
							{
								$headers[ 0 ] ='#';
							}
						foreach( $data[0] as $key =>$d)
							{
								$headers[ ]	=	$key;
							}							
					}
			}
			
		return $headers;
	}

}//end class

?>